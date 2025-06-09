<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use App\Actions\Fortify\CreateNewUser;
use Laravel\Fortify\Contracts\LoginResponse;
use App\Http\Responses\CustomLoginResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

class FortifyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(LoginResponse::class, CustomLoginResponse::class);
    }

    protected function registered(Request $request, $user)
    {
        return redirect('/mypage/profile');
    }

    public function boot()
    {
        // ログイン画面ビュー
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // 会員登録画面ビュー
        Fortify::registerView(function () {
            return view('auth.register');
        });

        // 会員登録後のリダイレクト先を明示的に指定

        // Fortify 独自の認証処理に FormRequest を組み込む
        Fortify::authenticateUsing(function (Request $request) {
            // App\Http\Requests\LoginRequest を利用してバリデーションルール・メッセージ取得
            $formRequest = new LoginRequest();

            Validator::make(
                $request->all(),
                $formRequest->rules(),
                $formRequest->messages()
            )->validate();

            // 認証処理
            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }

            // 認証失敗時に LoginRequest からカスタムメッセージを使用
            throw ValidationException::withMessages([
                'email' => [$formRequest->messages()['email.failed']],
            ]);
        });

        // Fortify 会員登録ロジック
        Fortify::createUsersUsing(CreateNewUser::class);
    }
}
