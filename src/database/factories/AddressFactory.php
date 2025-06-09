<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'item_id' => \App\Models\Item::factory(),
            'zip_code' => $this->faker->postcode(),
            'address' => $this->faker->address(),
            'building' => $this->faker->secondaryAddress(),
        ];
    }
}
