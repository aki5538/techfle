<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    public function store(RegisterRequest $request)
    {
        // バリデーション済みデータを取得
        $validated = $request->validated();
        // ユーザー登録処理
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // 登録後はログイン画面へリダイレクト
        return redirect()->route('mypage.profile');
    }
}
