<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_名前未入力の時はバリデーションエラーになる()
    {
        $response = $this->post('/register', [
            // 'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'name' => 'お名前を入力してください',
        ]);
    }

    public function test_メールアドレス未入力のときはバリデーションエラーになる()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            // 'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);
    }

    public function test_パスワード未入力のときはバリデーションエラーになる()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            // 'password' => '',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);
    }

    public function test_パスワードが7文字以下のときはバリデーションエラーになる()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'short1', // 7文字
            'password_confirmation' => 'short1',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードは8文字以上で入力してください',
        ]);
    }

    public function test_パスワードと確認用パスワードが一致しないときはバリデーションエラーになる()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different456', // 不一致
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードと一致しません',
        ]);
    }

    public function test_全ての入力が正しければ登録されログイン画面に遷移する()
    {
        // データリセット
        $this->refreshDatabase();

        // 入力データ
        $data = [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/register', $data);

        // users テーブルに登録されたか確認
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);

        // Fortifyの設定によりログイン画面へリダイレクトされる場合=$response->assertRedirect('/login');これはリダイレクト先がプロフィール画面
        $response->assertRedirect('/mypage/profile');
        // $response->assertRedirect('/login');
    }
}
