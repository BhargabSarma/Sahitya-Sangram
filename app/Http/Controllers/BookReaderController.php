<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\PdfToImage\Pdf;
use App\Models\Book;
use Spatie\PdfToImage\Enums\OutputFormat;
use Illuminate\Support\Facades\Auth;

class BookReaderController extends Controller
{
    public function showReader($id)
    {
        $book = Book::findOrFail($id);
        $pageCount = count(glob(storage_path("app/books/{$id}/*.jpg")));
        return view('read', [
            'bookId' => $id,
            'pageCount' => $pageCount,
            'book' => $book,
        ]);
    }

    // public function getPage($id, $page)
    // {
    //     $imagePath = storage_path("app/books/{$id}/page_{$page}.jpg");
    //     if (!file_exists($imagePath)) {
    //         abort(404);
    //     }
    //     return response()->file($imagePath);
    // }
}
