@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品一覧</h1>

    <div class="row">
        @foreach($items as $item)
            <div class="col-md-3 mb-4">
                <div class="card">
                    {{-- 商品画像 --}}
                    @if($item->images->isNotEmpty())
                        <img src="{{ asset('storage/' . $item->images->first()->path) }}" 
                             class="card-img-top" alt="{{ $item->name }}">
                    @endif

                    <div class="card-body">
                        <h5 class="card-title">{{ $item->name }}</h5>
                        <p>¥{{ number_format($item->price) }}</p>

                        {{-- Sold判定 --}}
                        @if($item->purchases->isNotEmpty())
                            <span class="badge bg-danger">Sold</span>
                        @endif

                        {{-- 詳細ページへのリンク --}}
                        <a href="{{ route('items.show', ['item_id' => $item->id]) }}" 
                           class="btn btn-primary">詳細を見る</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection