<?php

namespace App\Http\Controllers\Admin;

use App\Models\AuthorInquiry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminAuthorInquiryController extends Controller
{
    // List all inquiries
    public function index()
    {
        $inquiries = AuthorInquiry::latest()->get();
        return view('admin.author_inquiries.index', compact('inquiries'));
    }

    // Show details of an inquiry
    public function show($id)
    {
        $inquiry = AuthorInquiry::findOrFail($id);
        return view('admin.author_inquiries.show', compact('inquiry'));
    }

    // Approve an inquiry
    public function approve($id)
    {
        $inquiry = AuthorInquiry::findOrFail($id);
        $inquiry->status = 'approved';
        $inquiry->save();
        // Optionally: notify author
        return back()->with('success', 'Inquiry approved!');
    }

    // Deny an inquiry
    public function deny($id)
    {
        $inquiry = AuthorInquiry::findOrFail($id);
        $inquiry->status = 'denied';
        $inquiry->save();
        // Optionally: notify author
        return back()->with('success', 'Inquiry denied.');
    }

    // Delete an inquiry
    public function destroy($id)
    {
        $inquiry = AuthorInquiry::findOrFail($id);
        $inquiry->delete();
        return back()->with('success', 'Inquiry deleted.');
    }
}
