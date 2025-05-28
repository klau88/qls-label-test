<?php

use App\Classes\Api;

it('can authenticate to the QLS API', function () {
    $response = $this->get('/authenticate');

    $response->assertStatus(200);
});

it('can get products from the QLS API', function () {
    $companyId = config('api.company_id');
    $response = $this->get('/products');

    $response->assertStatus(200);
});

it('can get a shipment response from a test order', function () {
    $response = $this->post('/label/create');

    $response->assertStatus(200);
});
