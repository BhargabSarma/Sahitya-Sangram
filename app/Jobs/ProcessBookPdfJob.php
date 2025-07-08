<?php

namespace App\Jobs;

use App\Models\Book;
use App\Services\BookImageService;
use App\Notifications\BookProcessed;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ProcessBookPdfJob implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use \Illuminate\Foundation\Bus\Dispatchable, \Illuminate\Queue\InteractsWithQueue, \Illuminate\Queue\SerializesModels;

    protected $bookId;

    public function __construct($bookId)
    {
        $this->bookId = $bookId;
    }

    public function handle(): void
    {
        $book = Book::findOrFail($this->bookId);
        $pdfPath = storage_path('app/private/' . $book->book_file);
        $outputDir = storage_path("app/books/{$book->id}");

        Log::info('PDF Path: ' . $pdfPath);
        if (!file_exists($pdfPath)) {
            Log::error("PDF not found: " . $pdfPath);
            return; // Stop processing if file doesn't exist
        }

        try {
            app(BookImageService::class)->convertPdfToImages($pdfPath, $outputDir);
            $book->is_ready = true;
            $book->save();
        } catch (\Exception $e) {
            Log::error("PDF conversion failed: " . $e->getMessage());
        }
    }
}
