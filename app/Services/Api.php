<?php

namespace App\Services;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

class Api
{
    private string $username;
    private string $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return PendingRequest
     */
    private function fetch(): PendingRequest
    {
        return Http::withBasicAuth($this->username, $this->password);
    }

    /**
     * @return Response
     * @throws ConnectionException
     */
    public function authenticate(): Response
    {
        return $this->fetch()->get(config('api.url'));
    }

    /**
     * @return Response
     * @throws ConnectionException
     */
    public function getProducts(): Response
    {
        $companyId = config('api.company_id');
        return $this->fetch()->get(config('api.url') . "companies/{$companyId}/products");
    }

    /**
     * @param array $order
     * @return array
     */
    public function mapOrderToShipment(array $order): array
    {
        $shipment = [
            'product_combination_id' => 1,
            'company_id' => config('api.company_id'),
            'brand_id' => config('api.brand_id'),
            'reference' => $order['number'],
            'weight' => 0,
            'sender_contact' => [
                'name' => $order['billing_name'],
                'companyname' => $order['billing_companyname'],
                'street' => $order['billing_street'],
                'housenumber' => $order['billing_housenumber'],
                'address2' => $order['billing_address_line_2'] ?? null,
                'postalcode' => $order['billing_zipcode'],
                'locality' => $order['billing_city'],
                'country' => $order['billing_country'],
                'email' => $order['billing_email'],
                'phone' => $order['billing_phone'],
            ],
            'receiver_contact' => [
                'name' => $order['delivery_name'],
                'companyname' => $order['delivery_companyname'],
                'street' => $order['delivery_street'],
                'housenumber' => $order['delivery_housenumber'],
                'address2' => $order['delivery_address_line_2'] ?? null,
                'postalcode' => $order['delivery_zipcode'],
                'locality' => $order['delivery_city'],
                'country' => $order['delivery_country'],
                'email' => $order['delivery_email'] ?? null,
                'phone' => $order['delivery_phone'] ?? null,
            ],
            'shipment_products' => [],
            'zpl_direct' => true
        ];

        foreach ($order['order_lines'] as $orderLine) {
            array_push($shipment['shipment_products'], [
                'amount' => $orderLine['amount_ordered'],
                'name' => $orderLine['name'],
                'country_code_of_origin' => $orderLine['country'] ?? null,
                'hs_code' => $orderLine['hs_code'] ?? null,
                'ean' => $orderLine['ean'] ?? null,
                'sku' => (string)($orderLine['sku'] ?? null),
                'price_per_unit' => $orderLine['price_per_unit'] ?? null,
                'weight_per_unit' => $orderLine['weight_per_unit'] ?? null,
                'currency' => 'EUR'
            ]);
        }

        return $shipment;
    }

    /**
     * @param array $shipment
     * @return array
     */
    public function mapShipmentToLabelRequest(array $shipment): array
    {
        $brandId = config('api.brand_id');

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

        return $data;
    }

    /**
     * @param $shipment
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function getLabel($shipment): Response
    {
        $companyId = config('api.company_id');
        $data = $this->mapShipmentToLabelRequest($shipment);

        return $this->fetch()->post(config('api.url') . "/v2/companies/{$companyId}/shipments", $data);
    }

    /**
     * @param string $url
     * @return array
     * @throws ConnectionException
     */
    public function getLabelPdf(string $url): array
    {
        return $this->fetch()->get($url)->json();
    }
}
