<?php

namespace App\Http\Controllers;

use App\Services\Api;
use App\Models\Order;
use App\Models\OrderLine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $orders = Order::with('orderLines')->paginate(100)->toArray();

        return Inertia::render('Orders/Index', [
            'orders' => $orders['data'],
            'csrf' => csrf_token()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $products = app()->make(Api::class)->getProducts();
        $shippingMethods = $products['data'];

        return Inertia::render('Orders/Create', compact('shippingMethods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $order = Order::firstOrCreate([
            'product_combination_id' => $request['productCombinationId'],
            'company_id' => config('api.company_id'),
            'brand_id' => config('api.brand_id'),
            'number' => $request['reference'],
            'billing_companyname' => $request['senderCompanyName'],
            'billing_name' => $request['senderName'],
            'billing_street' => $request['senderStreet'],
            'billing_housenumber' => $request['senderNumber'],
            'billing_address_line_2' => $request['senderAddress2'] ?? null,
            'billing_zipcode' => $request['senderPostal'],
            'billing_city' => $request['senderCity'],
            'billing_country' => $request['senderCountry'],
            'billing_phone' => $request['senderPhone'],
            'billing_email' => $request['senderEmail'],
            'delivery_companyname' => $request['receiverCompanyName'],
            'delivery_name' => $request['receiverName'],
            'delivery_street' => $request['receiverStreet'],
            'delivery_housenumber' => $request['receiverNumber'],
            'delivery_address_line_2' => $request['receiverAddress2'] ?? null,
            'delivery_zipcode' => $request['receiverPostal'],
            'delivery_city' => $request['receiverCity'],
            'delivery_country' => $request['receiverCountry'],
            'delivery_phone' => $request['receiverPhone'],
            'delivery_email' => $request['receiverEmail'],
        ]);

        foreach ($request['orderLines'] as $line) {
            OrderLine::firstOrCreate([
                'order_id' => $order['id'] ?? null,
                'amount_ordered' => $line['amount'] ?? 1,
                'name' => $line['name'] ?? null,
                'country_code_of_origin' => $line['country_code_of_origin'] ?? null,
                'hs_code' => $line['hs_code'] ?? null,
                'ean' => $line['ean'] ?? null,
                'sku' => $line['sku'] ?? null,
                'price_per_unit' => $line['price_per_unit'] ?? null,
                'weight_per_unit' => $line['weight_per_unit'] ?? null,
                'currency' => $line['currency'] ?? 'EUR',
            ]);
        };

        return redirect()->route('orders.show', $order['id']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): Response
    {
        $order = $order->with('orderLines')->first();

        $products = app()->make(Api::class)->getProducts();

        $shippingMethod = collect($products['data'])->map(function ($product) use ($order) {
            return [
                'name' => $product['name'],
                'option' => collect($product['combinations'])->filter(fn($combination) => $combination['id'] === $order['product_combination_id'])->first()
            ];
        })->filter(fn($method) => $method['option'] !== null)->first();

        return Inertia::render('Orders/Show', compact('order', 'shippingMethod'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order): Response
    {
        $order = $order->with('orderLines')->first();
        $products = app()->make(Api::class)->getProducts();
        $shippingMethods = $products['data'];

        return Inertia::render('Orders/Edit', compact('order', 'shippingMethods'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        $order->update([
            'reference' => $request['reference'],
            'billing_companyname' => $request['billing_companyname'],
            'billing_name' => $request['billing_name'],
            'billing_street' => $request['billing_street'],
            'billing_housenumber' => $request['billing_housenumber'],
            'billing_address_line_2' => $request['billing_address_line_2'] ?? null,
            'billing_zipcode' => $request['billing_zipcode'],
            'billing_city' => $request['billing_city'],
            'billing_country' => $request['billing_country'],
            'billing_phone' => $request['billing_phone'],
            'billing_email' => $request['billing_email'],
            'delivery_companyname' => $request['delivery_companyname'],
            'delivery_name' => $request['delivery_name'],
            'delivery_street' => $request['delivery_street'],
            'delivery_housenumber' => $request['delivery_housenumber'],
            'delivery_address_line_2' => $request['delivery_address_line_2'] ?? null,
            'delivery_zipcode' => $request['delivery_zipcode'],
            'delivery_city' => $request['delivery_city'],
            'delivery_country' => $request['delivery_country'],
            'delivery_phone' => $request['delivery_phone'],
            'delivery_email' => $request['delivery_email'],
        ]);

        $currentOrderLineIds = $order->orderLines()->pluck('id')->toArray();
        $newOrderLineIds = collect($request['order_lines'])->pluck('id')->filter()->toArray();

        $deleted = array_diff($currentOrderLineIds, $newOrderLineIds);
        if (!empty($deleted)) {
            $order->orderLines()->whereIn('id', $deleted)->delete();
        }

        foreach ($request['order_lines'] as $line) {
            if (!empty($line['id'])) {
                OrderLine::find($line['id'])->update([
                    'amount_ordered' => $line['amount_ordered'],
                    'name' => $line['name'],
                    'hs_code' => $line['hs_code'] ?? null,
                    'ean' => $line['ean'] ?? null,
                    'sku' => $line['sku'] ?? null,
                ]);
            } else {
                OrderLine::create([
                    'order_id' => $request['id'] ?? null,
                    'amount_ordered' => $line['amount_ordered'],
                    'name' => $line['name'],
                    'hs_code' => $line['hs_code'] ?? null,
                    'ean' => $line['ean'] ?? null,
                    'sku' => $line['sku'] ?? null,
                ]);
            }
        }

        return redirect()->route('orders.show', $order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order): string
    {
        $order->delete();

        return redirect()->route('orders.index');
    }
}
