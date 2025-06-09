<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class ProfileEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_プロフィール編集画面で初期値が正しく表示される()
    {
        $user = User::factory()->create([
            'username' => 'テストユーザー',
            'profile_image' => 'test.jpg',
            'postal_code' => '123-4567',
            'address' => '東京都港区南青山',
            'building' => 'コーポ青山101'
        ]);

        // プロフィール編集画面にアクセス
        $response = $this->actingAs($user)->get(route('profile.page'));

        // ステータス確認
        $response->assertStatus(200);

        // 初期値が表示されているか確認
        $response->assertSee('test.jpg');               // プロフィール画像
        $response->assertSee('テストユーザー');          // ユーザー名
        $response->assertSee('123-4567');               // 郵便番号
        $response->assertSee('東京都港区南青山');         // 住所
    }
}
