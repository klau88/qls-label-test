<?php

use App\Services\Api;
use App\Models\Shipment;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('can authenticate to the QLS API', function () {
    $response = $this->get('/authenticate');

    $response->assertStatus(200);
});

it('can get products from the QLS API', function () {
    $response = $this->get(route('api.products'));

    $response->assertStatus(200);
});

it('can get a shipment response from a test order', function () {
    $shipment = Shipment::factory()->create();
    $response = $this->post(route('api.createLabel', [$shipment]));

    $response->assertStatus(200);
});

it('can generate a PDF file from the label response', function () {
    $order = app()->make('testOrder');
    $response = $this->post(route('shipments.store'), compact('order'));

    $response->assertStatus(302);
});
