<?php


namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\Request;
use Spatie\PdfToImage\Pdf;
use App\Services\BookImageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcessBookPdfJob;

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
    public function create()
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
            'Education'
        ];
        $authors = Author::all(); // Add this line to fetch authors
        return view('admin.books.create', compact('categories', 'authors'));
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
            'cover_image_front' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image_back' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'book_file' => 'required|file|mimes:pdf,epub|max:10240', // <-- changed
            'language' => 'nullable|string|max:50',
            'level' => 'required|in:Beginner,Intermediate,Advanced',
            'is_bestseller' => 'nullable|boolean',
            'hard_copy_price' => 'required|numeric|min:0',
            'digital_price' => 'required|numeric|min:0',
            'author_id' => 'required|exists:authors,id',
        ]);

        $data = $request->all();
        if ($request->hasFile('cover_image_front')) {
            $data['cover_image_front'] = $request->file('cover_image_front')->store('covers/front', 'public');
        }
        if ($request->hasFile('cover_image_back')) {
            $data['cover_image_back'] = $request->file('cover_image_back')->store('covers/back', 'public');
        }
        $data['is_bestseller'] = $request->has('is_bestseller') ? 1 : 0;

        // Store the book file in books (not private/books)
        $data['book_file'] = $request->file('book_file')->store('books');

        $book = Book::create($data);
        // Dispatch the job
        ProcessBookPdfJob::dispatch($book->id);

        return redirect()->route('admin.books.create')->with('success', 'Book added! PDF is being processed.');
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $book = Book::findOrFail($id);
        return view('book-details', compact('book'));
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
            'cover_image' => 'nullable|image|max:2048',
            'book_file' => 'required|file|mimes:pdf,epub|max:10240',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
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
        $book->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Book deleted.');
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
        return response()->json(['ready' => (bool)$book->is_ready]);
    }
}
