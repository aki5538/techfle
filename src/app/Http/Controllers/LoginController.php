<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;

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
        // バリデーション済みデータを取得
        $credentials = $request->validated();

        // 認証試行
        if (Auth::attempt($credentials)) {
            // セッション再生成（セキュリティ対策）
            $request->session()->regenerate();

            // 成功時はマイページへ
            return redirect()->route('mypage.profile');
        }

        // 失敗時はエラーメッセージを返す
        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ])->onlyInput('email');
    }
}
