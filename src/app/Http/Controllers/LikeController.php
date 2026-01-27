<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    // いいね登録
    public function store($item_id)
    {
        $user_id = Auth::id();

        // すでにいいねしているか確認
        $like = Like::where('user_id', $user_id)
                    ->where('item_id', $item_id)
                    ->first();

        if ($like) {
            // いいね解除
            $like->delete();
        } else {
            // いいね追加
            Like::create([
                'user_id' => $user_id,
                'item_id' => $item_id,
            ]);
        }

        return back();
    }
}

