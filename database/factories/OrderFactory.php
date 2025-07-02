<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => fake()->numberBetween(1000, 99999),
            'billing_companyname' => fake()->company(),
            'billing_name' => fake()->name(),
            'billing_street' => fake()->streetName(),
            'billing_housenumber' => fake()->numberBetween(1, 999),
            'billing_address_line_2' => fake()->streetSuffix(),
            'billing_zipcode' => fake()->postcode(),
            'billing_city' => fake()->city(),
            'billing_country' => fake()->countryCode(),
            'billing_email' => fake()->companyEmail(),
            'billing_phone' => fake()->phoneNumber(),
            'delivery_companyname' => fake()->company(),
            'delivery_name' => fake()->name(),
            'delivery_street' => fake()->streetName(),
            'delivery_housenumber' => fake()->numberBetween(1, 999),
            'delivery_address_line_2' => fake()->streetSuffix(),
            'delivery_zipcode' => fake()->postcode(),
            'delivery_city' => fake()->city(),
            'delivery_country' => fake()->countryCode(),
            'delivery_email' => fake()->companyEmail(),
            'delivery_phone' => fake()->phoneNumber(),
        ];
    }
}
