<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Address;

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
            return $carry + ($item->book->digital_price * $item->quantity);
        }, 0);

        // Fetch the full address
        $address = Address::findOrFail($request->address_id);

        // Convert address to JSON
        $shippingAddress = json_encode([
            'name' => $address->name ?? '',
            'phone' => $address->phone ?? '',
            'street' => $address->street ?? '',
            'city' => $address->city ?? '',
            'state' => $address->state ?? '',
            'postal_code' => $address->postal_code ?? '',
            'country' => $address->country ?? '',
        ]);

        // Save address_id and shipping_address to order
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
                'type' => $item->type, // <-- Add this line
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        $cart->items()->delete();

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
