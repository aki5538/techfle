@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection


@section('header-content')
{{-- ヘッダーUI --}}
<div class="page-header-ui">
    <div class="page-search">
        <input type="text" placeholder="なにをお探しですか？">
    </div>

    <div class="page-links">
        <a href="/login">ログアウト</a>
        <a href="/mypage">マイページ</a>
        <form action="/sell" method="GET">
            <button type="submit" class="sell-btn">出品</button>
        </form>
    </div>
</div>
@endsection

@section('content')
<div class="container">
    {{-- 商品詳細2カラム --}}
    <div class="item-detail">
        @if ($item->images->first())
            <img src="{{ $item->images->first()->path }}"
                class="item-detail-image"
                alt="{{ $item->name }}">
        @endif
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
                <form method="POST" action="{{ route('item.like', $item->id) }}">
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
                    @forelse(($item->categories ?? []) as $category)
                        <span class="category-tag">{{ $category->name }}</span>
                    @empty
                        <span class="category-tag">なし</span>
                    @endforelse
                </div>
            </div>

            {{-- 商品の状態 --}}
            <p class="item-meta-row">
                商品の状態：
                {{
                    [
                        'no-damage' => '傷や汚れなし',
                        'slightly-damaged' => 'やや傷や汚れあり',
                        'damaged' => '傷や汚れあり',
                        'bad' => '状態が悪い'
                    ][$item->status] ?? '不明'
                }}
            </p>
        </div>

        <div class="comments mt-5">
            <h4 class="comments-count-title">コメント（{{ $item->comments->count() }}）</h4>

            @forelse($item->comments as $comment)
                <div class="comment-box">
                    <div class="comment-icon">
                        @if(optional($comment->user)->profile_image)
                            <img src="{{ asset('storage/' . $comment->user->profile_image) }}" 
                                alt="ユーザー画像" class="comment-user-image">
                        @else
                            <img src="{{ asset('images/default-profile.png') }}" 
                                alt="デフォルト画像" class="comment-user-image">
                        @endif
                    </div>

                    <div class="comment-content">
                        <p class="comment-user">{{ $comment->user->name }}</p>

                        <div class="comment-body-wrapper">
                            <p class="comment-body">{{ $comment->comment }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="comment-box empty-comment">
                    <div class="comment-icon default-icon"></div>

                    <div class="comment-content">
                        <p class="comment-user">&nbsp;</p>

                        <div class="comment-body-wrapper">
                            <p class="comment-body">&nbsp;</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- コメントフォーム --}}
        <div class="comment-form mt-4">
            <form action="{{ route('comment.store', ['item_id' => $item->id]) }}" method="POST">
                @csrf
                <textarea name="comment" class="form-control" rows="3"
                    placeholder="コメントを入力（255文字以内）"></textarea>
                @error('comment')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
                <button type="submit" class="btn btn-primary mt-2">コメントを送信する</button>
            </form>
        </div>
    </div>
</div>
@endsection