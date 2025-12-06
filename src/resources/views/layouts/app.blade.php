<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>COACHTECH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('head') <!-- 各画面専用CSSをここに差し込む -->
</head>
<body>
    <header class="auth-header d-flex justify-content-between align-items-center">
    <img src="{{ asset('images/COACHTECHヘッダーロゴ.png') }}" alt="COACHTECHロゴ">

    @auth
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-danger">ログアウト</button>
    </form>
    @endauth
</header>

    <main class="container">
        @yield('content')
    </main>
</body>
</html>

