<!-- ログイン画面 -->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endsection

@section('content')
<div class="form-container">
    <h2 class="form-title">ログイン</h2>

    @if ($errors->has('login'))
    <p class="error">{{ $errors->first('login') }}</p>
    @endif
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="text" name="email" value="{{ old('email') }}">
            @error('email')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">パスワード</label>
            <input type="text" name="password">
            @error('password')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="submit-btn">ログインする</button>
    </form>

    <div class="form-footer">
        <a href="{{ route('register') }}">会員登録はこちら</a>
    </div>
</div>
@endsection

