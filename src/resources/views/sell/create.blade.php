@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品出品</h1>

    <form method="POST" action="{{ route('sell.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- 商品名 --}}
        <div class="mb-3">
            <label for="name" class="form-label">商品名</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        {{-- ブランド名 --}}
        <div class="mb-3">
            <label for="brand" class="form-label">ブランド名</label>
            <input type="text" name="brand" id="brand" class="form-control">
        </div>

        {{-- 価格 --}}
        <div class="mb-3">
            <label for="price" class="form-label">価格</label>
            <input type="number" name="price" id="price" class="form-control" required>
        </div>

        {{-- 商品説明 --}}
        <div class="mb-3">
            <label for="description" class="form-label">商品説明</label>
            <textarea name="description" id="description" class="form-control" required></textarea>
        </div>

        {{-- 商品の状態 --}}
        <div class="mb-3">
            <label for="status" class="form-label">商品の状態</label>
            <select name="status" id="status" class="form-select" required>
                <option value="新品">新品</option>
                <option value="良好">良好</option>
                <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                <option value="状態が悪い">状態が悪い</option>
            </select>
        </div>

        {{-- カテゴリ複数選択 --}}
        <div class="mb-3">
            <label for="categories" class="form-label">カテゴリ</label>
            <select name="categories[]" id="categories" class="form-select" multiple required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- 商品画像 --}}
        <div class="mb-3">
            <label for="images" class="form-label">商品画像</label>
            <input type="file" name="images[]" id="images" class="form-control" multiple>
        </div>

        <button type="submit" class="btn btn-success">出品する</button>
    </form>
</div>
@endsection