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
        $order = app()->make('testOrder');
        $api = app()->make(Api::class);
        $map = $api->mapOrderToShipment($order);

        $shipment = Shipment::firstOrCreate([
            'product_combination_id' => $request['product_combination_id'] ?? $map['product_combination_id'],
            'company_id' => $map['company_id'],
            'brand_id' => $map['brand_id'],
            'weight' => $request['weight'] ?? $map['weight'],
            'reference' => $request['number'] ?? $map['reference'],
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
            'receiver_company_name' => $request['delivery_address']['companyname'] ?? $map['receiver_contact']['companyname'],
            'receiver_name' => $request['delivery_address']['name'] ?? $map['receiver_contact']['name'],
            'receiver_street' => $request['delivery_address']['street'] ?? $map['receiver_contact']['street'],
            'receiver_housenumber' => $request['delivery_address']['housenumber'] ?? $map['receiver_contact']['housenumber'],
            'receiver_address2' => $request['delivery_address']['address_line_2'] ?? $map['receiver_contact']['address2'],
            'receiver_postalcode' => $request['delivery_address']['zipcode'] ?? $map['receiver_contact']['postalcode'],
            'receiver_city' => $request['delivery_address']['city'] ?? $map['receiver_contact']['locality'],
            'receiver_country' => $request['delivery_address']['country'] ?? $map['receiver_contact']['country'],
            'receiver_phone' => $request['delivery_address']['phone'] ?? $map['receiver_contact']['phone'],
            'receiver_email' => $request['delivery_address']['email'] ?? $map['receiver_contact']['email'],
        ]);

        $orderLines = $request['order_lines'] ?? $order['order_lines'];

        foreach ($orderLines as $product) {
            ShipmentProduct::firstOrCreate([
                'shipment_id' => $shipment['id'] ?? null,
                'shipment_product_id' => $product['id'] ?? null,
                'amount' => $product['amount_ordered'] ?? $map['amount'] ?? 1,
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

        return redirect()->route('shipments.show', $shipment['id']);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $shipment = Shipment::with('products')->find($id);
        $products = app()->make(Api::class)->getProducts();

        $shippingMethod = collect($products['data'])->filter(function ($product) use ($shipment) {
            return array_filter($product['combinations'], fn($combination) => $combination['id'] === $shipment['product_combination_id']);
        })->first();

        $shippingOption = collect($shippingMethod['combinations'])->pluck('name')->first();

        return Inertia::render('Shipments/Show', compact('shipment', 'shippingMethod', 'shippingOption'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $shipment = Shipment::with('products')->findOrFail($id);
        $products = app()->make(Api::class)->getProducts();
        $shippingMethods = $products['data'];

        return Inertia::render('Shipments/Edit', compact('shipment', 'shippingMethods'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Shipment::find($id)->update([
            'reference' => $request['reference'],
            'sender_company_name' => $request['sender_company_name'],
            'sender_name' => $request['sender_name'],
            'sender_street' => $request['sender_street'],
            'sender_housenumber' => $request['sender_housenumber'],
            'sender_address2' => $request['sender_address2'],
            'sender_postalcode' => $request['sender_postalcode'],
            'sender_city' => $request['sender_city'],
            'sender_country' => $request['sender_country'],
            'sender_phone' => $request['sender_phone'],
            'sender_email' => $request['sender_email'],
            'sender_vat' => $request['sender_vat'],
            'sender_eori' => $request['sender_eori'],
            'receiver_company_name' => $request['receiver_company_name'],
            'receiver_name' => $request['receiver_name'],
            'receiver_street' => $request['receiver_street'],
            'receiver_housenumber' => $request['receiver_housenumber'],
            'receiver_address2' => $request['receiver_address2'],
            'receiver_postalcode' => $request['receiver_postalcode'],
            'receiver_city' => $request['receiver_city'],
            'receiver_country' => $request['receiver_country'],
            'receiver_phone' => $request['receiver_phone'],
            'receiver_email' => $request['receiver_email'],
            'receiver_vat' => $request['receiver_vat'],
            'receiver_eori' => $request['receiver_eori'],
        ]);

        foreach($request['products'] as $product) {
            ShipmentProduct::find($product['id'])->update([
                'amount' => $product['amount'],
                'name' => $product['name'],
                'hs_code' => $product['hs_code'],
                'ean' => $product['ean'],
                'sku' => $product['sku'],
            ]);
        }

        return redirect()->route('shipments.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        Shipment::destroy($id);
        return redirect()->route('shipments.index');
    }
}
