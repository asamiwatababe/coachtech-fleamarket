@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<form action="{{ route('purchase.store', $item->id) }}" method="POST" class="purchase-form">
    @csrf

    <div class="purchase-container">
        <div class="purchase-left">
            <div class="product-box">
                <img
                    src="{{ Str::startsWith($item->image_url, 'images/') ? asset($item->image_url) : asset('storage/' . $item->image_url) }}"
                    alt="{{ $item->name }}">
                <div class="product-name-price">
                    <div class="product-name">{{ $item->name }}</div>
                    <div class="product-price">￥{{ number_format($item->price) }}</div>
                </div>
            </div>

            <!-- 支払い方法 -->
            <div class="form-section">
                <label for="payment_method">支払い方法</label>
                <select name="payment_method" id="payment_method">
                    <option value="コンビニ払い" {{ old('payment_method') == 'コンビニ払い' ? 'selected' : '' }}>コンビニ払い</option>
                    <option value="カード払い" {{ old('payment_method') == 'カード払い' ? 'selected' : '' }}>カード払い</option>
                </select>
                @error('payment_method') <p class="error">{{ $message }}</p> @enderror
            </div>

            <!-- 配送先 -->
            <div class="form-section">
                <div class="address-title">
                    <label>配送先</label>
                    <a href="{{ route('purchase.address.edit', $item->id) }}" class="change-link">変更する</a>
                </div>
                <div class="address-box">
                    〒 {{ $address->zip_code ?? $address->postal_code }}<br>
                    {{ $address->address }}<br>
                    {{ $address->building ?? '' }}
                </div>

                <input type="hidden" name="address_id" value="{{ $address->id ?? '' }}">
            </div>

        </div>

        <!-- 右側：購入要約 -->
        <div class="purchase-summary-container">
            <div class="purchase-summary">
                <div class="summary-row">
                    <div class="summary-label">商品代金</div>
                    <div class="summary-value">￥{{ number_format($item->price) }}</div>
                </div>
                <div class="summary-row">
                    <div class="summary-label">支払い方法</div>
                    <div class="summary-value" id="display_payment_method">
                        {{ old('payment_method', 'コンビニ払い') }}
                    </div>
                </div>
            </div>
            <button type="submit" class="purchase-button">購入する</button>
        </div>
    </div>
</form>

<!-- 支払い方法リアルタイム表示 -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('payment_method');
        const display = document.getElementById('display_payment_method');

        select.addEventListener('change', function() {
            display.textContent = select.value;
        });
    });
</script>
@endsection