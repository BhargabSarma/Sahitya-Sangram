<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Address;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $cart = Cart::where('user_id', Auth::id())->with('items.book')->first();
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        // Fetch addresses for this user
        $addresses = Address::where('user_id', Auth::id())->get();
        $defaultAddress = $addresses->where('is_default', true)->first();

        return view('order.checkout', compact('cart', 'addresses', 'defaultAddress'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
        ]);

        $cart = Cart::where('user_id', Auth::id())->with('items.book')->first();
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $total = $cart->items->reduce(function ($carry, $item) {
            if ($item->type === 'hard_copy') {
                return $carry + ($item->book->hard_copy_price * $item->quantity);
            } else {
                return $carry + ($item->book->digital_price * $item->quantity);
            }
        }, 0);

        $address = Address::findOrFail($request->address_id);

        $shippingAddress = json_encode([
            'name' => $address->name ?? '',
            'phone' => $address->phone ?? '',
            'street' => $address->street ?? '',
            'city' => $address->city ?? '',
            'state' => $address->state ?? '',
            'postal_code' => $address->postal_code ?? '',
            'country' => $address->country ?? '',
        ]);

        $order = Order::create([
            'user_id' => Auth::id(),
            'address_id' => $request->address_id,
            'shipping_address' => $shippingAddress,
            'total' => $total,
            'status' => 'pending',
        ]);

        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'book_id' => $item->book_id,
                'type' => $item->type,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);

            // Deplete inventory for hard_copy
            if ($item->type === 'hard_copy') {
                $inventory = \App\Models\Inventory::where('book_id', $item->book_id)->first();
                if ($inventory) {
                    if ($inventory->stock < $item->quantity) {
                        return redirect()->route('cart.index')
                            ->with('error', "Not enough stock for {$item->book->title}!");
                    }
                    $inventory->stock -= $item->quantity;
                    $inventory->save();
                }
            }
        }

        $cart->items()->delete();

        // --- Shiprocket Integration ---
        try {
            $addressObj = json_decode($shippingAddress);

            $payload = [
                'order_id' => $order->id,
                'order_date' => now()->format('Y-m-d'),
                'pickup_location' => 'Default',
                'billing_customer_name' => $addressObj->name ?? '',
                'billing_address' => $addressObj->street ?? '',
                'billing_city' => $addressObj->city ?? '',
                'billing_state' => $addressObj->state ?? '',
                'billing_pincode' => $addressObj->postal_code ?? '',
                'billing_country' => $addressObj->country ?? '',
                'billing_email' => Auth::user()->email ?? '',
                'billing_phone' => $addressObj->phone ?? '',
                'order_items' => $order->items->map(function ($item) {
                    return [
                        'name' => $item->book->title,
                        'sku' => $item->book_id,
                        'units' => $item->quantity,
                        'selling_price' => $item->price,
                    ];
                })->values()->toArray(),
                'payment_method' => 'Prepaid', // or 'COD'
                'shipping_charges' => 0,
                'total_discount' => 0,
                'sub_total' => $order->total,
                'length' => 10,
                'breadth' => 10,
                'height' => 1,
                'weight' => 0.5,
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.shiprocket.token'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post('https://apiv2.shiprocket.in/v1/external/orders/create/adhoc', $payload);

            if ($response->successful()) {
                $shipData = $response->json();
                $order->shiprocket_awb = $shipData['awb_code'] ?? null;
                $order->shiprocket_shipment_id = $shipData['shipment_id'] ?? null;
                $order->shiprocket_response = json_encode($shipData);
                $order->status = 'shipped';
                $order->save();
            } else {
                $order->shiprocket_response = $response->body();
                $order->save();
                return redirect()->route('order.history')->with('error', 'Shiprocket error: ' . $response->body());
            }
        } catch (\Exception $e) {
            $order->shiprocket_response = $e->getMessage();
            $order->save();
            return redirect()->route('order.history')->with('error', 'Could not create shipment: ' . $e->getMessage());
        }

        return redirect()->route('payments.show', $order->id)
            ->with('success', 'Order placed! Please complete your payment.');
    }
    public function confirmation($orderId)
    {
        $order = Order::with('items.book')->findOrFail($orderId);
        return view('order.confirmation', compact('order'));
    }

    public function history()
    {
        $orders = Order::where('user_id', Auth::id())->with('items.book')->latest()->get();
        return view('order.history', compact('orders'));
    }
}
