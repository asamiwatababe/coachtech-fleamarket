<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $tab = request('tab', 'sell');

        if ($tab === 'buy') {
            // 購入した商品
            $items = $user->purchases()->latest()->get();
        } else {
            // 出品した商品（デフォルト）
            $items = $user->items()->latest()->get();
        }

        return view('profile.mypage', compact('user', 'items', 'tab'));
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        if ($request->hasFile('image')) {
            $filename = $request->file('image')->store('profiles', 'public');
            $user->profile_image = $filename;
        }

        $user->username = $request->input('username');
        $user->postal_code = $request->input('postal_code');
        $user->address = $request->input('address');
        $user->building = $request->input('building');
        $user->save();

        return redirect()->route('profile.show')->with('status', 'プロフィールを更新しました。');
    }

    public function profile()
    {
        $user = Auth::user();
        $tab = request('tab', 'sell');

        if ($tab === 'buy') {
            $items = $user->purchases()->latest()->get();
        } else {
            $items = $user->items()->latest()->get();
        }

        return view('profile.profile', compact('user', 'items', 'tab'));
    }

    public function store(ProfileRequest $request)
    {
        $user = Auth::user();

        // 直接usersテーブルに保存
        if ($request->hasFile('image')) {
            $filename = $request->file('image')->store('profiles', 'public');
            $user->profile_image = $filename;
        }

        $user->username = $request->input('username');
        $user->postal_code = $request->input('postal_code');
        $user->address = $request->input('address');
        $user->building = $request->input('building');
        $user->save();

        return redirect()->route('profile.show')->with('status', 'プロフィールを登録しました。');
    }
}
