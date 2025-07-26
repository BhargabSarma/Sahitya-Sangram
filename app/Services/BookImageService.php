<?php

namespace App\Services;

use Spatie\PdfToImage\Pdf;
use Illuminate\Support\Facades\Log;

class BookImageService
{
    /**
     * Convert a batch of PDF pages to images.
     *
     * @param string $pdfPath
     * @param string $outputDir
     * @param int $startPage
     * @param int $batchSize
     * @param callable|null $progressCallback
     * @return array
     */
    public function convertPdfToImagesBatch($pdfPath, $outputDir, $startPage = 1, $batchSize = 25, callable $progressCallback = null)
    {
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0775, true);
        }
        try {
            $pdf = new Pdf($pdfPath);
            $totalPages = $pdf->pageCount();
            $endPage = min($startPage + $batchSize - 1, $totalPages);
            for ($i = $startPage; $i <= $endPage; $i++) {
                try {
                    $pdf->selectPage($i)->save($outputDir . DIRECTORY_SEPARATOR . "{$i}.jpg");
                } catch (\Exception $imgEx) {
                    Log::error("Failed to convert page {$i} of PDF '{$pdfPath}': " . $imgEx->getMessage());
                    continue;
                }
                if ($progressCallback) {
                    $progressCallback($i, $totalPages);
                }
            }
            return [
                'totalPages' => $totalPages,
                'lastPage' => $endPage
            ];
        } catch (\Exception $e) {
            Log::error("Failed to convert PDF to images: " . $e->getMessage());
            throw $e;
        }
    }
}