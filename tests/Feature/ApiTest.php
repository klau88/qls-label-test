<?php

use App\Models\Order;
use App\Models\OrderLine;
use App\Services\Api;
use App\Services\CreateLabelPdfService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('can authenticate to the QLS API', function () {
    $apiUrl = config('api.url');

    $fakeResponse = [
        'data' => [
            'id' => '9e606e6b-44a4-4a4e-a309-cc70ddd3a103',
            'tenant_id' => 'b4155e01-996a-11e6-b9d5-00155d7de20b',
            'affiliate_id' => null,
            'name' => 'Test Company Frits',
            'invoice_contact_id' => '42a64d8f-02c6-4fe2-b235-522a2add37d8',
            'active' => true,
            'tenant' => [
                'id' => 'b4155e01-996a-11e6-b9d5-00155d7de20b',
                'name' => 'Pakketdienst QLS B.V.',
                'invoice_contact_id' => null,
                'company_id' => '20b8a68a-c41f-4e4a-b53e-bcc0cdee49ad',
                'main_tenant_id' => null,
                'is_fulfillment' => false,
                'is_pickup' => true,
                'logo_file' => null,
                'financial_division_id' => '539313',
                'legal_companyname' => 'Pakketdienst QLS B.V.',
                'legal_coc' => '56923252',
                'legal_signer' => 'P. Boogers',
                'main_contact_id' => null,
            ],
        ]
    ];


    Http::fake([
        $apiUrl => Http::response($fakeResponse, 200)
    ]);

    $response = $this->app->make(Api::class)->authenticate();

    expect($response->status())->toBe(200);
    expect($response['data']['name'])->toBe('Test Company Frits');
    expect($response['data']['tenant']['name'])->toBe('Pakketdienst QLS B.V.');
    expect($response['data']['tenant']['company_id'])->toBe('20b8a68a-c41f-4e4a-b53e-bcc0cdee49ad');
});

it('can get products from the QLS API', function () {
    $companyId = config('api.company_id');
    $apiUrl = config('api.url') . "companies/{$companyId}/products";

    $fakeResponse = [
        'data' => [
            [
                'id' => 1,
                'category' => 'shipment',
                'name' => 'DHL Brievenbus pakje',
                'short' => 'dhl-mailbox-parcel',
                'image_small' => 'dhllogo_small.svg',
                'type' => 'delivery',
                'servicepoint' => false,
                'product_family' => [
                    'id' => '1a6ae81e-ce57-4daf-8709-8e5f3ca42715',
                    'name' => 'DHL eCommerce',
                    'target_group_tag' => 'family_dhlparcel_ecommerce'
                ],
                'combinations' => [
                    [
                        'id' => 1,
                        'name' => 'DHL Brievenbus pakje',
                        'product_options' => []
                    ]
                ]
            ],
        ]
    ];

    Http::fake([
        $apiUrl => Http::response($fakeResponse, 200)
    ]);

    $response = $this->app->make(Api::class)->getProducts();

    expect($response->status())->toBe(200);
    expect($response['data'][0]['name'])->toBe('DHL Brievenbus pakje');
    expect($response['data'][0]['product_family']['name'])->toBe('DHL eCommerce');
    expect($response['data'][0]['combinations'][0]['name'])->toBe('DHL Brievenbus pakje');
});

it('can save an order model from a test order', function () {
    $testOrder = $this->app->make('testOrder');
    $companyId = config('api.company_id');
    $brandId = config('api.brand_id');

    $newOrder = Order::create([
        'product_combination_id' => 1,
        'company_id' => $companyId,
        'brand_id' => $brandId,
        'number' => $testOrder['number'],
        'billing_name' => $testOrder['billing_address']['name'],
        'billing_companyname' => $testOrder['billing_address']['companyname'],
        'billing_street' => $testOrder['billing_address']['street'],
        'billing_housenumber' => $testOrder['billing_address']['housenumber'],
        'billing_address_line_2' => $testOrder['billing_address']['address_line_2'],
        'billing_zipcode' => $testOrder['billing_address']['zipcode'],
        'billing_city' => $testOrder['billing_address']['city'],
        'billing_country' => $testOrder['billing_address']['country'],
        'billing_email' => $testOrder['billing_address']['email'],
        'billing_phone' => $testOrder['billing_address']['phone'],
        'delivery_name' => $testOrder['delivery_address']['name'],
        'delivery_companyname' => $testOrder['delivery_address']['companyname'],
        'delivery_street' => $testOrder['delivery_address']['street'],
        'delivery_housenumber' => $testOrder['delivery_address']['housenumber'],
        'delivery_address_line_2' => $testOrder['delivery_address']['address_line_2'],
        'delivery_zipcode' => $testOrder['delivery_address']['zipcode'],
        'delivery_city' => $testOrder['delivery_address']['city'],
        'delivery_country' => $testOrder['delivery_address']['country'],
        'delivery_email' => $testOrder['delivery_address']['email'],
        'delivery_phone' => $testOrder['delivery_address']['phone'],
    ]);

    foreach ($testOrder['order_lines'] as $line) {
        OrderLine::create([
            'order_id' => $newOrder['id'],
            'amount_ordered' => $line['amount_ordered'],
            'name' => $line['name'],
            'sku' => $line['sku'] ?? null,
            'ean' => $line['ean'] ?? null,
            'price_per_unit' => $line['price_per_unit'] ?? null,
        ]);
    }

    $order = Order::with('orderLines')->find($newOrder['id']);

    $this->assertDataBaseHas('orders', [
        'number' => $testOrder['number'],
    ]);

    expect($order['number'])->toBe($testOrder['number']);
});

it('can get a shipment response from a test order', function () {
    $companyId = config('api.company_id');
    $brandId = config('api.brand_id');

    $newOrder = Order::factory()->create([
        'product_combination_id' => 1,
        'company_id' => $companyId,
        'brand_id' => $brandId,
    ]);

    $orderLines = OrderLine::factory()->count(2)->create(['order_id' => $newOrder->id]);

    $order = Order::with('orderLines')->find($newOrder['id'])->toArray();

    $shipment = $this->app->make(Api::class)->mapOrderToShipment($order);

    $data = [
        'product_combination_id' => $shipment['product_combination_id'],
        'brand_id' => $brandId,
        'reference' => $shipment['reference'],
        'weight' => $shipment['weight'],
        'sender_contact' => [
            'name' => $shipment['sender_contact']['name'],
            'companyname' => $shipment['sender_contact']['companyname'],
            'street' => $shipment['sender_contact']['street'],
            'housenumber' => $shipment['sender_contact']['housenumber'],
            'address2' => $shipment['sender_contact']['address_line_2'] ?? null,
            'postalcode' => $shipment['sender_contact']['postalcode'],
            'locality' => $shipment['sender_contact']['locality'],
            'country' => $shipment['sender_contact']['country'],
            'email' => $shipment['sender_contact']['email'] ?? null,
            'phone' => $shipment['sender_contact']['phone'] ?? null,
        ],
        'receiver_contact' => [
            'name' => $shipment['receiver_contact']['name'],
            'companyname' => $shipment['receiver_contact']['companyname'],
            'street' => $shipment['receiver_contact']['street'],
            'housenumber' => $shipment['receiver_contact']['housenumber'],
            'address2' => $shipment['receiver_contact']['address_line_2'] ?? null,
            'postalcode' => $shipment['receiver_contact']['postalcode'],
            'locality' => $shipment['receiver_contact']['locality'],
            'country' => $shipment['receiver_contact']['country'],
            'email' => $shipment['receiver_contact']['email'] ?? null,
            'phone' => $shipment['receiver_contact']['phone'] ?? null,
        ],
        'shipment_products' => [],
        'zpl_direct' => true
    ];

    foreach ($shipment['shipment_products'] as $product) {
        array_push($data['shipment_products'], [
            'amount' => $product['amount'],
            'name' => $product['name'],
            'hs_code' => $product['hs_code'] ?? null,
            'ean' => $product['ean'] ?? null,
            'sku' => $product['sku'] ?? null,
            'currency' => $product['currency'] ?? null,
            'weight_per_unit' => $product['weight_per_unit'] ?? null,
            'price_per_unit' => $product['price_per_unit'] ?? null,
            'country_code_of_origin' => $product['country_code_of_origin'] ?? null
        ]);
    }

    $apiUrl = config('api.url') . "/v2/companies/{$companyId}/shipments";

    $fakeResponse = [
        'data' => [
            'label_pdf_url' => 'https://api.pakketdienstqls.nl/v2/companies/9e606e6b-44a4-4a4e-a309-cc70ddd3a103/shipments/2d5948ae-fd44-4eb4-8fdd-2430495d2da0/labels/pdf',
        ]
    ];

    Http::fake([
        $apiUrl => Http::response($fakeResponse, 200)
    ]);

    $response = Http::post($apiUrl, $data);

    expect($response->status())->toBe(200);
    expect($response['data']['label_pdf_url'])->toBe('https://api.pakketdienstqls.nl/v2/companies/9e606e6b-44a4-4a4e-a309-cc70ddd3a103/shipments/2d5948ae-fd44-4eb4-8fdd-2430495d2da0/labels/pdf');
});

it('can generate a PDF file from the label response', function () {
    Storage::fake('public');

    $companyId = config('api.company_id');
    $brandId = config('api.brand_id');
    $apiUrl = config('api.url') . "/v2/companies/{$companyId}/shipments";

    $newOrder = Order::factory()
        ->has(OrderLine::factory()->count(2))
        ->create([
            'product_combination_id' => 1,
            'company_id' => $companyId,
            'brand_id' => $brandId,
        ]);

    $order = Order::with('orderLines')->find($newOrder['id']);

    $fakeResponse = [
        'data' => [
            'label_pdf_url' => 'https://api.pakketdienstqls.nl/v2/companies/9e606e6b-44a4-4a4e-a309-cc70ddd3a103/shipments/2d5948ae-fd44-4eb4-8fdd-2430495d2da0/labels/pdf',
        ]
    ];

    Http::fake([
        $apiUrl => Http::response($fakeResponse, 200)
    ]);

    $labelService = $this->app->make(CreateLabelPdfService::class);
    $response = $labelService->generatePdf($order->id);

    Storage::disk('public')->assertExists("label_{$order->id}.pdf");

    $content = Storage::disk('public')->get("label_{$order->id}.pdf");
    expect($content)->toStartWith('%PDF');

    expect($response)->toendWith("label_{$order->id}.pdf");
});
