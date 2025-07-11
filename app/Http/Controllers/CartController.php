<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Get logged-in user's cart or create one
    protected function getCart()
    {
        $user = Auth::user();
        return Cart::firstOrCreate(['user_id' => $user->id]);
    }

    public function index()
    {
        $cart = $this->getCart()->load('items.book');
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, $bookId)
    {
        $cart = $this->getCart();
        $item = $cart->items()->where('book_id', $bookId)->first();
        if ($item) {
            $item->quantity += 1;
            $item->save();
        } else {
            $cart->items()->create([
                'book_id' => $bookId,
                'quantity' => 1,
            ]);
        }

        // Calculate cart count (sum of all quantities)
        $cart_count = $cart->items()->sum('quantity');

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['cart_count' => $cart_count]);
        }

        return redirect()->route('cart.index')->with('success', 'Book added to cart!');
    }

    public function update(Request $request, $bookId)
    {
        $cart = $this->getCart();
        $item = $cart->items()->where('book_id', $bookId)->first();
        if ($item) {
            $item->quantity = max(1, (int)$request->input('quantity', 1));
            $item->save();
        }

        // Calculate cart count (sum of all quantities)
        $cart_count = $cart->items()->sum('quantity');

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['cart_count' => $cart_count]);
        }

        return redirect()->route('cart.index');
    }

    public function remove(Request $request, $bookId)
    {
        $cart = $this->getCart();
        $cart->items()->where('book_id', $bookId)->delete();

        // Calculate cart count (sum of all quantities)
        $cart_count = $cart->items()->sum('quantity');

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['cart_count' => $cart_count]);
        }

        return redirect()->route('cart.index')->with('success', 'Book removed from cart!');
    }

    public function clear(Request $request)
    {
        $cart = $this->getCart();
        $cart->items()->delete();

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['cart_count' => 0]);
        }

        return redirect()->route('cart.index')->with('success', 'Cart cleared!');
    }
}
