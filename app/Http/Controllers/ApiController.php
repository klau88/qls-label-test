<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CreateLabelPdfService;
use App\Services\PdfToImageService;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelPdf\Facades\Pdf;

class ApiController extends Controller
{
    public function generatePdf(CreateLabelPdfService $createLabelPdfService, PdfToImageService $pdfToImageService)
    {
        $id = request()->input('order');
        $order = Order::with('orderLines')->find($id)->toArray();
        $pdfLocation = $createLabelPdfService->generatePdf($id);
        $labelImage = $pdfToImageService->getImage($pdfLocation, $id);

        return Pdf::view('pdfTemplate', compact('order', 'labelImage'))
            ->format('a4')
            ->save(Storage::disk('public')->path("packing_slip_{$id}.pdf"));
    }
}
