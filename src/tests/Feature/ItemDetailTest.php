<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_商品詳細ページに必要な情報が全て表示される()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['name' => '家電']);

        $item = Item::factory()->create([
            'name' => 'イヤホン',
            'description' => '高音質のワイヤレスイヤホンです。',
            'price' => 12000,
            'brand' => 'Sony', // 直接文字列で指定（brand_idではない）
            'condition' => '新品',
            'image_url' => 'images/earphones.jpg',
        ]);

        // 多対多の関係を紐付ける
        $item->categories()->attach($category->id);

        Like::factory()->count(3)->create([
            'item_id' => $item->id,
        ]);

        $commentUser = User::factory()->create(['username' => 'テスト太郎']);

        Comment::factory()->create([
            'item_id' => $item->id,
            'user_id' => $commentUser->id,
            'comment' => 'とても良さそうですね！',
        ]);

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200);
        $response->assertSee('イヤホン');                    // 商品名
        $response->assertSee('Sony');                        // ブランド名
        $response->assertSee('¥12,000');                     // 価格（表示形式によって調整）
        $response->assertSee('高音質のワイヤレスイヤホン');  // 商品説明
        $response->assertSee('家電');                        // カテゴリ
        $response->assertSee('新品');                        // 商品の状態
        $response->assertSee('3');                           // いいね数（表記により調整）
        $response->assertSee('1');                           // コメント数
        $response->assertSee('テスト太郎');                    // コメントしたユーザー名
        $response->assertSee('とても良さそうですね！');       // コメント内容
        $response->assertSee('images/earphones.jpg');        // 商品画像のパス
    }

    public function test_複数カテゴリが表示される()
    {
        $item = Item::factory()->create([
            'name' => 'ノートパソコン',
        ]);

        $category1 = Category::factory()->create(['name' => '家電']);
        $category2 = Category::factory()->create(['name' => 'ゲーム']);

        // 商品にカテゴリを複数紐づけ
        $item->categories()->attach([$category1->id, $category2->id]);

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200);
        $response->assertSee('家電');
        $response->assertSee('ゲーム');
    }
}
