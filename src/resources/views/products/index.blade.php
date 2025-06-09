@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endsection

@section('content')
@php use Illuminate\Support\Str; @endphp

<div class="product-list-header">
    <a href="{{ route('products.index', ['tab' => null]) }}" class="{{ $tab !== 'mylist' ? 'active' : '' }}">おすすめ</a>
    <a href="{{ route('products.index', ['tab' => 'mylist', 'keyword' => request('keyword')]) }}"
        class="{{ request('tab') === 'mylist' ? 'active' : '' }}">
        マイリスト
    </a>
</div>

<!-- 未ログインでマイリストを開いた場合は何も表示しない -->
<div class="product-list">
    @if ($tab === 'mylist' && Auth::guest())
    @else
    @forelse ($products as $product)
    <div class="product-item">
        <a href="{{ route('item.show', $product->id) }}">
            @php
            $imagePath = Str::startsWith($product->image_url, 'images/')
            ? asset($product->image_url)
            : asset('storage/' . $product->image_url);
            @endphp
            <img src="{{ $imagePath }}" alt="{{ $product->name }}">

            @if ($product->is_sold)
            <span class="sold-label">sold</span>
            @endif
            <div class="product-item-name">{{ $product->name }}</div>
        </a>
    </div>
    @empty
    <p>表示する商品がありません。</p>
    @endforelse
    @endif
</div>
@endsection