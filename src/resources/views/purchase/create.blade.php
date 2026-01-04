@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/purchase/create.css') }}">
@endsection

@section('content')
<div class="container py-4">

    <h2 class="mb-4">商品購入</h2>

    <form method="POST" action="{{ route('purchase.store', ['item_id' => $item->id]) }}">
        @csrf

        <div class="row">

            {{-- 左側：商品情報＋支払い方法＋配送先 --}}
            <div class="col-md-8">

                {{-- 商品情報 --}}
                <div class="d-flex">
                    <img src="{{ $item->img_url }}"
                         alt="{{ $item->name }}"
                         class="product-image">

                    <div class="product-info">
                        <h3 class="product-name">{{ $item->name }}</h3>
                        <p class="product-price">¥{{ number_format($item->price) }}</p>
                    </div>
                </div>

                {{-- Line 18（商品情報の下線） --}}
                <div class="section-divider"></div>

                {{-- 支払い方法タイトル --}}
                <p class="payment-title">支払い方法</p>

                {{-- 支払い方法セレクトボックス --}}
                <select name="payment_method" id="payment_method" class="payment-select">
                    <option value="" selected>選択してください</option>
                    <option value="コンビニ払い">コンビニ払い</option>
                    <option value="カード払い">カード払い</option>
                </select>

                {{-- Line 19（支払い方法の下線） --}}
                <div class="section-divider"></div>

                {{-- 配送先タイトル＋変更する --}}
                <div class="d-flex align-items-center address-row">
                    <p class="address-title">配送先</p>
                    <a href="{{ route('purchase.address', ['item_id' => $item->id]) }}" class="address-edit">変更する</a>
                </div>

                {{-- 住所表示 --}}
                <p class="address-text">
                    {{ $address->postal_code }}<br>
                    {{ $address->address }}
                    {{ $address->building }}
                </p>

                {{-- hidden を追加 --}}
                <input type="hidden" name="address_id" value="{{ $address->id }}">

                {{-- Line 21（住所の下線） --}}
                <div class="section-divider"></div>
            </div>

            {{-- 右側：購入サマリー --}}
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

{{-- 支払い方法を右側に反映 --}}
<script>
document.getElementById('payment_method').addEventListener('change', function() {
    const text = this.options[this.selectedIndex].text;
    document.getElementById('payment-summary').textContent = text;
});
</script>
@endsection