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

        if (!file_exists(public_path("storage/label_{$id}.pdf"))) {
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

        putenv('PATH=' . getenv('PATH') . ':/opt/homebrew/bin:/usr/local/bin');
        $pdfImage = new PdfToImage(public_path("storage/label_{$id}.pdf"));
        $pdfImage->save(public_path("storage/label_{$id}.jpg"));
        $labelImage = asset("storage/label_{$id}.jpg");

        return Pdf::view('pdfTemplate', compact('shipment', 'labelImage'))
            ->format('a4')
            ->save(public_path("storage/packing_slip_{$id}.pdf"));
    }
}
