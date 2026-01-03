@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="auth-content">
    <h1 class="login-title">ログイン</h1>

    <form method="POST" action="/login">
        @csrf

        <!-- メールアドレス -->
        <div class="form-group">
            <label for="email" class="form-label">メールアドレス</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="form-input">
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- パスワード -->
        <div class="form-group">
            <label for="password" class="form-label">パスワード</label>
            <input id="password" type="password" name="password" required class="form-input">
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- ログインボタン -->
        <div class="form-group">
            <button type="submit" class="login-button">ログインする</button>
        </div>

        <!-- 会員登録リンク -->
        <div class="form-group text-center">
            <a href="{{ route('register') }}" class="register-link">会員登録はこちら</a>
        </div>
    </form>
</div>
@endsection