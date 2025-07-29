<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\Cart;
use App\Models\CartItem;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MergeSessionCart
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $sessionCart = session()->get('cart', []);
        if (empty($sessionCart)) {
            return;
        }

        $user = $event->user;
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        foreach ($sessionCart as $cartKey => $item) {
            // $cartKey format: bookId-type
            [$bookId, $type] = explode('-', $cartKey);
            $price = $item['price'] ?? 0;

            $cartItem = $cart->items()->where('book_id', $bookId)->where('type', $type)->first();

            if ($cartItem) {
                $cartItem->quantity += $item['quantity'];
                $cartItem->price = $price;
                $cartItem->save();
            } else {
                $cart->items()->create([
                    'book_id' => $bookId,
                    'quantity' => $item['quantity'],
                    'type' => $type,
                    'price' => $price,
                ]);
            }
        }

        // Clear session cart
        session()->forget('cart');
        session(['cart_count' => $cart->items()->sum('quantity')]);
    }
}
