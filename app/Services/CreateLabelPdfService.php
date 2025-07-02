<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CreateLabelPdfService
{
    /**
     * @param int $orderId
     * @return string
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function generatePdf(int $orderId): string
    {
        $order = Order::with('orderLines')->find($orderId)->toArray();

        $pdfLocation = Storage::disk('public')->path("label_{$orderId}.pdf");

        $api = app()->make(Api::class);

        if (!file_exists($pdfLocation)) {
            $shipment = $api->mapOrderToShipment($order);
            $response = $api->getLabel($shipment);

            $data = $response['data'];
            $getLabelPdf = $api->getLabelPdf($data['label_pdf_url']);
            $pdf = base64_decode($getLabelPdf['data']);

            Storage::disk('public')->put("label_{$orderId}.pdf", $pdf);
        }

        return $pdfLocation;
    }
}
