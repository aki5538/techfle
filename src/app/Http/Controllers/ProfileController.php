<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // プロフィール確認画面（PG09〜PG12）
    public function index()
    {
        $user = Auth::user();
        $page = request('page'); // buy / sell / null

        // 出品商品一覧・購入商品一覧をリレーションから取得
        $sellItems = $user->sellItems ?? collect();
        $buyItems  = $user->buyItems ?? collect();

        return view('mypage.index', compact('user', 'page', 'sellItems', 'buyItems'));
    }

    // プロフィール編集画面（PG10）
    public function edit()
    {
        $user = Auth::user();
        return view('mypage.profile', compact('user'));
    }

    // プロフィール保存処理（POST /mypage/profile）
    public function store(ProfileUpdateRequest $request)
    {
        $user = Auth::user();

        // プロフィール画像の保存処理
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('images', 'public');
            $user->profile_image = $path;
        }

        // その他の項目更新
        $user->name        = $request->input('name');
        $user->postal_code = $request->input('postal_code');
        $user->address     = $request->input('address');
        $user->building    = $request->input('building');

        $user->save();

        // 編集後はプロフィール確認画面（/mypage）へ戻る
        return redirect()->route('mypage.index')
                         ->with('success', 'プロフィールを更新しました');
    }
}