<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Like;
use App\Models\Comment;
use App\Models\ItemImage;

class ItemController extends Controller
{
    // 商品一覧（トップ画面 & マイリスト）
    public function index(Request $request)
    {
        $userId = optional(auth()->user())->id;

        $keyword = $request->query('keyword');
        $tab = $request->query('tab');

        $purchasedItemIds = Purchase::pluck('item_id')->toArray();

        if ($tab === 'mylist' && $userId) {
            $items = Item::with('images')
                ->whereHas('likes', fn($q) => $q->where('user_id', $userId))
                ->when($keyword, fn($q) => $q->where('name', 'like', "%{$keyword}%"))
                ->get();

            return view('items.index', compact('items', 'purchasedItemIds', 'keyword', 'tab'));
        }

        // 通常一覧（自分の商品は除外）
        $items = Item::with('images')
            ->when($userId, fn($q) => $q->where('user_id', '!=', $userId))
            ->when($keyword, fn($q) => $q->where('name', 'like', "%{$keyword}%"))
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

        if ($request->isMethod('post')) {

            if (!Auth::check()) {
                return back();
            }

            $userId = Auth::id();

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

            return back();
        }

        $comments = Comment::where('item_id', $item->id)->get();

        return view('items.show', compact('item', 'comments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'status' => 'required|string',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'images' => 'required',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $item = Item::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'brand' => $request->brand,
            'description' => $request->description,
            'price' => $request->price,
            'status' => $request->status,
        ]);

        $item->categories()->sync($request->categories);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('items', 'public');

                ItemImage::create([
                    'item_id' => $item->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('items.index')
            ->with('success', '商品を登録しました');
    }
}
