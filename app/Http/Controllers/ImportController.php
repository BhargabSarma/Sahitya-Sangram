<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Book;
use App\Models\Author;

class ImportController extends Controller
{
    public function create()
    {
        $categories = ['Fiction', 'Non-Fiction', 'Science', 'Math', 'History']; // Replace with your actual categories logic
        $authors = Author::all(); // Replace with your actual logic to get authors

        // Get list of PDFs from storage/app/ftp_uploads
        $ftpPath = storage_path('app/ftp_uploads');
        $ftp_files = [];
        if (File::exists($ftpPath)) {
            $ftp_files = collect(File::files($ftpPath))
                ->filter(function ($file) {
                    return strtolower($file->getExtension()) === 'pdf';
                })
                ->map(function ($file) {
                    return $file->getFilename();
                })
                ->toArray();
        }

        return view('admin.books.create', compact('categories', 'authors', 'ftp_files'));
    }

    public function store(Request $request)
    {
        // If "imported_pdf_file" is set, use that as the book file
        if ($request->has('imported_pdf_file')) {
            $pdfFile = $request->input('imported_pdf_file');
            $sourcePath = storage_path('app/ftp_uploads/' . $pdfFile);

            if (!File::exists($sourcePath)) {
                return back()->withErrors(['book_file' => 'Selected PDF not found in FTP uploads.'])->withInput();
            }

            // Move or copy the file to your books directory
            $booksPath = storage_path('app/books');
            if (!File::exists($booksPath)) {
                File::makeDirectory($booksPath, 0755, true);
            }
            $destinationPath = $booksPath . '/' . $pdfFile;
            File::move($sourcePath, $destinationPath);

            $bookFilePath = 'books/' . $pdfFile;
        } else {
            // Default file upload logic
            $request->validate([
                'book_file' => 'required|file|mimes:pdf,epub',
            ]);
            $bookFile = $request->file('book_file');
            $bookFilePath = $bookFile->store('books');
        }

        // Validate other fields
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'number_of_pages' => 'nullable|integer',
            'cover_image_front' => 'nullable|file|image',
            'cover_image_back' => 'nullable|file|image',
            'language' => 'nullable|string|max:255',
            'level' => 'required|string',
            'is_bestseller' => 'nullable|boolean',
            'hard_copy_price' => 'required|numeric',
            'digital_price' => 'required|numeric',
            'author_id' => 'required|exists:authors,id',
        ]);

        // Handle cover images upload
        $coverFrontPath = $request->file('cover_image_front') ? $request->file('cover_image_front')->store('covers') : null;
        $coverBackPath = $request->file('cover_image_back') ? $request->file('cover_image_back')->store('covers') : null;

        // Save the book record
        Book::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'category' => $request->category,
            'number_of_pages' => $request->number_of_pages,
            'cover_image_front' => $coverFrontPath,
            'cover_image_back' => $coverBackPath,
            'language' => $request->language,
            'level' => $request->level,
            'is_bestseller' => $request->has('is_bestseller'),
            'hard_copy_price' => $request->hard_copy_price,
            'digital_price' => $request->digital_price,
            'author_id' => $request->author_id,
            'pdf_path' => $bookFilePath,
        ]);

        return redirect()->route('admin.books.create')->with('success', 'Book added successfully!');
    }
}