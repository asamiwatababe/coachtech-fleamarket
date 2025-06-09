<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->words(3, true),
            'price' => $this->faker->numberBetween(1000, 10000),
            'description' => $this->faker->paragraph(),
            'image_url' => $this->faker->imageUrl(640, 480, 'fashion'),
            'condition' => $this->faker->randomElement(['新品', '中古', 'やや傷あり']),
            'brand' => $this->faker->company,
            'category_id' => Category::factory(),
        ];
    }
}
