@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endsection


@section('content')
<div class="register-container">
    <h2>会員登録</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <label>ユーザー名</label>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>メールアドレス</label>
            <input type="email" name="email" value="{{ old('email') }}">
            @error('email') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>パスワード</label>
            <input type="password" name="password">
            @error('password') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>確認用パスワード</label>
            <input type="password" name="password_confirmation">
        </div>

        <button type="submit" class="register-button">登録する</button>
    </form>

    <div class="login-link">
        <a href="{{ route('login') }}">ログインはこちら</a>
    </div>
</div>
@endsection