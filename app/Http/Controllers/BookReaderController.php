<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookReaderController extends Controller
{
    /**
     * Show the full book reader.
     */
    public function showReader($id)
    {
        $book = Book::findOrFail($id);

        // Fetch all converted page images for this book
        $imageFiles = glob(storage_path("app/books/{$id}/*.jpg"));
        sort($imageFiles);
        $pageCount = count($imageFiles);

        return view('read', [
            'bookId'    => $id,
            'pageCount' => $pageCount,
            'book'      => $book,
        ]);
    }

    /**
     * Show the sample reader (random 6 pages).
     */
    public function showSample($id)
    {
        $book = Book::findOrFail($id);

        // Get all page image paths, sorted by filename for correct order
        $imageFiles = glob(storage_path("app/books/{$id}/*.jpg"));
        sort($imageFiles);
        $totalPages = count($imageFiles);

        // Pick 6 random unique indexes
        if ($totalPages > 6) {
            $indexes = array_rand(array_flip(range(0, $totalPages - 1)), 6);
        } else {
            $indexes = range(0, $totalPages - 1);
        }
        if (!is_array($indexes)) $indexes = [$indexes];

        // Build samplePages array: each element has 'number' and 'image' (public path)
        $samplePages = [];
        foreach ($indexes as $i) {
            $filename = basename($imageFiles[$i]);
            $samplePages[] = [
                'number' => $i + 1,
                'image'  => asset("storage/app/books/{$id}/{$filename}"),
            ];
        }

        return view('read-sample', [
            'book'        => $book,
            'samplePages' => $samplePages,
        ]);
    }
}