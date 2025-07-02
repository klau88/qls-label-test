<?php

use App\Models\Order;
use App\Models\OrderLine;
use App\Services\Api;
use Illuminate\Support\Facades\Http;

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
            'id' => '2d5948ae-fd44-4eb4-8fdd-2430495d2da0',
            'company_id' => '9e606e6b-44a4-4a4e-a309-cc70ddd3a103',
            'brand_id' => 'e41c8d26-bdfd-4999-9086-e5939d67ae28',
            'product_combination_id' => 1,
            'status' => 'printed',
            'reference' => '#' . $order['number'],
            'sender_contact' => [
                'name' => 'John Doe',
                'companyname' => 'QLS',
                'street' => 'Daltonstraat',
                'housenumber' => '65',
                'postalcode' => '3316GD',
                'locality' => 'Dordrecht',
                'country' => 'NL',
                'phone' => '0101234567',
                'email' => 'email@example.com',
            ],
            'delivery_contact' => [
                'name' => 'John Doe',
                'companyname' => 'QLS',
                'street' => 'Daltonstraat',
                'housenumber' => '65',
                'postalcode' => '3316GD',
                'locality' => 'Dordrecht',
                'country' => 'NL',
                'phone' => '0101234567',
                'email' => 'email@example.com',
            ],
            'shipment_products' => [
                'data' => [
                    [
                        'id' => 'dffc3b85-eafa-4d54-b49a-f1f533ca8379',
                        'amount' => 2,
                        'name' => 'Jeans - Black - 36',
                        'country_code_of_origin' => null,
                        'hs_code' => null,
                        'ean' => '8710552295268',
                        'sku' => '69205',
                        'price_per_unit' => 29.99,
                        'weight_per_unit' => null,
                        'currency' => 'EUR'
                    ],
                    [
                        'id' => 'a93f38f2-b404-45c5-bfd2-f1962d3fee9d',
                        'amount' => 1,
                        'name' => 'Sjaal - Rood Oranje',
                        'country_code_of_origin' => null,
                        'hs_code' => null,
                        'ean' => '3059943009097',
                        'sku' => '25920',
                        'price_per_unit' => 10.99,
                        'weight_per_unit' => null,
                        'currency' => 'EUR'
                    ]
                ]
            ],
            'product' => [
                'id' => 1,
                'short' => 'dhl-mailbox-parcel',
                'name' => 'DHL Brievenbus pakje',
                'type' => 'delivery',
                'product_family' => [
                    'id' => '1a6ae81e-ce57-4daf-8709-8e5f3ca42715',
                    'name' => 'DHL eCommerce',
                    'target_group_tag' => 'family_dhlparcel_ecommerce'
                ]
            ],
            'tracking_id' => '3SQLW0028308715'
        ]
    ];

    Http::fake([
        $apiUrl => Http::response($fakeResponse, 200)
    ]);

    $response = Http::post($apiUrl, $data);

    expect($response->status())->toBe(200);
    expect($response['data']['company_id'])->toBe(config('api.company_id'));
    expect($response['data']['brand_id'])->toBe(config('api.brand_id'));
    expect($response['data']['product_combination_id'])->toBe(1);
    expect($response['data']['reference'])->toBe('#' . $order['number']);
    expect($response['data']['status'])->toBe('printed');
    expect($response['data']['shipment_products']['data'])->toHaveCount(2);
    expect($response['data']['tracking_id'])->toBeTruthy();
});

//it('can generate a PDF file from the label response', function () {
//    $order = $this->app->make('testOrder');
//    $response = $this->post(route('orders.store'), compact('order'));
//
//    $response->assertStatus(302);
//});
