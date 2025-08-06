<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Tag;
use App\Models\Book;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function manage()
    {
        $tags = Tag::with('books')->get();
        $books = Book::all();
        return view('admin.tags.manage', compact('tags', 'books'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:tags,name']);
        Tag::create(['name' => $request->name]);
        return back()->with('success', 'Tag added!');
    }

    public function assignBooks(Request $request, $tagId)
    {
        $tag = Tag::findOrFail($tagId);
        $bookIds = $request->input('books', []);
        if (count($bookIds) > 12) {
            return back()->with('error', 'You can assign a maximum of 12 books to a tag.');
        }
        $tag->books()->sync($bookIds);
        return back()->with('success', 'Books updated for tag.');
    }

    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->books()->detach();
        $tag->delete();
        return back()->with('success', 'Tag deleted.');
    }
}
