<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Exceptions\PdfDoesNotExist;
use Spatie\PdfToImage\Pdf as PdfToImage;

class PdfToImageService
{
    /**
     * @param int $id
     * @return string
     * @throws PdfDoesNotExist
     */
    public function getImage(int $id): string
    {
        $paths = implode(':', config('pdf.binary_paths'));
        putenv('PATH=' . getenv('PATH') . ':' . $paths);

        $pdfLocation = Storage::disk('public')->path("label_{$id}.pdf");
        $pdfImage = new PdfToImage($pdfLocation);
        $labelImage = Storage::disk('public')->path("label_{$id}.jpg");
        $pdfImage->save($labelImage);

        return $labelImage;
    }
}
