<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;

class ProfileInfoTest extends TestCase
{
    use RefreshDatabase;

    public function test_プロフィールページで必要な情報が取得できる()
    {
        // ユーザーを作成しプロフィール画像を設定
        $user = User::factory()->create([
            'username' => 'テストユーザー',
            'profile_image' => 'test.jpg',
        ]);

        // 出品商品を作成
        $sellItem = Item::factory()->create([
            'user_id' => $user->id,
        ]);

        // 購入用に他のユーザーと商品を作成
        $seller = User::factory()->create();
        $buyItem = Item::factory()->create(['user_id' => $seller->id]);

        // 購入済みにする
        $user->purchases()->attach($buyItem->id);
        $buyItem->update(['is_sold' => true]);

        // プロフィールページにアクセス
        $response = $this->actingAs($user)->get('/mypage');

        // 正常なレスポンス
        $response->assertStatus(200);

        // プロフィール画像、ユーザー名が表示されている
        $response->assertSee('test.jpg');
        $response->assertSee('テストユーザー');

        // 出品商品用テスト
        $responseSell = $this->actingAs($user)->get('/mypage?tab=sell');
        $responseSell->assertSee($sellItem->name);

        // 購入商品用テスト
        $responseBuy = $this->actingAs($user)->get('/mypage?tab=buy');
        $responseBuy->assertSee($buyItem->name);
    }
}
