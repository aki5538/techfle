@extends('layouts.app')

@section('content')
<div class="container">
    <h1>プロフィール</h1>

    <!-- プロフィール画像 -->
    @if($user->profile_image)
        <div class="mb-3">
            <img src="{{ asset('storage/' . $user->profile_image) }}" alt="プロフィール画像" width="120">
        </div>
    @endif

    <!-- ユーザー名 -->
    <p><strong>ユーザー名:</strong> {{ $user->name }}</p>

    <!-- タブ切り替え -->
    <ul class="nav nav-tabs mt-3">
        <li class="nav-item">
            <a class="nav-link {{ $page === null ? 'active' : '' }}" href="{{ route('mypage.index') }}">プロフィール</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $page === 'buy' ? 'active' : '' }}" href="{{ route('mypage.index', ['page' => 'buy']) }}">購入商品一覧</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $page === 'sell' ? 'active' : '' }}" href="{{ route('mypage.index', ['page' => 'sell']) }}">出品商品一覧</a>
        </li>
    </ul>

    <!-- コンテンツ切り替え -->
    <div class="mt-3">
        @if($page === 'buy')
            <h2>購入した商品一覧</h2>
            <ul>
                @forelse($buyItems as $item)
                    <li>{{ $item->name }}</li>
                @empty
                    <li>購入商品はありません</li>
                @endforelse
            </ul>
        @elseif($page === 'sell')
            <h2>出品した商品一覧</h2>
            <ul>
                @forelse($sellItems as $item)
                    <li>{{ $item->name }}</li>
                @empty
                    <li>出品商品はありません</li>
                @endforelse
            </ul>
        @else
            <h2>プロフィール情報</h2>
            <p>ユーザー名: {{ $user->name }}</p>
            <!-- プロフィール画像は上で表示済み -->
        @endif
    </div>

    <!-- 編集画面へのリンク -->
    <div class="mt-3">
        <a href="{{ route('mypage.profile') }}" class="btn btn-secondary">プロフィールを編集する</a>
    </div>
</div>
@endsection