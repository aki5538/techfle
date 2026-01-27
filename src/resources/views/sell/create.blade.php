@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="{{ asset('css/sell.css?v=2') }}">
@endsection

@section('header-content')
    <div class="search-form me-3">
        <input type="text" name="keyword" class="search-input" placeholder="なにをお探しですか？">
    </div>

    @if(Auth::check())
        <form method="POST" action="{{ route('logout') }}" class="d-inline me-3">
            @csrf
            <button type="submit" class="header-link">ログアウト</button>
        </form>
    @else
        <a href="{{ route('login') }}" class="header-link me-3">ログイン</a>
    @endif

    <a href="{{ route('mypage.index') }}" class="header-link me-3">マイページ</a>
    <a href="{{ route('sell.create') }}" class="btn btn-outline-dark">出品</a>
@endsection


@section('content')
<div class="container">
    <h1 class="page-title">商品の出品</h1>

    <form method="POST" action="{{ route('sell.store') }}" enctype="multipart/form-data">
        @csrf

        <p class="image-label">商品画像</p>

        <div class="image-drop-area">
            <label for="images" class="image-select-btn">
                画像を選択する
            </label>
            <input type="file" id="images" name="images[]" multiple style="display:none;">
        </div>

        <div class="detail-section">
            <div class="detail-header">商品の詳細</div>

            <div class="category-section">
                <div class="category-title">カテゴリー</div>

                <div class="category-tags">
                    <div class="category-tag" data-id="1" data-name="ファッション">ファッション</div>
                    <div class="category-tag" data-id="2" data-name="家電">家電</div>
                    <div class="category-tag" data-id="3" data-name="インテリア">インテリア</div>
                    <div class="category-tag" data-id="4" data-name="レディース">レディース</div>
                    <div class="category-tag" data-id="5" data-name="メンズ">メンズ</div>
                    <div class="category-tag" data-id="6" data-name="コスメ">コスメ</div>
                    <div class="category-tag" data-id="7" data-name="本">本</div>
                    <div class="category-tag" data-id="8" data-name="ゲーム">ゲーム</div>
                    <div class="category-tag" data-id="9" data-name="スポーツ">スポーツ</div>
                    <div class="category-tag" data-id="10" data-name="キッチン">キッチン</div>
                    <div class="category-tag" data-id="11" data-name="ハンドメイド">ハンドメイド</div>
                    <div class="category-tag" data-id="12" data-name="アクセサリー">アクセサリー</div>
                    <div class="category-tag" data-id="13" data-name="おもちゃ">おもちゃ</div>
                    <div class="category-tag" data-id="14" data-name="ベビー・キッズ">ベビー・キッズ</div>
                </div>

                <div id="category-hidden-container"></div>
            </div>

            <div class="condition-section">
                <div class="condition-title">商品の状態</div>

                <select name="status" class="condition-select">
                    <option value="" disabled selected>選択してください</option>
                    <option value="like-new">良好</option>
                    <option value="no-damage">目立った傷や汚れなし</option>
                    <option value="damage">やや傷や汚れあり</option>
                    <option value="bad">状態が悪い</option>
                </select>
            </div>
        </div>

        <div class="product-name-description-block">
            <div class="product-name-description-title">商品名と説明</div>
            <div class="product-name-description-border"></div>
        </div>
       
        <div class="product-name-block">
            <div class="product-name-label">商品名</div>
            <input type="text" name="name" class="product-name-input">
        </div>

        <div class="brand-name-block">
            <div class="brand-name-label">ブランド名</div>
            <input type="text" name="brand" class="brand-name-input">
        </div>

        <div class="product-description-block">
            <div class="product-description-label">商品の説明</div>
            <textarea name="description" class="product-description-input"></textarea>
        </div>

        <div class="price-block">
            <div class="price-label">販売価格</div>
            <div class="price-input-wrapper">
                <span class="price-yen">¥</span>
                <input type="text" name="price" class="price-input">
            </div>
        </div>

        <button class="submit-button">出品する</button>
    </form>

    <script>
        document.querySelectorAll('.category-tag').forEach(tag => {
            tag.addEventListener('click', () => {
                tag.classList.toggle('selected');

                const id = tag.dataset.id;
                const key = id;
                const container = document.getElementById('category-hidden-container');

                if (tag.classList.contains('selected')) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'categories[]';
                    input.value = id;
                    input.dataset.key = key;
                    container.appendChild(input);
                } else {
                    const target = container.querySelector(`input[data-key="${key}"]`);
                    if (target) target.remove();
                }
            });
        });
    </script>
</div>
@endsection





