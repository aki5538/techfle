<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class SellController extends Controller
{
    // 出品画面表示（PG08）
    public function create()
    {
        return view('sell.create');
    }

    // 出品処理
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'brand'       => 'nullable|string|max:255',
            'price'       => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status'      => 'required|string',
            'categories'  => 'required|array',
            'categories.*'=> 'string',
            'images.*'    => 'image|mimes:jpg,jpeg,png|max:2048', // 応用部分（残す）
        ]);

        // 商品登録（画像以外）
        $item = Item::create([
            'user_id'     => auth()->id(),
            'name'        => $validated['name'],
            'brand'       => $validated['brand'] ?? '',
            'price'       => $validated['price'],
            'description' => $validated['description'] ?? '',
            'status'      => $validated['status'],
        ]);
        
        $item->categories()->sync($validated['categories']);

        // 画像保存
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('items', 'public');
                $item->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('items.show', ['item_id' => $item->id])
                         ->with('success', '商品を出品しました');
    }
}