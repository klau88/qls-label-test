<?php

namespace App\Http\Controllers;

use App\Classes\Api;
use App\Models\Shipment;
use App\Models\ShipmentProduct;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
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

    public function shipments()
    {
        $order = app()->make('testOrder');

        $shipments = Shipment::all();

        return Inertia::render('Shipments', [
            'order' => $order,
            'shipments' => $shipments,
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

        $data = $label['data'];

        $shipment = Shipment::firstOrCreate([
            'shipment_id' => $data['id'],
            'company_id' => $data['company_id'],
            'brand_id' => $data['brand_id'],
            'product_id' => $data['product_id'],
            'product_combination_id' => $data['product_combination_id'],
            'barcode' => $data['barcode'],
            'tracking_url' => $data['tracking_url'],
            'status' => $data['status'],
            'type' => $data['type'],
            'weight' => $data['weight'],
            'reference' => $data['reference'],
            'cod_amount' => $data['cod_amount'],
            'customs_shipment_type' => $data['customs_shipment_type'],
            'customs_invoice_number' => $data['customs_invoice_number'],
            'label_pdf_url' => $data['label_pdf_url'],
            'label_zpl_url' => $data['label_zpl_url'],
            'sender_company_name' => $data['sender_contact']['companyname'],
            'sender_name' => $data['sender_contact']['name'],
            'sender_street' => $data['sender_contact']['street'],
            'sender_housenumber' => $data['sender_contact']['housenumber'],
            'sender_address2' => $data['sender_contact']['address2'],
            'sender_postalcode' => $data['sender_contact']['postalcode'],
            'sender_city' => $data['sender_contact']['locality'],
            'sender_country' => $data['sender_contact']['country'],
            'sender_phone' => $data['sender_contact']['phone'],
            'sender_email' => $data['sender_contact']['email'],
            'receiver_company_name' => $data['receiver_contact']['companyname'],
            'receiver_name' => $data['receiver_contact']['name'],
            'receiver_street' => $data['receiver_contact']['street'],
            'receiver_housenumber' => $data['receiver_contact']['housenumber'],
            'receiver_address2' => $data['receiver_contact']['address2'],
            'receiver_postalcode' => $data['receiver_contact']['postalcode'],
            'receiver_city' => $data['receiver_contact']['locality'],
            'receiver_country' => $data['receiver_contact']['country'],
            'receiver_phone' => $data['receiver_contact']['phone'],
            'receiver_email' => $data['receiver_contact']['email'],
            'shipment_short' => $data['product']['short'],
            'shipment_name' => $data['product']['name'],
            'shipment_type' => $data['product']['type'],
            'tracking_id' => $data['tracking_id']
        ]);

        foreach ($data['shipment_products']['data'] as $product) {
            ShipmentProduct::firstOrCreate([
                'shipment_id' => $shipment['id'],
                'shipment_product_id' => $product['id'],
                'amount' => $product['amount'],
                'name' => $product['name'],
                'country_code_of_origin' => $product['country_code_of_origin'],
                'hs_code' => $product['hs_code'],
                'ean' => $product['ean'],
                'sku' => $product['sku'],
                'price_per_unit' => $product['price_per_unit'],
                'weight_per_unit' => $product['weight_per_unit'],
                'currency' => $product['currency'],
            ]);
        }

        $getLabelPdf = $this->api()->getLabelPdf($shipment['label_pdf_url']);

        $pdf = base64_decode($getLabelPdf['data']);

        $file = response()->make($pdf, 200, [
            'Content-Type' => 'application/pdf'
        ]);

        return Storage::disk('public')->put("label_{$shipment['id']}.pdf", $file);
    }

    public function retrieveLabel(int $id)
    {
        return Storage::disk('public')->get("label_{$id}.pdf");
    }

    public function generated()
    {
        $id = request()->input('shipment');

        $pdf = new PdfToImage(public_path("storage/label_{$id}.pdf"));
        $pdf->save(public_path("storage/label_{$id}.jpg"));

        $shipment = Shipment::find($id);
        $labelImage = asset("storage/label_{$id}.jpg");

        return Pdf::view('pdfTemplate', compact('shipment', 'labelImage'))
            ->format('a4')
            ->save(public_path("storage/packing_slip_{$id}.pdf"));
    }
}
