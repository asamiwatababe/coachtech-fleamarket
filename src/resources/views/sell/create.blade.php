<!-- 商品出品画面 -->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="sell-container">
    <h2 class="sell-title">商品の出品</h2>
    <form action="{{ route('sell.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- 商品画像 -->
        <div class="form-group">
            <label for="image" class="image-label">商品画像</label>
            <label for="image" class="image-upload-area">
                <span>画像を選択する</span>
                <!-- プレビュー画像を表示する枠 -->
                <div class="image-preview">
                    <img id="preview" src="#" alt="プレビュー画像" style="max-width: 200px; display: none;">
                </div>
            </label>

            <!-- ファイル選択 -->
            <input type="file" id="image" name="image" accept=".jpeg,.png" class="hidden-file" onchange="previewImage(event)">

            @error('image')<p class="error">{{ $message }}</p>@enderror
        </div>

        <!-- 商品の詳細見出し -->
        <div class="product-detail-heading">
            <h3>商品の詳細</h3>
        </div>

        <div class="form-group">
            <label>カテゴリー</label>
            <div class="category-tags">
                @foreach($categories as $category)
                <input type="checkbox" name="category_ids[]" id="cat-{{ $loop->index }}" value="{{ $category->id }}"
                    {{ (is_array(old('category_ids')) && in_array($category->id, old('category_ids'))) ? 'checked' : '' }}>
                <label for="cat-{{ $loop->index }}">{{ $category->name }}</label>
                @endforeach
            </div>
            @error('category_ids')<p class="error">{{ $message }}</p>@enderror
        </div>

        <!-- 商品の状態 -->
        <div class="form-group">
            <label>商品の状態</label>
            <select name="condition">
                <option value="" selected disabled hidden>-- 選択してください --</option>
                <option value="良好">良好</option>
                <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                <option value="状態が悪い">状態が悪い</option>
            </select>
            @error('condition')<p class="error">{{ $message }}</p>@enderror
        </div>

        <!-- 商品の詳細見出し -->
        <div class="product-detail-heading">
            <h3>商品名と説明</h3>
        </div>

        <!-- 商品名 -->
        <div class="form-group">
            <label>商品名</label>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name')<p class="error">{{ $message }}</p>@enderror
        </div>

        <!-- ブランド名 -->
        <div class="form-group">
            <label>ブランド名</label>
            <input type="text" name="brand" value="{{ old('brand') }}">
        </div>

        <!-- 商品説明 -->
        <div class="form-group">
            <label>商品の説明</label>
            <textarea name="description">{{ old('description') }}</textarea>
            @error('description')<p class="error">{{ $message }}</p>@enderror
        </div>

        <!-- 価格 -->
        <div class="form-group">
            <label>販売価格</label>
            <div class="price-input">
                <span class="yen-symbol">¥</span>
                <input type="text" name="price" value="{{ old('price') }}">
            </div>
            @error('price')<p class="error">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="submit-btn">出品する</button>
    </form>
</div>

<script>
    function previewImage(event) {
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function() {
            const preview = document.getElementById('preview');
            preview.src = reader.result;
            preview.style.display = 'block';
        };

        if (file) {
            reader.readAsDataURL(file);
        }
    }
</script>

@endsection