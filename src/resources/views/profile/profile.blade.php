<!-- プロフィール設定と編集画面 -->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection


@section('content')
<div class="form-container">
    <h2 class="form-title">プロフィール設定</h2>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- プロフィール画像 -->
        <div class="profile-image-wrapper">
            <label for="image-upload" class="upload-label">
                <img
                    src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : asset('images/default-user.png') }}"
                    alt=""
                    class="profile-image">
                <div class="upload-button">画像を選択する</div>
                <input type="file" id="image-upload" name="image" accept=".jpeg,.png" class="hidden-file">
            </label>
            @error('image')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label>ユーザー名</label>
            <input type="text" name="username" value="{{ old('username', Auth::user()->username) }}">
            @error('username')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label>郵便番号</label>
            <input type="text" name="postal_code" value="{{ old('postal_code', Auth::user()->postal_code) }}">
            @error('postal_code')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label>住所</label>
            <input type="text" name="address" value="{{ old('address', Auth::user()->address) }}">
            @error('address')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label>建物名</label>
            <input type="text" name="building" value="{{ old('building', Auth::user()->building) }}">
        </div>

        <button type="submit" class="submit-btn">更新する</button>
    </form>
</div>

<script>
    document.getElementById('image-upload').addEventListener('change', function(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const imgElement = document.querySelector('.profile-image');
            imgElement.src = reader.result;
        };
        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    });
</script>
@endsection