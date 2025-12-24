@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">送付先住所の変更</h2>

    <div class="card p-4 shadow-sm">

        <form method="POST" action="{{ route('purchase.address.update', ['item_id' => $item->id]) }}">
            @csrf

            {{-- 現在の住所 --}}
            <div class="mb-3">
                <label class="form-label fw-bold">現在の住所</label>
                <p class="form-control-plaintext">{{ $user->address }}</p>
            </div>

            {{-- 新しい住所入力 --}}
            <div class="mb-3">
                <label for="address" class="form-label fw-bold">新しい住所</label>
                <input type="text"
                       name="address"
                       id="address"
                       class="form-control"
                       value="{{ old('address', $user->address) }}"
                       required>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                住所を更新する
            </button>

        </form>

    </div>

</div>
@endsection