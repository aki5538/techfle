@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="{{ asset('css/mypage/index.css') }}">
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

{{-- プロフィールヘッダー --}}
<div class="container">
    <div class="mypage-header">
        <div class="profile-image-wrapper">
            @if ($user->profile_image)
                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="プロフィール画像" class="profile-image">
            @else
                <div class="profile-image-placeholder">No Image</div>
            @endif
        </div>

        <div class="profile-info-row">
            <p class="profile-username">{{ $user->name }}</p>

            <a href="{{ route('mypage.profile') }}" class="btn-profile-edit">
                プロフィールを編集
            </a>
        </div>
    </div>
</div>

    {{-- タブ --}}
    <div class="mypage-tabs">
        <a href="{{ route('mypage.index', ['page' => 'sell']) }}"
           class="mypage-tab {{ $page === 'sell' ? 'is-active' : '' }}">
            出品した商品
        </a>

        <a href="{{ route('mypage.index', ['page' => 'buy']) }}"
           class="mypage-tab {{ $page === 'buy' ? 'is-active' : '' }}">
            購入した商品
        </a>
    </div>
    <div class="mypage-tab-border"></div>

    {{-- 商品一覧 --}}
    <div class="mypage-items">
        @foreach ($items as $item)
            <a href="{{ url('/item/' . $item->id) }}" class="mypage-item-card">
                <div class="item-image-wrapper">
                    @if ($item->images->first())
                        <img src="{{ asset('storage/' . $item->images->first()->path) }}" class="item-image" alt="{{ $item->name }}">
                    @else
                        <div class="item-image-placeholder">No Image</div>
                    @endif
                </div>
                <p class="item-name">{{ $item->name }}</p>
            </a>
        @endforeach
    </div>
@endsection