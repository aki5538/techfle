@extends('layouts.app')

@section('content')
<div class="container">
    <h1>プロフィール設定</h1>

    <form method="POST" action="{{ route('mypage.profile.store') }}">
        @csrf

        <!-- ユーザ名 -->
        <div class="form-group">
            <label for="name">ユーザ名</label>
            <input id="name" type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- メールアドレス -->
        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input id="email" type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- 自己紹介 -->
        <div class="form-group">
            <label for="bio">自己紹介</label>
            <textarea id="bio" name="bio">{{ old('bio', auth()->user()->bio ?? '') }}</textarea>
            @error('bio')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- 保存ボタン -->
        <div class="form-group">
            <button type="submit" class="btn btn-primary">保存する</button>
        </div>
    </form>
</div>
@endsection