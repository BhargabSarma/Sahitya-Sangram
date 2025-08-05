<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class AdminOrderPaymentController extends Controller
{
    // List all orders
    public function orders()
    {
        $orders = Order::with('user', 'items.book')->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    // Show details of an order
    public function orderShow($id)
    {
        $order = Order::with('user', 'items.book')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    // List all payments
    public function payments()
    {
        $payments = Payment::with('order.address')->get();
        return view('admin.payments.index', compact('payments'));
    }

    // Show details of a payment
    public function paymentShow($id)
    {
        $payment = Payment::with('order.user')->findOrFail($id);
        return view('admin.payments.show', compact('payment'));
    }

    // Optionally: update payment status
    public function updatePaymentStatus(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $request->validate(['status' => 'required|string']);
        $payment->status = $request->status;
        $payment->save();
        return back()->with('success', 'Payment status updated.');
    }

    public function createShipment($orderId)
    {
        $order = Order::with('items.book', 'address', 'user')->findOrFail($orderId);

        // You can reuse the Shiprocket integration logic from OrderController
        $address = $order->address;
        if (!$address) {
            return back()->with('error', 'No address found for this order.');
        }

        $payload = [
            'order_id' => $order->id,
            'order_date' => now()->format('Y-m-d'),
            'pickup_location' => 'Default',
            'billing_customer_name' => $address->full_name ?: $address->name ?: 'Test User',
            'billing_address' => $address->street_address ?: 'Test Street',
            'billing_city' => $address->city ?: 'Test City',
            'billing_state' => $address->state ?: 'Test State',
            'billing_pincode' => $address->zip ?: '123456',
            'billing_country' => $address->country ?: 'India',
            'billing_email' => $order->user->email ?? 'test@example.com',
            'billing_phone' => $address->phone ?: '9999999999',
            'shipping_is_billing' => true,
            'order_items' => $order->items->map(function ($item) {
                return [
                    'name' => $item->book->title,
                    'sku' => $item->book_id,
                    'units' => $item->quantity,
                    'selling_price' => $item->price,
                ];
            })->values()->toArray(),
            'payment_method' => 'Prepaid',
            'shipping_charges' => 0,
            'total_discount' => 0,
            'sub_total' => $order->total,
            'length' => 10,
            'breadth' => 10,
            'height' => 1,
            'weight' => 0.5,
        ];

        try {
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
                return back()->with('success', 'Shipment created successfully.');
            } else {
                $order->shiprocket_response = $response->body();
                $order->save();
                return back()->with('error', 'Shiprocket error: ' . $response->body());
            }
        } catch (\Exception $e) {
            $order->shiprocket_response = $e->getMessage();
            $order->save();
            return back()->with('error', 'Could not create shipment: ' . $e->getMessage());
        }
    }
}
