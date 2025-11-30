@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="register-container">
    <h1 class="register-title">会員登録</h1>

    <form method="POST" action="{{ route('register.store') }}">
        @csrf

        <!-- ユーザ名 -->
        <div class="form-group">
            <label for="name">ユーザ名</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- メールアドレス -->
        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}">
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- パスワード -->
        <div class="form-group">
            <label for="password">パスワード</label>
            <input id="password" type="password" name="password">
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- 確認用パスワード -->
        <div class="form-group">
            <label for="password_confirmation">確認用パスワード</label>
            <input id="password_confirmation" type="password" name="password_confirmation">
            @error('password_confirmation')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- 登録ボタン -->
        <div class="register-actions">
            <button type="submit" class="register-submit">登録する</button>
        </div>

        <!-- ログイン画面へのリンク -->
        <div class="login-link">
            <a href="{{ route('login') }}" class="login-link-text">ログインはこちら</a>
        </div>
    </form>
</div>
@endsection