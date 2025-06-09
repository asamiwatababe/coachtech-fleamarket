<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_いいねボタンを押すと商品がマイリストに追加されていいね数が1と表示される()
    {
        // ユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ログインしていいねを送信
        $this->actingAs($user)
            ->post("/item/{$item->id}/like")
            ->assertRedirect(); // リダイレクトを確認（通常は元の詳細ページなど）

        // likes テーブルにデータが登録されたか確認
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 商品詳細ページにアクセスし、いいね数が「1」と表示されているかを確認
        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);
        $response->assertSee('1'); // いいね数の表示（ビューに依存）

        // マイリストページにアクセスし、商品が表示されていることを確認
        $response = $this->actingAs($user)->get('/?tab=mylist');
        $response->assertStatus(200);
        $response->assertSee($item->name);
    }


    public function test_いいねした商品は色付きのアイコンが表示される()
    {
        // ユーザーと商品の作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // いいね登録（中間テーブルにデータを追加）
        $user->likes()->attach($item->id);

        // 商品詳細ページにアクセス
        $response = $this->actingAs($user)->get(route('item.show', $item->id));

        // いいね済みアイコンに特定のCSSクラスが含まれているか確認
        $response->assertSee('class="like-icon liked"', false); // HTMLを生で検出
    }

    public function test_再度いいねアイコンを押すといいねが解除されて合計数が減る()
    {
        // 1. ユーザーと出品者を作成
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        // 2. 商品を作成
        $item = Item::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        // 3. ユーザーが商品にいいねする
        $user->likes()->attach($item->id);

        // 4. 商品詳細ページで、いいね数が1であることを確認
        $response = $this->actingAs($user)->get(route('item.show', $item->id));
        $response->assertSee((string) 1); // いいね数が1

        // 5. いいね解除（DELETEリクエスト）
        $this->actingAs($user)->delete(route('item.unlike', $item->id));

        // 6. 商品詳細ページを再取得して、いいね数が0であることを確認
        $response = $this->actingAs($user)->get(route('item.show', $item->id));
        $response->assertSee((string) 0); // いいね数が0

        // 7. likes テーブルにレコードがないことを確認
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
}
