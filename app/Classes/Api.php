<?php

namespace App\Classes;

use Illuminate\Support\Facades\Http;

class Api
{
    private $url = 'https://api.pakketdienstqls.nl/';
    private $username;
    private $password;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    private function fetch()
    {
        return Http::withBasicAuth($this->username, $this->password);
    }

    public function authenticate()
    {
        return $this->fetch()->get(config('api.url'));
    }

    public function getProducts()
    {
        $companyId = config('api.company_id');
        return $this->fetch()->get(config('api.url') . "companies/{$companyId}/products");
    }

    public function mapOrderToShipment($order)
    {
        $shipment = [
            'product_combination_id' => 1,
            'brand_id' => config('api.brand_id'),
//            'servicepoint_code' => 'string',
            'reference' => '#958201',
            'weight' => 0,
//            'cod_amount' => 0.1,
//            'customs_invoice_number' => 'string',
//            'customs_shipment_type' => 'commercial',
            'return_contact' => [
                'name' => 'John Doe',
                'companyname' => 'QLS',
                'street' => 'Daltonstraat',
                'housenumber' => '65',
                'address2' => '',
                'postalcode' => '3316GD',
                'locality' => 'Dordrecht',
                'country' => 'NL',
                'email' => 'email@example.com',
                'phone' => '0101234567',
//                'vat' => 'string',
//                'eori' => 'string',
//                'oss' => 'string',
//                'type' => 'string'
            ],
            'sender_contact' => [
                'name' => 'John Doe',
                'companyname' => 'QLS',
                'street' => 'Daltonstraat',
                'housenumber' => '65',
                'address2' => '',
                'postalcode' => '3316GD',
                'locality' => 'Dordrecht',
                'country' => 'NL',
                'email' => 'email@example.com',
                'phone' => '0101234567',
//                'vat' => 'string',
//                'eori' => 'string',
//                'oss' => 'string',
//                'type' => 'string'
            ],
            'receiver_contact' => [
                'name' => $order['delivery_address']['name'],
                'companyname' => $order['delivery_address']['companyname'],
                'street' => $order['delivery_address']['street'],
                'housenumber' => $order['delivery_address']['housenumber'],
                'address2' => $order['delivery_address']['address_line_2'],
                'postalcode' => $order['delivery_address']['zipcode'],
                'locality' => $order['delivery_address']['city'],
                'country' => $order['delivery_address']['country'],
                'email' => $order['delivery_address']['email'] ?? null,
                'phone' => $order['delivery_address']['phone'] ?? null,
//                'vat' => 'string',
//                'eori' => 'string',
//                'oss' => 'string',
//                'type' => 'string'
            ],
            'shipment_products' => [],
            'zpl_direct' => true
        ];

        foreach ($order['order_lines'] as $orderLine) {
            array_push($shipment['shipment_products'], [
                'amount' => $orderLine['amount_ordered'],
                'name' => $orderLine['name'],
//            'country_code_of_origin' => 'US',
//            'hs_code' => '123456',
                'ean' => $orderLine['ean'],
                'sku' => (string)$orderLine['sku'],
//            'price_per_unit' => 10.99,
//            'weight_per_unit' => 500,
                'currency' => 'EUR'
            ]);
        }

        return $shipment;
    }

    public function postLabel($shipment)
    {
        $companyId = config('api.company_id');
        return $this->fetch()->post(config('api.url') . "/v2/companies/{$companyId}/shipments", $shipment);
    }
}
