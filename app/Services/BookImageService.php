<?php

namespace App\Services;

use Spatie\PdfToImage\Pdf;
use Illuminate\Support\Facades\Log;


class BookImageService
{
    public function convertPdfToImages($pdfPath, $outputDir, callable $progressCallback = null)
    {
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0775, true);
        }
        try {
            $pdf = new Pdf($pdfPath);
            $totalPages = $pdf->pageCount();
            for ($i = 1; $i <= $totalPages; $i++) {
                $pdf->selectPage($i)->save($outputDir . DIRECTORY_SEPARATOR . "{$i}.jpg");
                if ($progressCallback) {
                    $progressCallback($i - 1, $totalPages);
                }
            }
        } catch (\Exception $e) {
            Log::error("Failed to convert PDF to images: " . $e->getMessage());
            throw $e; // Rethrow to let the job handle it
        }
    }
}
