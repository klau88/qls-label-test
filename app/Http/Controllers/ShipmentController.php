<?php

namespace App\Http\Controllers;

use App\Classes\Api;
use App\Models\Shipment;
use App\Models\ShipmentProduct;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $order = app()->make('testOrder');

        $shipments = Shipment::with('products')->paginate(100)->toArray();

        return Inertia::render('Shipments/Index', [
            'order' => $order,
            'shipments' => $shipments['data'],
            'csrf' => csrf_token()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $order = app()->make('testOrder');

        return Inertia::render('Shipments/Create', compact('order'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $order = $request->input('order');
        $api = app()->make(Api::class);
        $map = $api->mapOrderToShipment($order);

        $shipment = Shipment::firstOrCreate([
            'product_combination_id' => $map['product_combination_id'],
            'company_id' => $map['company_id'],
            'brand_id' => $map['brand_id'],
            'weight' => $map['weight'],
            'reference' => $map['reference'],
            'sender_company_name' => $map['sender_contact']['companyname'],
            'sender_name' => $map['sender_contact']['name'],
            'sender_street' => $map['sender_contact']['street'],
            'sender_housenumber' => $map['sender_contact']['housenumber'],
            'sender_address2' => $map['sender_contact']['address2'],
            'sender_postalcode' => $map['sender_contact']['postalcode'],
            'sender_city' => $map['sender_contact']['locality'],
            'sender_country' => $map['sender_contact']['country'],
            'sender_phone' => $map['sender_contact']['phone'],
            'sender_email' => $map['sender_contact']['email'],
            'receiver_company_name' => $map['receiver_contact']['companyname'],
            'receiver_name' => $map['receiver_contact']['name'],
            'receiver_street' => $map['receiver_contact']['street'],
            'receiver_housenumber' => $map['receiver_contact']['housenumber'],
            'receiver_address2' => $map['receiver_contact']['address2'],
            'receiver_postalcode' => $map['receiver_contact']['postalcode'],
            'receiver_city' => $map['receiver_contact']['locality'],
            'receiver_country' => $map['receiver_contact']['country'],
            'receiver_phone' => $map['receiver_contact']['phone'],
            'receiver_email' => $map['receiver_contact']['email'],
        ]);

        foreach ($map['shipment_products'] as $product) {
            ShipmentProduct::firstOrCreate([
                'shipment_id' => $shipment['id'] ?? null,
                'shipment_product_id' => $product['id'] ?? null,
                'amount' => $product['amount'] ?? 1,
                'name' => $product['name'] ?? null,
                'country_code_of_origin' => $product['country_code_of_origin'] ?? null,
                'hs_code' => $product['hs_code'] ?? null,
                'ean' => $product['ean'] ?? null,
                'sku' => $product['sku'] ?? null,
                'price_per_unit' => $product['price_per_unit'] ?? null,
                'weight_per_unit' => $product['weight_per_unit'] ?? null,
                'currency' => $product['currency'] ?? null,
            ]);
        };
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
