<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Address;

class AddressController extends Controller
{
    // 送付先住所変更画面表示（PG07）
    public function edit($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();
        $currentAddress = $user->address; // hasOne リレーション前提

        return view('purchase.address', compact('item', 'user', 'currentAddress'));
    }

    // 送付先住所更新処理（FN024）
    public function update(Request $request, $item_id)
    {
        $request->validate([
            'postal_code' => 'required',
            'prefecture'  => 'required',
            'city'        => 'required',
            'block'       => 'required',
            'building'    => 'nullable',
        ]);

        $user = auth()->user();

        // ① addresses テーブルに住所を保存（新規 or 更新）
        $address = Address::updateOrCreate(
            ['user_id' => $user->id], // 1ユーザー1住所
            [
                'postal_code' => $request->postal_code,
                'prefecture'  => $request->prefecture,
                'city'        => $request->city,
                'block'       => $request->block,
                'building'    => $request->building,
            ]
        );

        // ② users.address_id を更新（購入画面に反映させるため）
        $user->update([
            'address_id' => $address->id
        ]);

        // ③ 購入画面に戻る
        return redirect()->route('purchase.create', ['item_id' => $item_id])
                         ->with('success', '住所を更新しました');
    }
}