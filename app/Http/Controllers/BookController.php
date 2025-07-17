<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessBookPdfJob;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Pdf;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {
        $books = Book::latest()->get();

        return view('admin.dashboard', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    // Show add book form
    public function create(Request $request)
    {
        $categories = [
            'Fiction',
            'Non-fiction',
            'Science',
            'Technology',
            'Biography',
            'Children',
            'Comics',
            'Business',
            'History',
            'Education',
        ];
        $authors = Author::all(); // Add this line to fetch authors
        $book = null;
        if ($request->has('book')) {
            $book = Book::find($request->input('book'));
        }

        return view('admin.books.create', compact('categories', 'authors', 'book'));
    }

    // Store new book in DB
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'number_of_pages' => 'nullable|integer|min:1',
            'cover_image_front' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'cover_image_back' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'book_file' => 'required|file|mimes:pdf,epub|max:51200',
            'language' => 'nullable|string|max:50',
            'level' => 'required|in:Beginner,Intermediate,Advanced',
            'is_bestseller' => 'nullable|boolean',
            'hard_copy_price' => 'required|numeric|min:0',
            'digital_price' => 'required|numeric|min:0',
            'author_id' => 'required|exists:authors,id',
        ]);

        $data = $request->all();
        if ($request->hasFile('cover_image_front')) {
            $filename = $request->file('cover_image_front')->getClientOriginalName();
            $request->file('cover_image_front')->move(base_path('/storage/images/front'), $filename);
            $data['cover_image_front'] = 'images/front/'.$filename;
        }
        if ($request->hasFile('cover_image_back')) {
            $filename = $request->file('cover_image_back')->getClientOriginalName();
            $request->file('cover_image_back')->move(base_path('/storage/images/back'), $filename);
            $data['cover_image_back'] = 'images/back/'.$filename;
        }
        $data['is_bestseller'] = $request->has('is_bestseller') ? 1 : 0;

        // Store the book file in books (not private/books)
        $data['book_file'] = $request->file('book_file')->store('books');

        $book = Book::create($data);
        // Dispatch the job
        ProcessBookPdfJob::dispatch($book->id);

        return redirect()->route('admin.books.create', ['book' => $book->id])
            ->with('success', 'Book added! PDF is being processed.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $book = Book::findOrFail($id);

        // Get all page image paths, sorted by filename for correct order
        $imageFiles = glob(storage_path("app/books/{$id}/*.jpg"));
        sort($imageFiles);
        $totalPages = count($imageFiles);

        // Pick 6 random unique indexes (or all if less than 6)
        $indexes = $totalPages > 6
            ? array_rand(array_flip(range(0, $totalPages - 1)), 6)
            : range(0, $totalPages - 1);
        if (! is_array($indexes)) {
            $indexes = [$indexes];
        }

        // Build samplePages array
        $samplePages = [];
        foreach ($indexes as $i) {
            $filename = basename($imageFiles[$i]);
            $samplePages[] = [
                'number' => $i + 1,
                'image' => asset("storage/app/books/{$id}/{$filename}"),
            ];
        }

        return view('book-details', [
            'book' => $book,
            'samplePages' => $samplePages,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        return view('admin.books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required',
            'author' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric',

            'access_type' => 'required|in:purchase,subscription',
            'cover_image' => 'nullable|image|max:10240',
            'book_file' => 'required|file|mimes:pdf,epub|max:51200',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers');
        }

        if ($request->hasFile('book_file')) {
            $validated['book_file'] = $request->file('book_file')->store('books');
        }

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Book updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $errors = [];

        try {
            // Delete front cover image if exists
            if ($book->cover_image_front) {
                $frontImagePath = base_path("storage/{$book->cover_image_front}");
                if (file_exists($frontImagePath)) {
                    if (! unlink($frontImagePath)) {
                        $errors[] = 'Failed to delete front cover image.';
                        \Log::error("Failed to delete front cover image: {$frontImagePath}");
                    }
                }
            }

            // Delete back cover image if exists
            if ($book->cover_image_back) {
                $backImagePath = base_path("storage/{$book->cover_image_back}");
                if (file_exists($backImagePath)) {
                    if (! unlink($backImagePath)) {
                        $errors[] = 'Failed to delete back cover image.';
                        \Log::error("Failed to delete back cover image: {$backImagePath}");
                    }
                }
            }

            // Delete book file if exists
            if ($book->book_file) {
                $bookFilePath = storage_path("app/{$book->book_file}");
                if (file_exists($bookFilePath)) {
                    if (! unlink($bookFilePath)) {
                        $errors[] = 'Failed to delete book file.';
                        \Log::error("Failed to delete book file: {$bookFilePath}");
                    }
                }
            }

            // Delete book images directory (PDF page images)
            $bookImagesDir = storage_path("app/books/{$book->id}");
            if (is_dir($bookImagesDir)) {
                if (! $this->deleteDirectory($bookImagesDir)) {
                    $errors[] = 'Failed to delete book images directory.';
                    \Log::error("Failed to delete book images directory: {$bookImagesDir}");
                }
            }

            // Delete the book record (this will also trigger cascade deletion of reviews)
            $book->delete();

            // Return appropriate response based on whether there were file deletion errors
            if (empty($errors)) {
                return redirect()->route('admin.dashboard')->with('success', 'Book and all associated files deleted successfully.');
            } else {
                $errorMessage = 'Book deleted but some files could not be removed: '.implode(' ', $errors);

                return redirect()->route('admin.dashboard')->with('warning', $errorMessage);
            }

        } catch (\Exception $e) {
            \Log::error("Error deleting book {$book->id}: ".$e->getMessage());

            return redirect()->route('admin.dashboard')->with('error', 'An error occurred while deleting the book. Please try again.');
        }
    }

    /**
     * Recursively delete a directory and all its contents
     */
    private function deleteDirectory($dir)
    {
        if (! is_dir($dir)) {
            return true;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir.DIRECTORY_SEPARATOR.$file;
            if (is_dir($path)) {
                if (! $this->deleteDirectory($path)) {
                    return false;
                }
            } else {
                if (! unlink($path)) {
                    return false;
                }
            }
        }

        return rmdir($dir);
    }

    /**
     * Display the user's bookshelf.
     */
    public function bookshelf()
    {
        $books = Book::all(); // Or filter by user if needed

        return view('bookshelf', compact('books'));
    }

    public function checkReady($id)
    {
        $book = Book::findOrFail($id);

        return response()->json(['ready' => (bool) $book->is_ready]);
    }
}
