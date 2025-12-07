@extends('layouts.app')

@section('content')
<div class="container">
    <h1>プロフィール設定</h1>

    <!-- 成功メッセージ -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('mypage.profile.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- プロフィール画像 --}}
        <div class="form-group">
            <label for="profile_image">プロフィール画像</label>
            <input id="profile_image" type="file" name="profile_image" accept="image/*">
            
            @if(Auth::check() && Auth::user()->profile_image)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="プロフィール画像" width="120">
                </div>
            @endif

            @error('profile_image')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- ユーザー名 -->
        <div class="form-group">
            <label for="name">ユーザー名</label>
            <input id="name" type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- 郵便番号 -->
        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input id="postal_code" type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code ?? '') }}" required>
            @error('postal_code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- 住所 -->
        <div class="form-group">
            <label for="address">住所</label>
            <input id="address" type="text" name="address" value="{{ old('address', $user->address ?? '') }}" required>
            @error('address')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- 建物名 -->
        <div class="form-group">
            <label for="building">建物名</label>
            <input id="building" type="text" name="building" value="{{ old('building', $user->building ?? '') }}" required>
            @error('building')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- 保存ボタン -->
        <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary">保存する</button>
        </div>
    </form>
</div>
@endsection