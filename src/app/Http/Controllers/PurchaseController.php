<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    // 商品購入画面表示（PG06）
    public function create($item_id)
    {
        $item = Item::with(['images', 'categories'])
            ->findOrFail($item_id);

        $user = Auth::user();

        $address = Address::where('user_id', $user->id)->first();

        if (!$address) {
            return redirect()
                ->route('purchase.address', ['item_id' => $item_id])
                ->with('error', '先に住所を登録してください。');
        }


        return view('purchase.create', compact('item', 'user', 'address'));
    }

    // 購入処理
    public function store(Request $request, $item_id)
    {
        $request->validate([
            'payment_method' => 'required',
        ]);

        $item = Item::findOrFail($item_id);

        if ($item->sold) {
            return redirect()->back()->with('error', 'この商品はすでに購入されています。');
        }

        $address = Address::where('user_id', auth()->id())->first();

        $purchase = Purchase::create([
            'user_id'        => auth()->id(),
            'item_id'        => $item->id,
            'address_id'     => $address->id,
            'payment_method' => $request->payment_method,
        ]);

        $item->update(['sold' => true]);

        if ($request->payment_method === 'カード払い') {
            return $this->startStripeCardPayment($purchase);
        }

        if ($request->payment_method === 'コンビニ払い') {
            return $this->startStripeKonbiniPayment($purchase);
        }

        if ($request->payment_method === 'カード払い') {

            return $this->startStripeCardPayment($purchase);

        } elseif ($request->payment_method === 'コンビニ払い') {

            return $this->startStripeKonbiniPayment($purchase);
        }

        return redirect()->route('items.index')->with('success', '購入が完了しました');
    }

    public function startStripeCardPayment($purchase)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $item = $purchase->item;

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success'),
            'cancel_url' => route('purchase.cancel'),
        ]);

        return redirect()->away($session->url);
    }

    public function startStripeKonbiniPayment($purchase)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $item = $purchase->item;

        $session = Session::create([
            'payment_method_types' => ['konbini'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success'),
            'cancel_url' => route('purchase.cancel'),
        ]);

        return redirect()->away($session->url);
    }
    
    // 住所更新処理（FN024）
    public function updateAddress(Request $request, $item_id)
    {
        $request->validate([
            'postal_code' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        $address = Address::updateOrCreate(
            ['user_id' => $user->id],
            [
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building,
            ]
        );

        return redirect()->route('purchase.create', ['item_id' => $item_id])
                        ->with('success', '住所を更新しました');
    }

    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        $address = Address::where('user_id', $user->id)->first();
        return view('purchase.address', compact('item', 'user', 'address'));
            }

    public function success()
    {
        return redirect()->route('items.index')
                        ->with('success', '決済が完了しました');
    }

    public function cancel()
    {
        return redirect()->back()
                        ->with('error', '決済がキャンセルされました');
    }
}
