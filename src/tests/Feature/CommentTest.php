<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class CommentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_ログイン済みのユーザーはコメントを送信できる()
    {
        // 1. ユーザーと出品者を作成
        $user = User::factory()->create();
        $seller = User::factory()->create();

        // 2. 商品を作成
        $item = Item::factory()->create(['user_id' => $seller->id]);

        // 3. コメント送信（POST）
        $response = $this->actingAs($user)->post(route('item.comment', $item->id), [
            'comment' => 'とても良い商品ですね！',
        ]);

        // 4. リダイレクト確認
        $response->assertRedirect();

        // 5. コメントがデータベースに保存されていることを確認
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'とても良い商品ですね！',
        ]);

        // 6. コメント数が 1 件であることを確認（再取得）
        $this->assertEquals(1, $item->comments()->count());
    }

    public function test_ログインしていないユーザーはコメントを送信できない()
    {
        // 出品者と商品を作成
        $seller = User::factory()->create();
        $item = Item::factory()->create(['user_id' => $seller->id]);

        // ログインせずにコメント送信を試みる
        $response = $this->post(route('item.comment', $item->id), [
            'comment' => 'ゲストユーザーのコメント',
        ]);

        // 通常はログインページへリダイレクトされる（または403）
        $response->assertRedirect(route('login'));

        // コメントがDBに保存されていないことを確認
        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'comment' => 'ゲストユーザーのコメント',
        ]);
    }

    public function test_コメントが未入力の場合バリデーションメッセージが表示される()
    {
        // ユーザー作成
        $user = \App\Models\User::factory()->create();

        // 商品作成
        $item = \App\Models\Item::factory()->create();

        // ログイン状態でコメントを空で投稿
        $response = $this->actingAs($user)->post(route('item.comment', $item->id), [
            'comment' => '', // 空で送信
        ]);

        // 元のページにリダイレクトされる
        $response->assertRedirect();

        // バリデーションエラーを確認
        $response->assertSessionHasErrors('comment');
    }

    public function test_コメントが255文字を超えるとバリデーションエラーになる()
    {
        // ユーザー作成
        $user = \App\Models\User::factory()->create();

        // 商品作成
        $item = \App\Models\Item::factory()->create();

        // 256文字の文字列を作成
        $longComment = str_repeat('あ', 256);

        // ログイン状態でコメント送信
        $response = $this->actingAs($user)->post(route('item.comment', $item->id), [
            'comment' => $longComment,
        ]);

        // バリデーションエラーの検証
        $response->assertSessionHasErrors('comment');
        $response->assertRedirect();
    }
}
