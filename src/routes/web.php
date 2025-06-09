<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\LikeController;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;

// 商品一覧画面（ログイン後）
Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// プロフィール設定画面
Route::middleware('auth')->group(function () {
    // マイページ表示
    Route::get('/mypage', [ProfileController::class, 'show'])->name('profile.show');

    // プロフィール設定,プロフィール編集ページ
    Route::get('/mypage/profile', [ProfileController::class, 'profile'])->name('profile.page');

    // プロフィール新規作成（初回）
    Route::post('/mypage/profile', [ProfileController::class, 'store'])->name('profile.store');

    // プロフィール更新（既にある場合）
    Route::put('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
});
// 商品出品画面
Route::middleware('auth')->group(function () {
    Route::get('/sell', [SellController::class, 'create'])->name('sell.create');
    Route::post('/sell', [SellController::class, 'store'])->name('sell.store');
});

// 商品詳細画面
Route::get('/item/{item}', [ItemController::class, 'show'])->name('item.show');

// コメント投稿(ミドルウェアで守る)
// Route::post('/item/{item}/comment', [ItemController::class, 'comment'])->name('item.comment');
Route::post('/item/{item}/comment', [ItemController::class, 'comment'])
    ->middleware('auth')
    ->name('item.comment');

// 購入画面
Route::middleware('auth')->group(function () {
    Route::get('/purchase/{item}', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::post('/purchase/{item}', [PurchaseController::class, 'store'])->name('purchase.store');
});
// いいね機能
Route::post('/item/{item}/like',[LikeController::class, 'store'])->name('item.like');
Route::delete('/item/{item}/like',[LikeController::class, 'destroy'])->name('item.unlike');
// 住所変更画面
Route::middleware('auth')->group(function () {
    Route::get('/purchase/address/{item}', [PurchaseController::class, 'editAddress'])->name('purchase.address.edit');
    Route::post('/purchase/address/{item}', [PurchaseController::class, 'updateAddress'])->name('purchase.address.update');
});
