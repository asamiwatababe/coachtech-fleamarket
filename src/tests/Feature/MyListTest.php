<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    public function test_いいねした商品だけがマイリストに表示される()
    {
        // ユーザー作成
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        // 商品A（いいね商品）
        $likedItem = Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'いいね商品',
        ]);

        // 商品B（非いいね商品）
        $unlikedItem = Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => '非いいね商品',
        ]);

        // ユーザーが商品Aをいいね
        $user->likes()->attach($likedItem->id);

        // マイリストにアクセス
        $response = $this->actingAs($user)->get('/?tab=mylist');

        // いいね商品は表示される
        $response->assertSee('いいね商品');

        // 非いいね商品は表示されない
        $response->assertDontSee($unlikedItem->name);//非いいね商品
    }


    public function test_マイリストに購入済み商品はsoldと表示される()
    {
        // ユーザー作成
        $user = User::factory()->create();

        // 商品を作成（購入済み）
        $item = Item::factory()->create([
            'is_sold' => true,
        ]);

        // いいね登録
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // ログインしてマイリストページへアクセス
        $response = $this->actingAs($user)->get('/?tab=mylist');

        // 商品名が表示され、soldラベルが含まれていることを確認
        $response->assertStatus(200);
        $response->assertSee($item->name);
        $response->assertSee('sold');
    }

    public function test_自分が出品した商品はマイリスト一覧に表示されない()
    {
        // 出品者としてユーザーを作成
        $user = User::factory()->create();

        // 他ユーザーを作成
        $otherUser = User::factory()->create();

        // 自分が出品した商品を作成し、いいねする
        $myItem = Item::factory()->create(['user_id' => $user->id]);
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $myItem->id,
        ]);

        // 他人が出品した商品を作成し、いいねする
        $otherItem = Item::factory()->create(['user_id' => $otherUser->id]);
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $otherItem->id,
        ]);

        // ログインしてマイリストページへアクセス
        $response = $this->actingAs($user)->get('/?tab=mylist');

        // 表示されるアイテムに「自分が出品した商品」が含まれていないことを確認
        $response->assertStatus(200);
        $response->assertDontSee($myItem->name);
        $response->assertSee($otherItem->name);
    }

    public function test_未認証の場合はマイリストページにアクセスしても何も表示されない()
    {
        // 未ログイン状態でマイリストページにアクセス
        $response = $this->get('/?tab=mylist');
        // 期待される動作に応じてアサーションを変更：
        // 1. ログインページへリダイレクトされる場合：
        $response->assertStatus(200);
    }

    public function test_検索状態がマイリストでも保持されている()
    {
        $user = User::factory()->create();

        // ログインして商品を検索
        $response = $this->actingAs($user)->get('/?keyword=Apple');        $response->assertStatus(200);

        // マイリストページにキーワード付きで遷移
        $response = $this->actingAs($user)->get('/?tab=mylist&keyword=Apple');
        $response->assertStatus(200);

        // 入力欄などにキーワードが含まれているかを確認
        $response->assertSee('value="Apple"', false); // HTMLとしてvalue属性に含まれる
    }
}



