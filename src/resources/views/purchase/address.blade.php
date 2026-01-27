@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="{{ asset('css/purchase/address.css') }}">
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

<div class="address-container">

    <h2 class="address-title">住所の変更</h2>

    <form method="POST" action="{{ route('purchase.address.update', ['item_id' => $item->id]) }}" class="address-form">
        @csrf
        @method('PUT')

        <div class="form-block">
            <label class="form-label">郵便番号</label>
            <input type="text" name="postal_code" class="form-input"
                value="{{ old('postal_code', $address->postal_code ?? '') }}" required>
        </div>

        <div class="form-block">
            <label class="form-label">住所</label>
            <input type="text" name="address" class="form-input"
                value="{{ old('address', $address->address ?? '') }}" required>
        </div>

        <div class="form-block">
            <label class="form-label">建物名</label>
            <input type="text" name="building" class="form-input"
               value="{{ old('building', $address->building ?? '') }}">
        </div>

        <button type="submit" class="update-btn">
            <span class="update-btn-text">更新する</span>
        </button>

    </form>
</div>
@endsection