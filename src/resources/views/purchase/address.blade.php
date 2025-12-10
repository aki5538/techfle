@extends('layouts.app')

@section('content')
<div class="container">
    <h1>送付先住所変更</h1>

    <h3>{{ $item->name }}</h3>

    <form method="POST" action="{{ route('purchase.address.update', ['item_id' => $item->id]) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="postal_code" class="form-label">郵便番号</label>
            <input type="text" name="postal_code" id="postal_code" class="form-control" 
                   value="{{ old('postal_code', $user->postal_code ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">住所</label>
            <input type="text" name="address" id="address" class="form-control" 
                   value="{{ old('address', $user->address ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="building" class="form-label">建物名</label>
            <input type="text" name="building" id="building" class="form-control" 
                   value="{{ old('building', $user->building ?? '') }}">
        </div>

        <button type="submit" class="btn btn-primary">変更する</button>
    </form>
</div>
@endsection