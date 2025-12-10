@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $item->name }}</h1>

    {{-- 商品画像 --}}
    @foreach($item->images as $image)
        <img src="{{ asset('storage/' . $image->path) }}" 
             alt="{{ $item->name }}" class="img-fluid mb-3">
    @endforeach

    <p>{{ $item->description }}</p>
    <p>価格: ¥{{ number_format($item->price) }}</p>

    {{-- ブランド名・商品の状態 --}}
    <p>ブランド名: {{ $item->brand }}</p>
    <p>商品の状態: {{ $item->status }}</p>

    {{-- Sold判定 --}}
    @if($item->purchases->isNotEmpty())
        <span class="badge bg-danger">Sold</span>
    @endif

    {{-- カテゴリ --}}
    <p>カテゴリ: 
        @foreach($item->categories as $category)
            {{ $category->name }}
        @endforeach
    </p>

    {{-- いいね数とボタン --}}
    <p>いいね数: {{ $item->likes->count() }}</p>
    <form method="POST" action="{{ route('items.like', $item->id) }}">
        @csrf
        <button type="submit" class="btn btn-outline-primary">いいねする</button>
    </form>

    {{-- 購入ボタン（未購入時のみ表示） --}}
    @if($item->purchases->isEmpty())
        <a href="{{ route('purchase.index', $item->id) }}" class="btn btn-success mt-2">
            購入する
        </a>
    @endif

    {{-- コメント一覧 --}}
    <h3 class="mt-4">コメント一覧 ({{ $item->comments->count() }})</h3>
    @foreach($item->comments as $comment)
        <p>{{ $comment->user->name }}: {{ $comment->comment }}</p>
    @endforeach

    {{-- コメント投稿フォーム（ログインユーザーのみ） --}}
    @auth
    <form method="POST" action="{{ route('items.comment', $item->id) }}" class="mt-3">
        @csrf
        <div class="form-group">
            <label for="comment">コメント</label>
            <textarea id="comment" name="comment" class="form-control" maxlength="255" required></textarea>
            @error('comment')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary mt-2">送信</button>
    </form>
    @endauth
</div>
@endsection