<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function store(RegisterRequest $request)
    {
        // バリデーション済みデータを取得
        $validated = $request->validated();
        // ユーザー登録処理
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // メール送信のトリガー
        event(new Registered($user));
        // 登録後にログインさせる（Breezeと同じ挙動）
        Auth::login($user);

        // 認証画面へ
        return redirect()->route('verification.notice');
    }
}
