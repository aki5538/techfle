<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    // ログイン画面表示 (GET /login)
    public function create()
    {
        return view('auth.login');
    }

    // ログイン処理 (POST /login)
    public function store(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        // 認証失敗時のメッセージを仕様書どおりに
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => 'ログイン情報が登録されていません',
            ]);
        }

        // 認証成功時の処理（遷移先はあなたのアプリに合わせて変更）
        return redirect('/');
    }
}
