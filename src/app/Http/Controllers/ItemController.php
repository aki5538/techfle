<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Like;

class ItemController extends Controller
{
    // 商品一覧（トップ画面 & マイリスト）
    public function index(Request $request)
    {
        $userId = auth()->id();
        $keyword = $request->query('keyword');
        $tab = $request->query('tab');

        //購入済み商品の ID を取得（Sold 判定用）
        $purchasedItemIds = Purchase::pluck('item_id')->toArray();

        //マイリスト（いいねした商品だけ）
        if ($tab === 'mylist' && $userId) {
            $items = Item::whereHas('likes', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->when($keyword, function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                })
                ->get();

            return view('items.index', compact('items', 'purchasedItemIds', 'keyword', 'tab'));
        }

        //通常の商品一覧（未ログインでも表示OK）
        $items = Item::query()
            ->when($userId, function ($q) use ($userId) {
                //自分の商品を除外（仕様書 FN014-4）
                $q->where('user_id', '!=', $userId);
            })
            ->when($keyword, function ($q) use ($keyword) {
                //部分一致検索（仕様書 FN016）
                $q->where('name', 'like', "%{$keyword}%");
            })
            ->get();

        return view('items.index', compact('items', 'purchasedItemIds', 'keyword', 'tab'));
    }

    // 商品詳細（GET: 表示 / POST: いいね登録・解除）
    public function show(Request $request, int $item_id)
    {
        $item = Item::with([
            'purchase',
            'likes',
            'comments.user',
            'categories',
        ])->findOrFail($item_id);

        // POST のときだけ処理
        if ($request->isMethod('post')) {

            if (!Auth::check()) {
                return back();
            }

            $userId = Auth::id();

            // ① いいね処理
            if ($request->has('like')) {
                $alreadyLiked = $item->likes->contains('user_id', $userId);

                if ($alreadyLiked) {
                    Like::where('user_id', $userId)
                        ->where('item_id', $item_id)
                        ->delete();
                } else {
                    Like::create([
                        'user_id' => $userId,
                        'item_id' => $item_id,
                    ]);
                }
            }

            // ② コメント送信処理
            if ($request->has('comment')) {

                $request->validate([
                    'comment' => 'required|max:255',
                ]);

                Comment::create([
                    'user_id' => $userId,
                    'item_id' => $item_id,
                    'comment' => $request->comment,
                ]);
            }

            return back();
        }

        return view('items.show', compact('item'));
    }
}
