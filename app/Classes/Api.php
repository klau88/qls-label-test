<?php

namespace App\Classes;

use App\Models\Shipment;
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
            'company_id' => config('api.company_id'),
            'brand_id' => config('api.brand_id'),
//            'servicepoint_code' => 'string',
            'reference' => $order['number'],
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
                'address2' => $order['delivery_address']['address_line_2'] ?? null,
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
                'ean' => $orderLine['ean'] ?? null,
                'sku' => (string)($orderLine['sku'] ?? null),
                'price_per_unit' => $orderLine['price_per_unit'] ?? null,
//            'weight_per_unit' => 500,
                'currency' => 'EUR'
            ]);
        }

        return $shipment;
    }

    public function getLabel(Shipment $shipment)
    {
        $companyId = config('api.company_id');
        $brandId = config('api.brand_id');
        $data = [
            'product_combination_id' => $shipment['product_combination_id'],
            'brand_id' => $brandId,
            'reference' => $shipment['reference'],
            'weight' => $shipment['weight'],
            'return_contact' => [
                'name' => $shipment['sender_name'],
                'companyname' => $shipment['sender_contact_companyname'],
                'street' => $shipment['sender_street'],
                'housenumber' => $shipment['sender_housenumber'],
                'address2' => $shipment['sender_address2'],
                'postalcode' => $shipment['sender_postalcode'],
                'locality' => $shipment['sender_city'],
                'country' => $shipment['sender_country'],
                'email' => $shipment['sender_email'] ?? null,
                'phone' => $shipment['sender_phone'] ?? null,
            ],
            'sender_contact' => [
                'name' => $shipment['sender_name'],
                'companyname' => $shipment['sender_contact_companyname'],
                'street' => $shipment['sender_street'],
                'housenumber' => $shipment['sender_housenumber'],
                'address2' => $shipment['sender_address2'],
                'postalcode' => $shipment['sender_postalcode'],
                'locality' => $shipment['sender_city'],
                'country' => $shipment['sender_country'],
                'email' => $shipment['sender_email'] ?? null,
                'phone' => $shipment['sender_phone'] ?? null,
            ],
            'receiver_contact' => [
                'name' => $shipment['receiver_name'],
                'companyname' => $shipment['receiver_company_name'],
                'street' => $shipment['receiver_street'],
                'housenumber' => $shipment['receiver_housenumber'],
                'address2' => $shipment['receiver_address2'],
                'postalcode' => $shipment['receiver_postalcode'],
                'locality' => $shipment['receiver_city'],
                'country' => $shipment['receiver_country'],
                'email' => $shipment['receiver_email'] ?? null,
                'phone' => $shipment['receiver_phone'] ?? null,
            ],
            'shipment_products' => [],
            'zpl_direct' => true
        ];

        foreach ($shipment['products'] as $product) {
            array_push($data, [
                'amount' => $product['amount'],
                'name' => $product['name'],
                'ean' => $product['ean'] ?? null,
                'sku' => $product['sku'] ?? null,
                'currency' => $product['currency'] ?? null
            ]);
        }

        return $this->fetch()->post(config('api.url') . "/v2/companies/{$companyId}/shipments", $data);
    }

    public function getLabelPdf(string $url)
    {
        return $this->fetch()->get($url);
    }
}
