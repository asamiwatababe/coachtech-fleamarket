<!-- 商品詳細ページ -->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<div class="item-detail">
    <div class="left">
        <div class="left">
            @php use Illuminate\Support\Str; @endphp
            <img
                src="{{ Str::startsWith($item->image_url, 'images/') ? asset($item->image_url) : asset('storage/' . $item->image_url) }}"
                alt="{{ $item->name }}"
                style="max-width: 300px;">
        </div>
    </div>

    <div class="right">
        <!-- 商品名・ブランド・価格 -->
        <h2>{{ $item->name }}</h2>
        <p class="item-bland">{{ $item->brand }}</p>
        <p class="item-price">¥{{ number_format($item->price) }}（税込）</p>

        <!-- アイコンと数 -->
        <div class="icon-section">
            <!-- いいね -->
            <div class="icon-item">
                @auth
                @if ($item->likes->contains('user_id', auth()->id()))
                <!-- いいね済み -->
                <form method="POST" action="{{ route('item.unlike', $item->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background: none; border: none;">
                        <img src="{{ asset('images/items/like-icon.png') }}" alt="いいね済み" class="like-icon liked">
                    </button>
                </form>
                @else
                <!-- いいね未 -->
                <form method="POST" action="{{ route('item.like', $item->id) }}">
                    @csrf
                    <button type="submit" style="background: none; border: none;">
                        <img src="{{ asset('images/items/like-icon.png') }}" alt="いいね" class="like-icon">
                    </button>
                </form>
                @endif
                @endauth
                <div class="icon-count">{{ $item->likes->count() }}</div>
            </div>

            <!-- コメント -->
            <div class="icon-item">
                <img src="{{ asset('images/items/comment-icon.png') }}" alt="コメント" class="icon-image">
                <div class="icon-count">{{ $item->comments->count() }}</div>
            </div>
        </div>

        <!-- 購入ボタン -->
        <form action="{{ route('purchase.show', $item->id) }}" method="GET">
            <button type="submit" class="purchase-button">購入手続きへ</button>
        </form>

        <h3>商品説明</h3>
        <p>{{ $item->description }}</p>

        <h3>商品情報</h3>
        <div class="info-row">
            <div class="info-label">カテゴリ</div>
            <div class="info-value">
                @foreach ($item->categories as $category)
                <span>{{ $category->name }}</span>@if (!$loop->last)、@endif
                @endforeach
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">商品の状態</div>
            <div class="info-value">{{ $item->condition }}</div>
        </div>

        <h3>コメント ({{ $item->comments->count() }})</h3>
        @foreach ($item->comments as $comment)
        <div class="comment">
            <div class="comment-user">
                <img src="{{ $comment->user->profile_image ? asset('storage/' . $comment->user->profile_image) : asset('images/default-user.png') }}"
                    alt="ユーザー画像"
                    class="comment-user-image">
                <span class="comment-username">{{ $comment->user->username ?? '匿名ユーザー' }}</span>
            </div>
            <p class="comment-inner">{{ $comment->comment }}</p>
        </div>
        @endforeach

        <!-- コメント欄 ログイン済み＝コメント入力、送信　未ログイン＝コメント入力、送信できない-->
        <form method="POST" action="{{ route('item.comment', $item->id) }}" @guest onsubmit="return false;" @endguest>
            @csrf
            <div class="comment-title">商品へのコメント</div>

            <textarea name="comment" placeholder="コメントを入力してください">{{ old('comment') }}</textarea>

            @auth
            @error('comment')<p style="color: red;">{{ $message }}</p>@enderror
            @endauth

            <button class="comment-button" type="submit" @guest disabled @endguest>
                コメントを送信する
            </button>

            @guest
            <p style="color: gray;">※コメントを投稿するにはログインが必要です。</p>
            <a href="{{ route('login') }}" class="login-link">ログインはこちら</a>
            @endguest
        </form>

    </div>
</div>
@endsection