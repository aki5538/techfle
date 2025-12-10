@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品購入</h1>

    <h3>{{ $item->name }}</h3>

    {{-- 商品画像 --}}
    @if($item->images->isNotEmpty())
        @foreach($item->images as $image)
            <img src="{{ asset('storage/' . $image->path) }}" 
                 alt="{{ $item->name }}" class="img-fluid mb-3">
        @endforeach
    @endif

    <p>価格: ¥{{ number_format($item->price) }}</p>

    <form method="POST" action="{{ route('purchase.store', ['item_id' => $item->id]) }}">
        @csrf

        {{-- 支払い方法選択 --}}
        <div class="mb-3">
            <label for="payment_method" class="form-label">支払い方法</label>
            <select name="payment_method" id="payment_method" class="form-select" required>
                <option value="convenience">コンビニ支払い</option>
                <option value="card">カード支払い</option>
            </select>
        </div>

        {{-- 送付先住所 --}}
        <div class="mb-3">
            <label for="address" class="form-label">送付先住所</label>
            <input type="text" name="address" id="address" class="form-control" 
                   value="{{ $user->address ?? '' }}" required>
            <a href="{{ route('purchase.address', ['item_id' => $item->id]) }}" class="btn btn-link">
                住所を変更する
            </a>
        </div>

        <button type="submit" class="btn btn-success">購入する</button>
    </form>
</div>
@endsection