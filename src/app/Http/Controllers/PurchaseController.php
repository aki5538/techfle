<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    // 商品購入画面表示（PG06）
    public function create($item_id)
    {
        $item = Item::with(['images', 'categories'])
            ->findOrFail($item_id);

        return view('purchase.create', compact('item'));
    }

    // 購入処理
    public function store(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        Purchase::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'address' => $request->input('address'),
        ]);

        return redirect()->route('items.show', ['item_id' => $item->id])
                         ->with('success', '購入が完了しました');
    }
}
