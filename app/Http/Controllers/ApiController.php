<?php

namespace App\Http\Controllers;

use App\Classes\Api;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;

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

    public function label()
    {
        $order = app()->make('testOrder');

        return Inertia::render('Label', [
            'order' => $order,
            'csrf' => csrf_token()
        ]);
    }

    public function getLabel()
    {
        $order = app()->make('testOrder');
        $shipment = $this->api()->mapOrderToShipment($order);

        return $this->api()->getLabel($shipment);
    }

    public function generateLabelPdf()
    {
        $label = $this->getLabel();

        $labelUrl = $label['data']['label_pdf_url'];

        $getLabelPdf = $this->api()->getLabelPdf($labelUrl);

        $pdf = base64_decode($getLabelPdf['data']);

        return response()->make($pdf, 200, [
            'Content-Type' => 'application/pdf'
        ]);
    }
}
