<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    // 商品一覧（トップ画面 & マイリスト）
    public function index(Request $request)
    {
        // 仮の処理：ビューだけ返す
        return view('items.index');
    }

    // 商品詳細
    public function show($item_id)
    {
        // 仮の処理：ビューだけ返す
        return view('items.show', compact('item_id'));
    }
}
