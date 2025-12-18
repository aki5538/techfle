@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')
<div class="container">
    {{-- ヘッダーUI --}}
    <div class="page-header-ui">
        <div class="page-search">
            <input type="text" placeholder="なにをお探しですか？">
        </div>

        <div class="page-links">
            <a href="/login">ログイン</a>
            <a href="/mypage">マイページ</a>
            <form action="/sell" method="GET">
                <button type="submit" class="sell-btn">出品</button>
            </form>
        </div>
    </div>

    {{-- 商品詳細2カラム --}}
    <div class="item-detail">
        <img src="{{ $item->img_url }}" class="item-detail-image" alt="{{ $item->name }}">
    </div>

    <div class="item-info">
        <h2>{{ $item->name }}</h2>
        <p class="brand">ブランド：{{ $item->brand ?? 'なし' }}</p>
        <p class="price">¥{{ number_format($item->price) }}</p>

        <div class="likes-comments">
            <div class="likes">
                @php
                    $liked = auth()->check() && $item->likes->contains('user_id', auth()->id());
                @endphp
                <form method="POST" action="{{ url('/item/' . $item->id) }}">
                    @csrf
                    <button type="submit" name="like" value="1" style="border:none; background:none; padding:0;">
                        @if ($liked)
                            <img src="{{ asset('images/ハートロゴ_ピンク.png') }}" class="like-icon-img">
                        @else
                            <img src="{{ asset('images/ハートロゴ_デフォルト.png') }}" class="like-icon-img">
                        @endif
                    </button>
                </form>
                <span>{{ $item->likes->count() }}</span>
            </div>

            <div class="comments-count">
                <img src="{{ asset('images/ふきだしロゴ.png') }}" class="comment-icon-img">
                <span>{{ $item->comments->count() }}</span>
            </div>
        </div>

        <div class="purchase-wrapper">
            <a href="{{ route('purchase.create', ['item_id' => $item->id]) }}" class="purchase-btn">
                購入手続きへ
            </a>
        </div>

        <h2 class="description-title">商品説明</h2>
        <p class="description-body">{{ $item->description }}</p>

       <div class="item-meta">
            <h4 class="item-meta-title">商品の情報</h4>

            {{-- カテゴリー行（横並び） --}}
            <div class="category-wrapper">
                <span class="category-title">カテゴリー</span>

                <div class="category-tags">
                    @forelse($item->categories as $category)
                        <span class="category-tag">{{ $category->name }}</span>
                    @empty
                        <span class="category-tag">なし</span>
                    @endforelse
                </div>
            </div>

            {{-- 商品の状態 --}}
            <p class="item-meta-row">商品の状態：{{ $item->status }}</p>
        </div> 

        <div class="comments mt-5">
            <h4>コメント（{{ $item->comments->count() }}件）</h4>
            @foreach($item->comments as $comment)
                <div class="comment-box">
                    <p class="comment-user">{{ $comment->user->name }}</p>
                    <p class="comment-body">{{ $comment->comment }}</p>
                </div>
            @endforeach
        </div>

        @auth
            <div class="comment-form mt-4">
                <form action="{{ url('/item/' . $item->id) }}" method="POST">
                    @csrf
                    <textarea name="comment" class="form-control" rows="3"
                        placeholder="コメントを入力（255文字以内）"></textarea>
                    @error('comment')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                    <button type="submit" class="btn btn-primary mt-2">コメントを送信</button>
                </form>
            </div>
        @endauth
    </div>
</div>
@endsection