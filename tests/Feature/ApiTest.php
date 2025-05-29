<?php

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
    $response = $this->post(route('api.createLabel'), []);

    $response->assertStatus(200);
});

it('can generate a PDF file from the label response', function () {
    $response = $this->get(route('api.generateLabel'));

    $response->assertStatus(200);
});
