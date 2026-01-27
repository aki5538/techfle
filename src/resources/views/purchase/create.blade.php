@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/purchase/create.css') }}">
@endsection

@section('content')
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

<div class="container py-4">

    <h2 class="mb-4">商品購入</h2>

    <form method="POST" action="{{ route('purchase.store', ['item_id' => $item->id]) }}">
        @csrf

        <div class="row">
            <div class="col-md-8">
                <div class="d-flex">
                    @if ($item->images->first())
                        <img src="{{ $item->images->first()->path }}"
                            alt="{{ $item->name }}"
                            class="product-image">
                    @else
                        <div class="product-image-placeholder">No Image</div>
                    @endif

                    <div class="product-info">
                        <h3 class="product-name">{{ $item->name }}</h3>
                        <p class="product-price">¥{{ number_format($item->price) }}</p>
                    </div>
                </div>

                <div class="section-divider"></div>

                <p class="payment-title">支払い方法</p>

                <select name="payment_method" id="payment_method" class="payment-select">
                    <option value="" selected>選択してください</option>
                    <option value="コンビニ払い">コンビニ払い</option>
                    <option value="カード払い">カード払い</option>
                </select>

                <div class="section-divider"></div>

                <div class="d-flex align-items-center address-row">
                    <p class="address-title">配送先</p>
                    <a href="{{ route('purchase.address', ['item_id' => $item->id]) }}" class="address-edit">変更する</a>
                </div>

                <p class="address-text">
                    {{ $address->postal_code }}<br>
                    {{ $address->address }}
                    {{ $address->building }}
                </p>

                <input type="hidden" name="address_id" value="{{ $address->id }}">

                <div class="section-divider"></div>
            </div>

            <div class="col-md-4">
                <div class="purchase-summary">

                    <div class="summary-row">
                        <div class="summary-label">商品代金</div>
                        <div class="summary-value">¥{{ number_format($item->price) }}</div>
                    </div>

                    <div class="summary-row">
                        <div class="summary-label">支払い方法</div>
                        <div class="summary-value" id="payment-summary">選択してください</div>
                    </div>

                    <button type="submit" class="purchase-button">購入する</button>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
document.getElementById('payment_method').addEventListener('change', function() {
    const text = this.options[this.selectedIndex].text;
    document.getElementById('payment-summary').textContent = text;
});
</script>
@endsection