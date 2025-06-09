<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>フリマアプリ</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('css')
</head>

<body>
    <header class="toppage-header">
        <div class="toppage-header-container">
            <div class="toppage-header-icon">
                <a href="{{ route('products.index') }}">
                    <img src="{{ asset('images/items/logo.svg') }}" alt="COACHTECHロゴ" height="30">
                </a>
            </div>
            <!-- 商品名検索 -->
            <div class="toppage-header-search">
                <form method="GET" action="{{ route('products.index') }}">
                    <input type="text" name="keyword" placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
                    @if(request('tab'))
                    <input type="hidden" name="tab" value="{{ request('tab') }}">
                    @endif
                </form>
            </div>

            <div class="toppage-header-nav">
                <nav>
                    @auth
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-link">ログアウト</button>
                    </form>
                    <a href="{{ route('profile.show') }}">マイページ</a>
                    <a href="{{ route('sell.create') }}" class="toppage-header-button">出品</a>
                    @endauth
                    <!-- ログインしてないときの表示 -->
                    @guest
                    <a href="{{ route('login') }}" class="toppage-header-button">ログイン</a>
                    <a href="{{ route('profile.page') }}">マイページ</a>
                    <a href="{{ route('sell.create') }}" class="toppage-header-button">出品</a>
                    @endguest
                </nav>
            </div>
        </div>
    </header>

    <div class="container">
        @yield('content')
    </div>
</body>

</html>