<?php

namespace App\Http\Controllers;

use App\Models\Book;

class HomeController extends Controller
{
    public function index()
    {
        // Only fetch 4 most recent books for the shelf
        $books = Book::orderBy('created_at', 'desc')->take(4)->get();
        return view('index', compact('books'));
    }
}