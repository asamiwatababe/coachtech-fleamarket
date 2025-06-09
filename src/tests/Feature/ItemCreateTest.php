<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\UploadedFile;

class ItemCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_商品出品画面で商品情報が正しく保存される()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->post('/sell', [
            'category_ids' => [$category->id],
            'condition' => '新品',
            'name' => 'テスト商品',
            'description' => 'これはテスト用の商品です。',
            'price' => 3000,
            'brand' => 'テストブランド',
            'image' => UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg'),
        ]);

        $response->assertRedirect(route('profile.show', ['tab' => 'sell']));

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'description' => 'これはテスト用の商品です。',
            'price' => 3000,
        ]);
        // 登録された商品を取得
        $item = Item::where('name', 'テスト商品')->first();

        $this->assertDatabaseHas('category_item', [
            'item_id' => $item->id,
            'category_id' => $category->id,
        ]);
    }
}
