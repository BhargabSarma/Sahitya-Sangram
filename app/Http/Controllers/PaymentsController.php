<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PaymentsController extends Controller
{
    // Show payment page for an order
    public function show($orderId)
    {
        $user = Auth::user();
        $order = Order::where('id', $orderId)
            ->where('user_id', $user->id) // or use Auth::id()
            ->firstOrFail();

        return view('payments.show', compact('order'));
    }

    // Initiate payment (stub for Razorpay integration)
    public function pay(Request $request, $orderId)
    {
        $user = Auth::user();
        $order = Order::where('id', $orderId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Create a payment record
        $payment = Payment::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(), // <-- FIXED HERE
            'amount' => $order->total,
            'status' => 'pending',
            'payment_method' => 'razorpay', // or others
        ]);

        // Razorpay integration would go here
        /*
        $razorpayOrder = Razorpay::createOrder([
            'amount' => $order->total * 100, // in paisa
            'currency' => 'INR',
            'receipt' => $order->id,
            // other params...
        ]);
        return view('payments.razorpay', [
            'razorpayOrder' => $razorpayOrder,
            'order' => $order,
        ]);
        */

        // For now, just mark as paid for demo
        $payment->status = 'completed';
        $payment->save();

        $order->status = 'completed';
        $order->save();

        return redirect()->route('library.index')->with('success', 'Payment successful, your book is in your library!');
    }

    // Razorpay callback/webhook handler (to be implemented)
    public function callback(Request $request)
    {
        // Handle Razorpay response here in production
        // You'd verify payment and update records
    }
}
