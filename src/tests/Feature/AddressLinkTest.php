<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;

class AddressLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_購入した商品に送付先住所が紐づいて登録される()
    {
        // ユーザーと出品者を作成
        $user = User::factory()->create();
        $seller = User::factory()->create();

        // 商品を出品者が登録
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'is_sold' => false,
        ]);

        // 住所を事前に登録（この住所が紐づく）
        $address = Address::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'zip_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => '渋谷ビル301',
        ]);

        // 商品購入処理を実行
        $response = $this->actingAs($user)->post(route('purchase.store', $item->id), [
            'payment_method' => 'credit_card',
            'address_id' => $address->id,
        ]);

        $response->assertRedirect(route('products.index'));

        // 購入情報がpurchasesテーブルに保存されている
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 送付先住所が正しく保存されているか確認
        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'zip_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
        ]);
    }
}
