@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="{{ asset('css/mypage/profile.css') }}">
@endsection

@section('header-content')
    <form method="GET" action="{{ url('/') }}" class="search-form me-3">
        <input type="text" name="keyword" class="search-input" placeholder="なにをお探しですか？">
    </form>

    @if(Auth::check())
        <form method="POST" action="{{ route('logout') }}" class="d-inline me-3">
            @csrf
            <button type="submit" class="header-link">ログアウト</button>
        </form>
    @else
        <a href="{{ route('login') }}" class="header-link me-3">ログイン</a>
    @endif

    <a href="{{ route('mypage.index') }}" class="header-link me-3">マイページ</a>
    <a href="{{ route('sell.create') }}" class="btn btn-outline-dark">出品</a>
@endsection

@section('content')
    <form method="POST" action="{{ route('mypage.profile.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="profile-header">
            <h1 class="profile-title">プロフィール設定</h1>

            <div class="profile-image-block">
                {{-- プロフィール画像表示 --}}
                @if(Auth::check() && Auth::user()->profile_image)
                    <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="プロフィール画像">
                    @else
                    <img src="{{ asset('images/default-profile.png') }}" alt="プロフィール画像" class="profile-image">
                @endif

                {{-- 画像選択ボタン --}}
                <div class="image-upload-wrapper">
                    <label for="profile_image" class="select-image-button">画像を選択する</label>
                    <input type="file" id="profile_image" name="profile_image" accept="image/*" class="hidden-file">
                </div>
                @error('profile_image')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        {{-- 入力欄全体の大枠 --}}
        <div class="form-area">

            {{-- ユーザー名 --}}
            <div class="form-block user-name-block">
                <label for="name" class="form-label">ユーザー名</label>
                <input type="text" id="name" name="name"
                    value="{{ old('name', $user->name) }}"
                    class="form-input">
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- 郵便番号 --}}
            <div class="form-block postal-block">
                <label for="postal_code" class="form-label">郵便番号</label>
                <input type="text" id="postal_code" name="postal_code"
                    value="{{ old('postal_code', $user->postal_code) }}"
                    class="form-input">
                @error('postal_code')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- 住所 --}}
            <div class="form-block address-block">
                <label for="address" class="form-label">住所</label>
                <input type="text" id="address" name="address"
                    value="{{ old('address', $user->address) }}"
                    class="form-input">
                @error('address')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- 建物名 --}}
            <div class="form-block building-block">
                <label for="building" class="form-label">建物名</label>
                <input type="text" id="building" name="building"
                    value="{{ old('building', $user->building) }}"
                    class="form-input">
                @error('building')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="update-button-block">
            <button type="submit" class="update-button">更新する</button>
        </div>
    </form>
@endsection