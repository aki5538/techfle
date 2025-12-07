<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;

class ProfileController extends Controller
{
    // プロフィール画面表示（PG09〜PG12）
    public function index(Request $request)
    {
        $user = auth()->user();

        // ログインしていない場合はログイン画面へリダイレクト
        if (!$user) {
            return redirect()->route('login')->with('error', 'ログインしてください');
        }

        $page = $request->query('page');

        if ($page === 'buy') {
            // 購入した商品一覧（PG11）
            $buyItems = Purchase::where('user_id', $user->id)
                ->with('item.images')
                ->get()
                ->pluck('item');
            $sellItems = collect();
        } elseif ($page === 'sell') {
            // 出品した商品一覧（PG12）
            $sellItems = Item::where('user_id', $user->id)
                ->with('images')
                ->get();
            $buyItems = collect();
        } else {
            // 通常プロフィール画面（PG09）
            $buyItems = collect();
            $sellItems = collect();
        }
        
        return view('mypage.index', compact('user', 'page', 'buyItems', 'sellItems'));
    }

    // プロフィール編集画面表示（PG10）
    public function edit()
    {
        $user = auth()->user();
        return view('mypage.profile', compact('user'));
    }

    // プロフィール保存処理（PG10）
    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'postal_code' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile', 'public');
            $user->profile_image = $path;
        }

        $user->name = $validated['name'];
        $user->postal_code = $validated['postal_code'];
        $user->address = $validated['address'];
        $user->building = $validated['building'] ?? '';
        $user->save();

        return redirect()->route('mypage.index')->with('success', 'プロフィールを更新しました');
    }
}