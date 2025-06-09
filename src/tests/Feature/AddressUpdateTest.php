<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;

class AddressUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_住所変更後に購入画面に新しい住所が反映される()
    {
        // ユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ログイン状態にする
        $this->actingAs($user);

        // 送付先住所を変更（登録）
        $response = $this->post(route('purchase.address.update', $item->id), [
            'zip_code' => '123-4567',
            'address' => '東京都新宿区1-1-1',
            'building' => 'マンション101',
        ]);

        $response->assertRedirect(route('purchase.show', $item->id));

        // 商品購入画面にアクセス
        $response = $this->get(route('purchase.show', $item->id));

        // レスポンスに登録した住所が表示されているか確認
        $response->assertSee('123-4567');
        $response->assertSee('東京都新宿区1-1-1');
        $response->assertSee('マンション101');
    }
}
