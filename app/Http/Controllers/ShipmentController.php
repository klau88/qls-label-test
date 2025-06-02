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
        $products = app()->make(Api::class)->getProducts();
        $shippingMethods = $products['data'];

        return Inertia::render('Shipments/Create', compact('order', 'shippingMethods'));
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
            'product_combination_id' => $order['product_combination_id'],
            'company_id' => $map['company_id'],
            'brand_id' => $map['brand_id'],
            'weight' => $order['weight'] ?? $map['weight'],
            'reference' => $order['number'],
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
            'receiver_company_name' => $order['delivery_address']['companyname'],
            'receiver_name' => $order['delivery_address']['name'],
            'receiver_street' => $order['delivery_address']['street'],
            'receiver_housenumber' => $order['delivery_address']['housenumber'],
            'receiver_address2' => $order['delivery_address']['address_line_2'],
            'receiver_postalcode' => $order['delivery_address']['zipcode'],
            'receiver_city' => $order['delivery_address']['city'],
            'receiver_country' => $order['delivery_address']['country'],
            'receiver_phone' => $order['delivery_address']['phone'],
            'receiver_email' => $order['delivery_address']['email'],
        ]);

        foreach ($order['order_lines'] as $product) {
            ShipmentProduct::firstOrCreate([
                'shipment_id' => $shipment['id'] ?? null,
                'shipment_product_id' => $product['id'] ?? null,
                'amount' => $product['amount_ordered'] ?? 1,
                'name' => $product['name'] ?? null,
                'country_code_of_origin' => $product['country_code_of_origin'] ?? null,
                'hs_code' => $product['hs_code'] ?? null,
                'ean' => $product['ean'] ?? null,
                'sku' => $product['sku'] ?? null,
                'price_per_unit' => $product['price_per_unit'] ?? null,
                'weight_per_unit' => $product['weight_per_unit'] ?? null,
                'currency' => $product['currency'] ?? 'EUR',
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
