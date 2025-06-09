<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use App\Models\Item;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    /**
     * 商品購入画面を表示
     */
    public function show(Item $item)
    {
        $user = Auth::user();

        $address = Address::where('user_id', $user->id)
            ->where('item_id', $item->id)
            ->latest()
            ->first();

        if ($address && !empty($address->zip_code) && !empty($address->address)) {
            return view('purchase.index', [
                'item' => $item,
                'address' => $address, // ← address テーブル
            ]);
        }

        // 変更されていない場合は users テーブルのプロフィール住所を表示
        if (!empty($user->postal_code) && !empty($user->address)) {
            return view('purchase.index', [
                'item' => $item,
                'address' => $user, // ← users テーブル
            ]);
        }

        // どちらも未入力なら住所入力画面へ
        return redirect()->route('purchase.address.edit', $item->id);
    }

    /**
     * 購入処理
     */
    public function store(PurchaseRequest $request, Item $item)
    {
        Auth::user()->purchases()->attach($item->id);
        $item->update(['is_sold' => true]);

        return redirect()->route('products.index')->with('success', '購入が完了しました');
    }

    /**
     * 住所入力画面表示
     */
    public function editAddress(Item $item)
    {
        // 新規住所入力のため、空のフォーム表示
        return view('purchase.address', compact('item'));
    }

    /**
     * 住所保存処理
     */
    public function updateAddress(AddressRequest $request, Item $item)
    {
        // 入力された住所情報を保存
        Address::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'zip_code' => $request->zip_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        // 再び購入画面へ遷移
        return redirect()->route('purchase.show', $item->id);
    }
}
