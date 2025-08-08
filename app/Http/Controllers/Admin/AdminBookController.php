<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Book;


class AdminBookController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return view('admin.books.index', compact('books'));
    }

    public function bulkUpdateDiscount(Request $request)
    {
        $discounts = $request->input('discounts', []);
        foreach ($discounts as $bookId => $types) {
            $book = Book::find($bookId);
            if (!$book) continue;
            if (isset($types['hard_copy'])) {
                $book->hard_copy_discount = $types['hard_copy'];
            }
            if (isset($types['digital_copy'])) {
                $book->digital_discount = $types['digital_copy'];
            }
            $book->save();
        }
        return redirect()->back()->with('success', 'Discounts updated successfully!');
    }
}
