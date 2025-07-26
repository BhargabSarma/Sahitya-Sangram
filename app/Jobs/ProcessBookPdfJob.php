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
    protected $startPage;
    protected $batchSize;
    public $timeout = 600; // 10 minutes

    /**
     * @param int $bookId
     * @param int $startPage
     * @param int $batchSize
     */
    public function __construct($bookId, $startPage = 1, $batchSize = 25)
    {
        $this->bookId = $bookId;
        $this->startPage = $startPage;
        $this->batchSize = $batchSize;
    }

    public function handle(): void
    {
        $book = Book::findOrFail($this->bookId);
        $pdfPath = storage_path('app/private/' . $book->book_file);
        $outputDir = storage_path("app/books/{$book->id}");

        Log::info("PDF Path: $pdfPath | OutputDir: $outputDir | startPage: {$this->startPage} | batchSize: {$this->batchSize}");
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
            $result = app(BookImageService::class)->convertPdfToImagesBatch(
                $pdfPath,
                $outputDir,
                $this->startPage,
                $this->batchSize,
                function ($page, $totalPages) use ($book) {
                    $book->progress = intval((($page) / $totalPages) * 100);
                    $book->conversion_last_page = $page;
                    $book->save();
                }
            );

            if ($result['lastPage'] < $result['totalPages']) {
                // Dispatch next batch
                ProcessBookPdfJob::dispatch($this->bookId, $result['lastPage'] + 1, $this->batchSize);
            } else {
                $book->is_ready = true;
                $book->image_processing_status = 'completed';
                $book->progress = 100;
                $book->image_processing_error = null;
                $book->save();
            }
        } catch (Exception $e) {
            Log::error("PDF conversion failed: " . $e->getMessage());
            $book->image_processing_status = 'failed';
            $book->image_processing_error = $e->getMessage();
            $book->save();
        }
    }
}