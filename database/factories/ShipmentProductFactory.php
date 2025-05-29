<?php

namespace Database\Factories;

use App\Models\Shipment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShipmentProduct>
 */
class ShipmentProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'shipment_id' => Shipment::first()->id,
            'shipment_product_id' => fake()->uuid(),
            'amount' => fake()->numberBetween(1, 10),
            'name' => fake()->name(),
            'country_code_of_origin' => fake()->countryCode(),
            'price_per_unit' => fake()->numberBetween(1, 10),
            'weight_per_unit' => fake()->numberBetween(1, 10),
            'currency' => fake()->currencyCode(),
        ];
    }
}
