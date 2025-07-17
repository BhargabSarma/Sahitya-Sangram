<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $cart = Cart::where('user_id', Auth::id())->with('items.book')->first();
        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        return view('order.checkout', compact('cart'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'address.line1' => 'required|string|max:255',
            'address.city' => 'required|string|max:100',
            'address.state' => 'required|string|max:100',
            'address.zip' => 'required|string|max:20',
            'address.country' => 'required|string|max:100',
        ]);

        $cart = Cart::where('user_id', Auth::id())->with('items.book')->first();
        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $total = $cart->items->reduce(function ($carry, $item) {
            return $carry + ($item->book->digital_price * $item->quantity);
        }, 0);

        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $total,
            'status' => 'pending',
            'shipping_address' => $request->input('address'),
        ]);

        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'book_id' => $item->book_id,
                'quantity' => $item->quantity,
                'price' => $item->book->digital_price,
            ]);
        }

        $cart->items()->delete();

        // Redirect to payment page instead of confirmation
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
