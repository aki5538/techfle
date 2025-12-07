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
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $item = Item::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'] ?? '',
        ]);

        // 画像保存
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('images', 'public');
                $item->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('items.show', ['item_id' => $item->id])
                         ->with('success', '商品を出品しました');
    }
}
