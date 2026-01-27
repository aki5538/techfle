@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('header-content')
    <form method="GET" action="{{ url('/') }}" class="search-form me-3">
        <input type="text" name="keyword" value="{{ $keyword }}" class="search-input" placeholder="なにをお探しですか？">
    </form>

    @if(Auth::check())
        {{-- ログイン後：ログインの位置にログアウトを置く --}}
        <form method="POST" action="{{ route('logout') }}" class="d-inline me-3">
            @csrf
            <button type="submit" class="header-link">ログアウト</button>
        </form>
    @else
        {{-- 未ログイン：ログイン --}}
        <a href="{{ route('login') }}" class="header-link me-3">ログイン</a>
    @endif

    {{-- この2つはログイン前後で常に表示 --}}
    <a href="{{ route('mypage.index') }}" class="header-link me-3">マイページ</a>
    <a href="{{ route('sell.create') }}" class="btn btn-outline-dark">出品</a>
@endsection

@section('content')
<div class="container">

    <div class="tab-menu">
        <a href="{{ url('/?keyword=' . $keyword) }}" class="tab {{ $tab !== 'mylist' ? 'active' : '' }}">おすすめ</a>
        <a href="{{ url('/?tab=mylist&keyword=' . $keyword) }}" class="tab {{ $tab === 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>

    <div class="row">
        @foreach($items as $item)
            <div class="col-md-4 mb-4">
                <div class="item-card">

                    <a href="{{ route('items.show', $item->id) }}">
                        @php
                            $image = $item->images->first();
                        @endphp

                        @if ($image)
                            @php
                                $path = $image->path;
                                $url = str_starts_with($path, 'http')
                                    ? $path
                                    : '/storage/' . $path;
                            @endphp

                            <img src="{{ $url }}" class="item-image" alt="{{ $item->name }}">
                        @else
                            <div class="no-image-box">No Image</div>
                        @endif
                    </a>

                    @if(in_array($item->id, $purchasedItemIds))
                        <span class="sold-label">Sold</span>
                    @endif

                    <p class="item-name">{{ $item->name }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection