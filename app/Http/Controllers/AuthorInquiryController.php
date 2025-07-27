<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuthorInquiry;
use Illuminate\Support\Facades\Storage;

class AuthorInquiryController extends Controller
{
    // Show the inquiry form
    public function showForm()
    {
        return view('publish'); // This is your publish.blade.php
    }

    // Handle the form submission
    public function submit(Request $request)
    {
        $request->validate([
            // Book Details
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'required|string|max:2000',
            'category' => 'required|string|max:100',
            'category_other' => 'nullable|string|max:100',
            'pdf' => 'required|file|mimes:pdf|max:20480', // 20MB
            // Author Details
            'author' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'bio' => 'nullable|string|max:1000',
        ]);

        $pdfPath = $request->file('pdf')->store('author_books', 'public');

        // If category is "Others", use category_other
        $category = $request->category === 'Others' && $request->filled('category_other')
            ? $request->category_other
            : $request->category;

        AuthorInquiry::create([
            'author_name' => $request->author,
            'author_email' => $request->email,
            'author_phone' => $request->phone,
            'author_bio' => $request->bio,
            'book_title' => $request->title,
            'book_subtitle' => $request->subtitle,
            'book_description' => $request->description,
            'book_category' => $category,
            'book_pdf_path' => $pdfPath,
            'status' => 'pending',
        ]);

        return redirect()->route('author.inquiry.form')->with('success', 'Your book inquiry has been submitted!');
    }
}
