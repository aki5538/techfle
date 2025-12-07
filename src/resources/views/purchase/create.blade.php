@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品購入</h1>

    <h3>{{ $item->name }}</h3>
    <p>価格: ¥{{ number_format($item->price) }}</p>

    <form method="POST" action="{{ route('purchase.store', ['item_id' => $item->id]) }}">
        @csrf
        <div class="mb-3">
            <label for="address" class="form-label">送付先住所</label>
            <input type="text" name="address" id="address" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">購入する</button>
    </form>
</div>
@endsection