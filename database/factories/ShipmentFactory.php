<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shipment>
 */
class ShipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $barcode = fake()->text(15);
        return [
            'shipment_id' => fake()->uuid(),
            'company_id' => config('api.company_id') ?: fake()->uuid(),
            'brand_id' => config('api.brand_id') ?: fake()->uuid(),
            'product_id' => fake()->numberBetween(1, 3),
            'product_combination_id' => fake()->numberBetween(1, 3),
            'barcode' => $barcode,
            'tracking_url' => fake()->url(),
            'status' => fake()->text(15),
            'type' => fake()->text(15),
            'weight' => fake()->numberBetween(0, 5),
            'reference' => fake()->text(15),
            'cod_amount' => fake()->numberBetween(0, 1),
            'customs_shipment_type' => fake()->text(15),
            'customs_invoice_number' => fake()->text(15),
            'label_pdf_url' => fake()->url(),
            'label_zpl_url' => fake()->url(),
            'sender_company_name' => fake()->company(),
            'sender_name' => fake()->name(),
            'sender_street' => fake()->streetName(),
            'sender_housenumber' => fake()->numberBetween(1, 99),
            'sender_address2' => fake()->streetSuffix(),
            'sender_postalcode' => fake()->postcode(),
            'sender_city' => fake()->city(),
            'sender_country' => fake()->countryCode(),
            'sender_phone' => fake()->phoneNumber(),
            'sender_email' => fake()->companyEmail(),
            'receiver_company_name' => fake()->company(),
            'receiver_name' => fake()->name(),
            'receiver_street' => fake()->streetName(),
            'receiver_housenumber' => fake()->numberBetween(1, 99),
            'receiver_address2' => fake()->streetSuffix(),
            'receiver_postalcode' => fake()->postcode(),
            'receiver_city' => fake()->city(),
            'receiver_country' => fake()->countryCode(),
            'receiver_phone' => fake()->phoneNumber(),
            'receiver_email' => fake()->email(),
            'shipment_short' => fake()->text(15),
            'shipment_name' => fake()->text(15),
            'shipment_type' => fake()->text(15),
            'tracking_id' => $barcode
        ];
    }
}
