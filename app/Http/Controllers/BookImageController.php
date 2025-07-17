<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class BookImageController extends Controller
{
    public function show($bookId, $page)
    {
        // $user = Auth::user();
        // Add your access logic here

        $imgPath = storage_path("app/books/{$bookId}/{$page}.jpg");
        if (! file_exists($imgPath)) {
            abort(404);
        }

        return response()->file($imgPath);
    }
}
