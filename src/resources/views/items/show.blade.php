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

    {{-- Sold判定 --}}
    @if($item->purchases->isNotEmpty())
        <span class="badge bg-danger">Sold</span>
    @endif

    <p>カテゴリ: 
        @foreach($item->categories as $category)
            {{ $category->name }}
        @endforeach
    </p>

    <p>いいね数: {{ $item->likes->count() }}</p>

    <h3>コメント一覧</h3>
    @foreach($item->comments as $comment)
        <p>{{ $comment->user->name }}: {{ $comment->comment }}</p>
    @endforeach
</div>
@endsection