<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    // 商品購入画面表示（PG06）
    public function create($item_id)
    {
        $item = Item::with(['images', 'categories'])
            ->findOrFail($item_id);

        // 仕様書：初期住所はプロフィール画面で登録済みの住所
        $user = Auth::user();

        return view('purchase.create', compact('item', 'user'));
    }

    // 購入処理
    public function store(Request $request, $item_id)
    {
        // バリデーション（仕様書：支払い方法は必須）
        $request->validate([
            'payment_method' => 'required',
        ]);

        // 商品取得
        $item = Item::findOrFail($item_id);

        // すでに売れていたら購入不可（仕様書の前提）
        if ($item->sold) {
            return redirect()->back()->with('error', 'この商品はすでに購入されています。');
        }

        // 購入情報を保存（仕様書：住所はプロフィールの住所を使用）
        Purchase::create([
            'user_id'        => auth()->id(),
            'item_id'        => $item->id,
            'address_id'        => auth()->user()->address_id,
            'payment_method' => $request->payment_method,  // ← Stripe連携前提
        ]);

        // 商品を sold に更新（仕様書：購入後は sold 表示）
        $item->update(['sold' => true]);

        // ⑥ 支払い方法ごとの Stripe 遷移（ルート追加なし）
        if ($request->payment_method === 'カード払い') {

            // Stripe カード決済へ直接リダイレクト
            return $this->startStripeCardPayment($purchase);

        } elseif ($request->payment_method === 'コンビニ払い') {

            // Stripe コンビニ決済へ直接リダイレクト
            return $this->startStripeKonbiniPayment($purchase);
        }

        // 万が一どちらでもない場合（通常は起きない）
        return redirect()->route('items.index')->with('success', '購入が完了しました');
    }
    
    // 住所更新処理（FN024）
    public function updateAddress(Request $request, $item_id)
    {
        $request->validate([
            'address' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        // プロフィール住所を更新
        $user->address = $request->address;
        $user->save();

        // 購入画面へ戻る
        return redirect()->route('purchase.create', ['item_id' => $item_id])
                        ->with('success', '住所を更新しました');
    }

    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        return view('purchase.address', compact('item', 'user'));
    }
}
