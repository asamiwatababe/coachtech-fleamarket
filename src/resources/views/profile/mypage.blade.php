<!-- マイページ画面 -->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-container">
    <div class="profile-header">
        <div class="profile-box">
            <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-user.png') }}"
                alt=""
                class="profile-image">
            <div class="user-info">
                <h2 class="username">{{ $user->username ?? 'ユーザー名未設定' }}</h2>
            </div>
        </div>
        <a href="{{ route('profile.page') }}" class="edit-button">プロフィールを編集</a>
    </div>

    <div class="tab-menu">
        <a href="{{ route('profile.show', ['tab' => 'sell']) }}"
            class="{{ ($tab ?? '') === 'sell' ? 'active' : '' }}">
            出品した商品
        </a>
        <a href="{{ route('profile.show', ['tab' => 'buy']) }}"
            class="{{ ($tab ?? '') === 'buy' ? 'active' : '' }}">
            購入した商品
        </a>
    </div>

    <!-- 購入と出品した商品の画像や名前 -->
    <div class="product-list">
        @forelse ($items as $item)
        <div class="product-card">
            <a href="{{ route('item.show', $item->id) }}">
                @php
                $imagePath = Str::startsWith($item->image_url, 'images/')
                ? asset($item->image_url)
                : asset('storage/' . $item->image_url);
                @endphp
                <img src="{{ $imagePath }}" alt="{{ $item->name }}" class="product-image">

                @if ($item->is_sold)
                <span class="sold-label">sold</span>
                @endif
            </a>

            <div class="label">{{ $item->name }}</div>
        </div>
        @empty
        <p>
            {{ $tab === 'buy' ? 'まだ購入した商品はありません。' : 'まだ出品された商品はありません。' }}
        </p>
        @endforelse
    </div>
</div>
@endsection