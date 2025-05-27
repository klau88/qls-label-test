<?php

it('can authenticate to the QLS API', function () {
    $credentials = [
        'username' => config('api.username'),
        'password' => config('api.password'),
    ];

    $response = $this->get('/authenticate', $credentials);

    $response->assertStatus(200);
});
