<?php


namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Tag;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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
        $authors = Author::all();
        $tags = Tag::withCount('books')->get();
        $book = null;
        if ($request->has('book')) {
            $book = Book::find($request->input('book'));
        }
        return view('admin.books.create', compact('categories', 'authors', 'book', 'tags'));
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
            'book_file' => 'required|file|mimes:pdf,epub|max:2048000',
            'language' => 'nullable|string|max:50',
            'level' => 'required|in:Beginner,Intermediate,Advanced',
            'hard_copy_price' => 'required|numeric|min:0',
            'digital_price' => 'required|numeric|min:0',
            'author_id' => 'required|exists:authors,id',
            'discount' => 'nullable|integer|min:0|max:100',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $data = $request->all();
        $bookName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->input('title')); // Sanitize book name

        if ($request->hasFile('cover_image_front')) {
            $frontFilename = $bookName . '_front.' . $request->file('cover_image_front')->getClientOriginalExtension();
            $request->file('cover_image_front')->move(base_path('storage/images/front'), $frontFilename);
            $data['cover_image_front'] = 'images/front/' . $frontFilename;
        }
        if ($request->hasFile('cover_image_back')) {
            $backFilename = $bookName . '_back.' . $request->file('cover_image_back')->getClientOriginalExtension();
            $request->file('cover_image_back')->move(base_path('storage/images/back'), $backFilename);
            $data['cover_image_back'] = 'images/back/' . $backFilename;
        }


        $data['book_file'] = $request->file('book_file')->store('books');
        $data['discount'] = $request->input('discount', null);


        $book = Book::create($data);

        // Attach tags (max 12 books per tag enforced in blade)
        if ($request->has('tags')) {
            $book->tags()->sync($request->input('tags'));
        }

        // Add 100 stock to inventories table
        Inventory::create([
            'book_id' => $book->id,
            'stock' => 100,
        ]);

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
        if (!is_array($indexes)) $indexes = [$indexes];

        // Build samplePages array
        $samplePages = [];
        foreach ($indexes as $i) {
            $filename = basename($imageFiles[$i]);
            $samplePages[] = [
                'number' => $i + 1,
                'image'  => asset("storage/app/books/{$id}/{$filename}"),
            ];
        }

        return view('book-details', [
            'book' => $book,
            'samplePages' => $samplePages
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
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
        $authors = Author::all();
        $tags = Tag::withCount('books')->get();
        return view('admin.books.edit', compact('book', 'categories', 'authors', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'number_of_pages' => 'nullable|integer|min:1',
            'cover_image_front' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'cover_image_back' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'book_file' => 'nullable|file|mimes:pdf,epub|max:2048000',
            'language' => 'nullable|string|max:50',
            'level' => 'required|in:Beginner,Intermediate,Advanced',
            'hard_copy_price' => 'required|numeric|min:0',
            'digital_price' => 'required|numeric|min:0',
            'author_id' => 'required|exists:authors,id',
            'discount' => 'nullable|integer|min:0|max:100',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $data = $request->all();
        $bookName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->input('title')); // Sanitize book name

        if ($request->hasFile('cover_image_front')) {
            $frontFilename = $bookName . '_front.' . $request->file('cover_image_front')->getClientOriginalExtension();
            $request->file('cover_image_front')->move(base_path('storage/images/front'), $frontFilename);
            $data['cover_image_front'] = 'images/front/' . $frontFilename;
        }
        if ($request->hasFile('cover_image_back')) {
            $backFilename = $bookName . '_back.' . $request->file('cover_image_back')->getClientOriginalExtension();
            $request->file('cover_image_back')->move(base_path('storage/images/back'), $backFilename);
            $data['cover_image_back'] = 'images/back/' . $backFilename;
        }
        if ($request->hasFile('book_file')) {
            $data['book_file'] = $request->file('book_file')->store('books');
        }
        $data['discount'] = $request->input('discount', null);


        $book->update($data);

        // Sync tags
        if ($request->has('tags')) {
            $book->tags()->sync($request->input('tags'));
        } else {
            $book->tags()->sync([]);
        }

        return redirect()->route('admin.books.edit', $book->id)
            ->with('success', 'Book updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $imageDir = storage_path("app/books/{$book->id}");
        if (is_dir($imageDir)) {
            File::deleteDirectory($imageDir);
        }
        $book->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Book deleted.');
    }



    public function checkReady($id)
    {
        $book = Book::findOrFail($id);
        return response()->json([
            'ready' => (bool)$book->is_ready,
            'status' => $book->image_processing_status ?? 'pending',
            'error' => $book->image_processing_error ?? '',
            'progress' => $book->progress ?? 0
        ]);
    }
    // BooksController.php

    //search kora controller + paginate
    public function bookshelf(Request $request)
    {
        $query = $request->input('q');

        // If no query, just show all books paginated
        if (!$query) {
            $books = Book::orderBy('created_at', 'desc')->paginate(12);
            return view('bookshelf', compact('books', 'query'));
        }

        // First, try to match books by title
        $books = Book::where('title', 'like', "%$query%")
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // If no books by title, try to match author name
        if ($books->isEmpty()) {
            $books = Book::whereHas('author', function ($authorQuery) use ($query) {
                $authorQuery->where('name', 'like', "%$query%");
            })
                ->orderBy('created_at', 'desc')
                ->paginate(12);
        }

        return view('bookshelf', compact('books', 'query'));
    }

    // For AJAX search
    public function ajaxSearch(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            $books = Book::orderBy('created_at', 'desc')->limit(15)->get();
            return view('components.bookshelf-results', compact('books'))->render();
        }

        // First, try to match books by title
        $books = Book::where('title', 'like', "%$query%")
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();

        // If no books by title, try to match author name
        if ($books->isEmpty()) {
            $books = Book::whereHas('author', function ($authorQuery) use ($query) {
                $authorQuery->where('name', 'like', "%$query%");
            })
                ->orderBy('created_at', 'desc')
                ->limit(15)
                ->get();
        }

        return view('components.bookshelf-results', compact('books'))->render();
    }
    //new 
    public function startConversion(Book $book)
    {
        if ($book->image_processing_status === 'processing' || $book->is_ready) {
            return back()->with('status', 'Conversion already started or completed.');
        }
        ProcessBookPdfJob::dispatch($book->id, 1, 25); // 25 = batch size
        $book->image_processing_status = 'processing';
        $book->save();

        return back()->with('status', 'Conversion started!');
    }
}
