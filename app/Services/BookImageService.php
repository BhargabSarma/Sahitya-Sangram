<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Spatie\PdfToImage\Pdf;

class BookImageService
{
    public function convertPdfToImages($pdfPath, $outputDir)
    {
        if (! file_exists($outputDir)) {
            mkdir($outputDir, 0775, true);
        }
        try {
            $pdf = new Pdf($pdfPath);
            $pdf->saveAllPages($outputDir);
        } catch (\Exception $e) {
            Log::error('Failed to convert PDF to images: '.$e->getMessage());
            throw $e; // Rethrow to let the job handle it
        }
    }
}
