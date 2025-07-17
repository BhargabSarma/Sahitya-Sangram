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

class ProcessBookPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $bookId;
    public $timeout = 600; // 10 minutes

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
            $book->image_processing_status = 'failed';
            $book->image_processing_error = 'PDF file not found';
            $book->save();
            return;
        }

        $book->image_processing_status = 'processing';
        $book->image_processing_error = null;
        $book->save();

        try {
            app(BookImageService::class)->convertPdfToImages($pdfPath, $outputDir, function ($i, $totalPages) use ($book) {
                $book->progress = intval((($i + 1) / $totalPages) * 100);
                $book->save();
            });
            $book->is_ready = true;
            $book->image_processing_status = 'completed';
            $book->image_processing_error = null;
            $book->save();
        } catch (Exception $e) {
            Log::error("PDF conversion failed: " . $e->getMessage());
            $book->image_processing_status = 'failed';
            $book->image_processing_error = $e->getMessage();
            $book->save();
        }
    }
}
