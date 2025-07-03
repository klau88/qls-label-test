<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
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
    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $order = Order::firstOrCreate($validated);

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
    public function update(UpdateOrderRequest $request, Order $order): RedirectResponse
    {
        $validated = $request->validated();

        $order->update($validated);

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
