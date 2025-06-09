<?php

namespace App\Http\Controllers;
use App\Models\Category;


use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Controllers\Controller;

class SellController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('sell.create', compact('categories'));    }

    public function store(ExhibitionRequest $request)
    {
        $filename = $request->file('image')->store('items', 'public');

        // 商品を登録
        $item = Item::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'brand' => $request->brand,
            'image_url' => $filename,
            'condition' => $request->condition,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        // カテゴリーの紐づけ（多対多）
        if ($request->filled('category_ids')) {
            $item->categories()->attach($request->category_ids);
        }

        return redirect()->route('profile.show', ['tab' => 'sell'])->with('status', '商品を出品しました');
    }
}
