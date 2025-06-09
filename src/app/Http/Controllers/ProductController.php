<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Item;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab');
        $keyword = $request->input('keyword');

        if ($tab === 'mylist' && Auth::check()) {
            $likedItems = Auth::user()
            //ユーザーがいいねした商品一覧を取得＆Itemモデル一覧取得
                ->likes()
                ->get()
                ->filter(function ($item) use ($keyword) {
                    return $item->user_id !== Auth::id() &&
                        (!$keyword || str_contains($item->name, $keyword));
                })
                ->values();

            $products = $likedItems;
        } else {
            $query = Item::query();

            if (Auth::check()) {
                $query->where('user_id', '!=', Auth::id());
            }

            if ($request->filled('keyword')) {
                $query->where('name', 'LIKE', "%{$keyword}%");
            }

            $products = $query->get();
        }

        return view('products.index', compact('products', 'tab'));
    }
}
