<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    // いいね登録
    public function store($item_id)
    {
        Like::firstOrCreate([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
        ]);

        return back();
    }

    // いいね解除
    public function destroy($item_id)
    {
        Like::where('user_id', Auth::id())
            ->where('item_id', $item_id)
            ->delete();

        return back();
    }
}