<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
}
