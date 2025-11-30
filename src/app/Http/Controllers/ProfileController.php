<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // プロフィール画面（/mypage）
    public function index()
    {
        $page = request('page'); // buy / sell / null
        return view('mypage.index', compact('page'));
    }

    // プロフィール編集画面（/mypage/profile）
    public function edit()
    {
        return view('mypage.profile');
    }

    // プロフィール保存処理（POST /mypage/profile）
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'bio' => ['nullable', 'string'],
        ]);

        $user = Auth::user();
        $user->update($validated);

        return redirect()->route('mypage.index');
    }
}

