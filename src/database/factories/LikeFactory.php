<?php

namespace Database\Factories;

use App\Models\Like;
use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

// マイリストの購入済み商品にsoldラベルが表示される
class LikeFactory extends Factory
{
    protected $model = Like::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'item_id' => Item::factory(),
        ];
    }
}
