<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_メールアドレス未入力のときはバリデーションエラーになる()
    {
        $response = $this->post('/login', [
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);
    }

    public function test_パスワード未入力のときはバリデーションエラーになる()
    {
        // ログインリクエスト送信（パスワードのみ未入力）
        $response = $this->post('/login', [
            'email' => 'test@example.com',
        ], ['Accept' => 'text/html']);

        // セッションに password のバリデーションエラーが含まれているか
        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);
    }

    public function test_存在しないログイン情報ではエラーメッセージが表示される()
    {
        // 存在しないユーザー情報でログイン試行
        $response = $this->from('/login')->post('/login', [
            'email' => 'invalid@example.com',
            'password' => 'wrongpassword',
        ], ['Accept' => 'text/html']);

        // エラーメッセージ確認
        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);

        // 元のログイン画面にリダイレクトしているか確認
        $response->assertRedirect('/login');
    }

    public function test_正しい情報でログインできる()
    {
        // ユーザーを作成（保存）
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // ログインリクエスト送信
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // 認証されていることを確認
        $this->assertAuthenticatedAs($user);

        // ログイン後のリダイレクトを確認
        $response->assertRedirect('/');
    }

    public function test_ログアウトできる()
    {
        // ユーザー作成 & ログイン
        $user = User::factory()->create();
        $response = $this->actingAs($user);

        // ログアウト処理を実行
        $logoutResponse = $this->post('/logout');

        // ログアウト後はゲスト状態になることを確認
        $this->assertGuest();

        // リダイレクト先を確認（商品一覧ページなど）
        $logoutResponse->assertRedirect('/');
    }

    
}