<?php

namespace App\Providers;

use App\Classes\Api;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(Api::class, function () {
            return new Api(config('api.username'), config('api.password'));
        });

        $this->app->bind('testOrder', function () {
            return [
                'number' => '#958201',
                'billing_address' => [
                    'companyname' => 'QLS',
                    'name' => 'John Doe',
                    'street' => 'Daltonstraat',
                    'housenumber' => '65',
                    'address_line_2' => '',
                    'zipcode' => '3316GD',
                    'city' => 'Dordrecht',
                    'country' => 'NL',
                    'email' => 'email@example.com',
                    'phone' => '0101234567',
                ],
                'delivery_address' => [
                    'companyname' => 'QLS',
                    'name' => 'John Doe',
                    'street' => 'Daltonstraat',
                    'housenumber' => '65',
                    'address_line_2' => '',
                    'zipcode' => '3316GD',
                    'city' => 'Dordrecht',
                    'country' => 'NL',
                    'email' => 'email@example.com',
                    'phone' => '0101234567',
                ],
                'order_lines' => [
                    [
                        'amount_ordered' => 2,
                        'name' => 'Jeans - Black - 36',
                        'sku' => '69205',
                        'ean' => '8710552295268',
                    ],
                    [
                        'amount_ordered' => 1,
                        'name' => 'Sjaal - Rood Oranje',
                        'sku' => '25920',
                        'ean' => '3059943009097',
                    ]
                ]
            ];
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
