<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    // 商品一覧（トップ画面 & マイリスト）
    public function index(Request $request)
    {
        $userId = auth()->id();

        if ($request->query('tab') === 'mylist') {
            // 商品一覧画面（トップ画面）_マイリスト
            $items = Item::whereHas('likes', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->with(['images', 'purchases'])
                ->get();
        } else {
            // 商品一覧画面（トップ画面）
            $items = Item::with(['images', 'purchases'])
                ->where('user_id', '!=', $userId)
                ->get();
        }

        return view('items.index', compact('items'));
    }

    // 商品詳細
    public function show(int $item_id)
    {
        $item = Item::with([
        'images',
        'categories',
        'likes',
        'comments.user',
        'purchases' // ← Sold判定用に追加
    ])->findOrFail($item_id);

        return view('items.show', compact('item'));
    }
}
