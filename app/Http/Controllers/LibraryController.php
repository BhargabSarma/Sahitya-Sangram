<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;

class LibraryController extends Controller
{
    // Show all books purchased by the logged-in user
    public function index(Request $request)
    {
        $user = $request->user();

        // Get all book IDs from completed orders
        $completedOrderIds = Order::where('user_id', $user->id)
            ->where('status', 'completed')
            ->pluck('id');

        $digitalBookIds = OrderItem::whereIn('order_id', $completedOrderIds)
            ->where('type', 'digital_copy')
            ->pluck('book_id')
            ->unique()
            ->toArray();

        $bookIds = OrderItem::whereIn('order_id', $completedOrderIds)
            ->pluck('book_id')
            ->unique();

        $books = Book::whereIn('id', $bookIds)->get();

        // You could eager load reviews to show if user reviewed that book
        $reviews = Review::where('user_id', $user->id)
            ->whereIn('book_id', $bookIds)
            ->get()
            ->keyBy('book_id');

        return view('library.index', compact('books', 'reviews', 'digitalBookIds'));
    }

    // Show review form for a book
    public function reviewForm($bookId)
    {
        $book = Book::findOrFail($bookId);
        return view('library.review', compact('book'));
    }

    // Store submitted review
    public function storeReview(Request $request, $bookId)
    {
        $user = $request->user();
        $book = Book::findOrFail($bookId);

        // Check if the user has purchased this book
        $hasPurchased = OrderItem::where('book_id', $bookId)
            ->whereHas('order', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('status', 'completed');
            })
            ->exists();

        if (!$hasPurchased) {
            return redirect()->back()->with('error', 'You can only review books you have purchased.');
        }

        // Only one review per user per book
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $existing = Review::where('user_id', $user->id)
            ->where('book_id', $bookId)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'You have already reviewed this book.');
        }

        $review = new Review();
        $review->user_id = $user->id;
        $review->book_id = $bookId;
        $review->order_id = OrderItem::where('book_id', $bookId)
            ->whereHas('order', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('status', 'completed');
            })
            ->latest('created_at')->first()->order_id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->save();

        return redirect()->route('library.index')->with('success', 'Review posted!');
    }
}
