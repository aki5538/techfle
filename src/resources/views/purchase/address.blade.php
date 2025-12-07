@extends('layouts.app')

@section('content')
<div class="container">
    <h1>送付先住所変更</h1>

    <h3>{{ $item->name }}</h3>

    <form method="POST" action="{{ route('purchase.address.update', ['item_id' => $item->id]) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="address" class="form-label">新しい住所</label>
            <input type="text" name="address" id="address" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">変更する</button>
    </form>
</div>
@endsection