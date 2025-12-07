<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;

class AddressController extends Controller
{
    // 送付先住所変更画面表示（PG07）
    public function edit($item_id)
    {
        $item = Item::findOrFail($item_id);
        return view('purchase.address', compact('item'));
    }

    // 送付先住所更新処理
    public function update(Request $request, $item_id)
    {
        $purchase = Purchase::where('item_id', $item_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $purchase->update([
            'address' => $request->input('address'),
        ]);

        return redirect()->route('items.show', ['item_id' => $item_id])
                         ->with('success', '送付先住所を変更しました');
    }
}
