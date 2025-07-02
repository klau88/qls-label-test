<?php

namespace App\Http\Controllers;

use App\Services\Api;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\PdfToImage\Pdf as PdfToImage;

class ApiController extends Controller
{
    private function api()
    {
        return app()->make(Api::class);
    }

    public function authenticate()
    {
        return $this->api()->authenticate();
    }

    public function products()
    {
        return $this->api()->getProducts();
    }

    public function getLabel(Order $order)
    {
        return $this->api()->getLabel($order);
    }

    public function generatePdf()
    {
        $id = request()->input('order');
        $order = Order::with('orderLines')->find($id);

        $pdfLocation = Storage::disk('public')->path("label_{$id}.pdf");

        if (!file_exists($pdfLocation)) {
            $shipment = $this->api()->mapOrderToShipment($order);
            $response = $this->api()->getLabel($shipment);
            $data = $response['data'];
            $getLabelPdf = $this->api()->getLabelPdf($data['label_pdf_url']);
            $pdf = base64_decode($getLabelPdf['data']);

            $file = response()->make($pdf, 200, [
                'Content-Type' => 'application/pdf'
            ]);

            Storage::disk('public')->put("label_{$id}.pdf", $file);
        }

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
