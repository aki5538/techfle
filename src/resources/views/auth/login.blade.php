@extends('layouts.app')

@section('content')
<div class="container">
    <h1>ログイン</h1>

    <form method="POST" action="{{ route('login.store') }}">
        @csrf

        <!-- メールアドレス -->
        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- パスワード -->
        <div class="form-group">
            <label for="password">パスワード</label>
            <input id="password" type="password" name="password" required>
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- ログインボタン -->
        <div class="form-group">
            <button type="submit" class="btn btn-primary">ログイン</button>
        </div>

        <!-- 会員登録画面へのリンク -->
        <div class="form-group">
            <a href="{{ route('register') }}">会員登録はこちら</a>
        </div>
    </form>
</div>
@endsection