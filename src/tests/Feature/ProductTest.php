<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_全商品を取得できる()
    {
        // 商品データを5件作成
        Item::factory()->count(5)->create();

        // 商品一覧ページへアクセス
        $response = $this->get('/');

        // レスポンスが正常（200 OK）
        $response->assertStatus(200);

        // ビューに商品が5件含まれていることを確認
        $items = Item::all();
        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    public function test_購入済み商品に_sold_ラベルが表示される()
    {
        // 購入済み商品を作成
        $soldItem = Item::factory()->create([
            'name' => 'テスト商品',
            'is_sold' => true,
        ]);

        // 商品一覧ページへアクセス
        $response = $this->get('/');

        // ステータスコード確認
        $response->assertStatus(200);

        // 商品名と「sold」ラベルが表示されることを確認
        $response->assertSee('テスト商品');
        $response->assertSee('sold');
    }

    public function test_自分が出品した商品は一覧に表示されない()
    {
        // ユーザー作成（ログインユーザー）
        $user = User::factory()->create();

        // ログインユーザーが出品した商品
        $ownItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '自分の商品',
        ]);

        // 他のユーザーの商品
        $otherItem = Item::factory()->create([
            'name' => '他人の商品',
        ]);

        // ログインして商品一覧ページにアクセス
        $response = $this->actingAs($user)->get('/');

        // 自分の出品商品は含まれていないことを確認
        $response->assertDontSee('自分の商品');

        // 他人の出品商品は表示されていることを確認
        $response->assertSee('他人の商品');
    }

    public function test_商品名で部分一致検索ができる()
    {
        // 検索対象の商品を複数作成
        Item::factory()->create(['name' => 'Apple Watch']);
        Item::factory()->create(['name' => 'Apple iPhone']);
        Item::factory()->create(['name' => 'Samsung Galaxy']);

        // 「Apple」で検索
        $response = $this->get('/?keyword=Apple');
        
        // レスポンスが正常
        $response->assertStatus(200);

        // 部分一致する商品が表示されることを確認
        $response->assertSee('Apple Watch');
        $response->assertSee('Apple iPhone');

        // 一致しない商品が表示されないことを確認
        $response->assertDontSee('Samsung Galaxy');
    }
}


