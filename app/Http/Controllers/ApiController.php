<?php

namespace App\Http\Controllers;

use App\Services\Api;
use App\Models\Order;
use App\Services\CreateLabelPdfService;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\PdfToImage\Pdf as PdfToImage;
use Illuminate\Http\Client\Response;

class ApiController extends Controller
{
    public function generatePdf(CreateLabelPdfService $createLabelPdfService)
    {
        $id = request()->input('order');
        $order = Order::with('orderLines')->find($id)->toArray();

        $pdfLocation = $createLabelPdfService->generatePdf($id);

        $paths = implode(':', config('pdf.binary_paths'));
        putenv('PATH=' . getenv('PATH') . ':' . $paths);

        $pdfImage = new PdfToImage($pdfLocation);

        $imageLocation = Storage::disk('public')->path("label_{$id}.jpg");
        $pdfImage->save($imageLocation);
        $labelImage = $imageLocation;

        return Pdf::view('pdfTemplate', compact('order', 'labelImage'))
            ->format('a4')
            ->save(Storage::disk('public')->path("packing_slip_{$id}.pdf"));
    }
}
