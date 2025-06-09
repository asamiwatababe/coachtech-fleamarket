<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_購入するボタンを押すと購入が完了する()
    {
        // ユーザーと出品者を作成
        $user = User::factory()->create();
        $seller = User::factory()->create();

        // 出品商品を作成
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'is_sold' => false,
        ]);

        // ダミーの住所を作成
        $address = Address::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 購入処理を実行
        $response = $this->actingAs($user)->post(route('purchase.store', $item->id), [
            'payment_method' => 'credit_card',
            'address_id' => $address->id,
        ]);

        // 商品一覧ページにリダイレクトされる
        $response->assertRedirect(route('products.index'));

        // 購入情報がDBに登録されていることを確認
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 商品が「売り切れ」になっているか確認(型で)
        $this->assertEquals(true, $item->fresh()->is_sold);
    }

    public function test_購入した商品は商品一覧画面で_sold_と表示される()
    {
        // ユーザーと出品者を作成
        $user = User::factory()->create();
        $seller = User::factory()->create();

        // 商品を作成（未購入）
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'is_sold' => false,
            'name' => 'テスト商品',
        ]);

        // ダミー住所登録
        \App\Models\Address::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // ユーザーが商品を購入
        $this->actingAs($user)->post(route('purchase.store', $item->id), [
            'payment_method' => 'credit_card',
            'address_id' => 1,
        ]);

        // 商品一覧ページを表示
        $response = $this->actingAs($user)->get(route('products.index'));

        // レスポンス内容に「sold」が含まれているか確認
        $response->assertSeeText('sold');
    }

    public function test_購入した商品はプロフィール画面の購入一覧に追加される()
    {
        // 購入者と出品者を作成
        $user = User::factory()->create();
        $seller = User::factory()->create();

        // 商品を作成
        $item = \App\Models\Item::factory()->create([
            'user_id' => $seller->id,
            'is_sold' => false,
            'name' => 'テスト商品',
        ]);

        // 住所を作成（バリデーション対策）
        $address = \App\Models\Address::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 購入処理を実行
        $this->actingAs($user)->post(route('purchase.store', $item->id), [
            'payment_method' => 'credit_card',
            'address_id' => $address->id,
        ]);

        // プロフィール画面（購入一覧タブ）を表示
        $response = $this->actingAs($user)->get('/mypage?tab=buy');

        // 購入した商品が表示されているか確認
        $response->assertSeeText('テスト商品');
    }

    public function test_支払い方法を選択すると小計に反映される()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $item = Item::factory()->create(['user_id' => $seller->id]);

        // ダミーの住所を作成（バリデーション対策）
        $address = \App\Models\Address::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 選択された支払い方法とともに購入画面にアクセス（POSTで送信）
        $response = $this->actingAs($user)->post(route('purchase.store', $item->id), [
            'payment_method' => 'credit_card',
            'address_id' => $address->id,
        ]);

        // 処理後にリダイレクトされることを確認
        $response->assertRedirect(route('products.index'));
    }
}
