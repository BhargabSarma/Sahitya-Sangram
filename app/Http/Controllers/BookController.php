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
            'Education'
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
            $data['cover_image_front'] = 'images/front/' . $filename;
        }
        if ($request->hasFile('cover_image_back')) {
            $filename = $request->file('cover_image_back')->getClientOriginalName();
            $request->file('cover_image_back')->move(base_path('/storage/images/back'), $filename);
            $data['cover_image_back'] = 'images/back/' . $filename;
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
