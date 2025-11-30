<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    // ログイン画面表示 (GET /login)
    public function create()
    {
        return view('auth.login');
    }

    // ログイン処理 (POST /login)
    public function store(Request $request)
    {
        // バリデーション
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 認証試行
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // 成功時はマイページへ
            return redirect()->route('mypage.index');
        }

        // 失敗時はエラーメッセージ付きで戻す
        return back()->withErrors([
            'email' => 'ログイン情報が正しくありません。',
        ])->onlyInput('email');
    }
}
