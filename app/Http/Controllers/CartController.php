<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Get the cart: session for guests, DB for logged-in users.
     */
    protected function getCartData()
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            $items = $cart->items()->with('book')->get();
            return [
                'type' => 'db',
                'cart' => $cart,
                'items' => $items,
            ];
        } else {
            $sessionCart = session()->get('cart', []);
            $items = collect($sessionCart)->map(function ($item, $bookId) {
                $book = Book::find($bookId);
                return (object)[
                    'book' => $book,
                    'quantity' => $item['quantity'],
                    'book_id' => $bookId,
                ];
            });
            return [
                'type' => 'session',
                'cart' => null,
                'items' => $items,
            ];
        }
    }

    public function index()
    {
        $cartData = $this->getCartData();
        return view('cart.index', ['cartItems' => $cartData['items']]);
    }

    public function add(Request $request, $bookId)
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            $item = $cart->items()->where('book_id', $bookId)->first();
            $type = $request->input('type', 'hard_copy');
            $price = $request->input('price', 0);

            if ($item) {
                $item->quantity += 1;
                // Ensure type and price are always set
                $item->type = $type;
                $item->price = $price;
                $item->save();
            } else {
                $cart->items()->create([
                    'book_id' => $bookId,
                    'quantity' => 1,
                    'type' => $type,
                    'price' => $price,
                ]);
            }
            $cart_count = $cart->items()->sum('quantity');
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$bookId])) {
                $cart[$bookId]['quantity'] += 1;
            } else {
                $cart[$bookId] = [
                    'quantity' => 1,
                    'type' => $request->input('type', 'hard_copy'),
                    'price' => $request->input('price', 0),
                ];
            }
            session()->put('cart', $cart);
            $cart_count = collect($cart)->sum('quantity');
        }
        session(['cart_count' => $cart_count]);

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['cart_count' => $cart_count]);
        }
        return redirect()->route('cart.index')->with('success', 'Book added to cart!');
    }

    public function update(Request $request, $bookId)
    {
        $quantity = max(1, (int)$request->input('quantity', 1));
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            $item = $cart->items()->where('book_id', $bookId)->first();
            if ($item) {
                $item->quantity = $quantity;
                $item->save();
            }
            $cart_count = $cart->items()->sum('quantity');
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$bookId])) {
                $cart[$bookId]['quantity'] = $quantity;
            }
            session()->put('cart', $cart);
            $cart_count = collect($cart)->sum('quantity');
        }
        session(['cart_count' => $cart_count]);

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['cart_count' => $cart_count]);
        }
        return redirect()->route('cart.index');
    }

    public function remove(Request $request, $bookId)
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            $cart->items()->where('book_id', $bookId)->delete();
            $cart_count = $cart->items()->sum('quantity');
        } else {
            $cart = session()->get('cart', []);
            unset($cart[$bookId]);
            session()->put('cart', $cart);
            $cart_count = collect($cart)->sum('quantity');
        }
        session(['cart_count' => $cart_count]);

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['cart_count' => $cart_count]);
        }
        return redirect()->route('cart.index')->with('success', 'Book removed from cart!');
    }

    public function clear(Request $request)
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            $cart->items()->delete();
        } else {
            session()->forget('cart');
        }
        session(['cart_count' => 0]);

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['cart_count' => 0]);
        }
        return redirect()->route('cart.index')->with('success', 'Cart cleared!');
    }
}
