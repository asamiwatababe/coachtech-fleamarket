<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;

class ItemController extends Controller
{
    // 商品表示
    public function show(Item $item)
    {
        $item->load('user', 'categories', 'comments.user');

        return view('items.show', compact('item'));
    }

    // コメント処理
    public function comment(CommentRequest $request, Item $item)
    {
        // バリデーション済みのデータ取得
        $validated = $request->validated();

        $item->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
        ]);

        return back()->with('status', 'コメントを投稿しました。');
    }
}
