@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="profile-form-container">
    <h2 class="form-title">住所の変更</h2>
    <form action="{{ route('purchase.address.update', $item->id) }}" method="POST" class="address-form">
        @csrf

        <div class="form-group">
            <label for="zip_code">郵便番号</label>
            <input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code') }}">
            @error('zip_code')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" name="address" id="address" value="{{ old('address') }}">
            @error('address')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="building">建物名</label>
            <input type="text" name="building" id="building" value="{{ old('building') }}">
            @error('building')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="submit-button">更新する</button>
    </form>
</div>
@endsection