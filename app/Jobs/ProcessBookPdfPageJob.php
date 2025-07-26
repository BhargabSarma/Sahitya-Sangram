<?php

namespace App\Jobs;

use App\Models\Book;
use App\Services\BookImageService;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessBookPdfPageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $bookId;
    protected $pageNumber;
    public $timeout = 3600; // 2 minutes, adjust as needed
    public $tries = 3;

    public function __construct($bookId, $pageNumber)
    {
        $this->bookId = $bookId;
        $this->pageNumber = $pageNumber;
    }

    public function handle(): void
    {
        $book = Book::findOrFail($this->bookId);
        $pdfPath = storage_path('app/private/' . $book->book_file);
        $outputDir = storage_path("app/books/{$book->id}");

        if (!file_exists($pdfPath)) {
            Log::error("PDF not found for page job: " . $pdfPath);
            return;
        }

        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0775, true);
        }

        try {
            app(BookImageService::class)->convertPdfPageToImage($pdfPath, $outputDir, $this->pageNumber);
            Log::info("Converted page {$this->pageNumber} for book ID {$this->bookId}");
        } catch (Exception $e) {
            Log::error("Failed to convert page {$this->pageNumber} for book ID {$this->bookId}: " . $e->getMessage());
            // Optionally, you could mark the page as failed in DB or notify the user
        }
    }
}