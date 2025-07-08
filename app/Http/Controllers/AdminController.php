<?php


namespace App\Http\Controllers;

use App\Models\Book;

class AdminController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return view('index', compact('books'));
    }
}
