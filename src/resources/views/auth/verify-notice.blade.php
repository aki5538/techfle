@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 600px; margin: 40px auto;">

    <div class="mb-4 text-sm text-gray-600">
        まだメール認証が完了していません。<br>
        ご登録のメールアドレスに送信された認証リンクをクリックしてください。
    </div>

    <div class="mt-4 flex flex-col gap-4">

        <!-- 認証はこちらから（メール認証画面へ遷移） -->
        <a href="{{ route('verification.notice') }}" class="btn btn-primary">
            認証はこちらから
        </a>

        <!-- 認証メール再送（FN013） -->
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-secondary">
                認証メールを再送する
            </button>
        </form>

        <!-- ログアウト -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-light">
                ログアウト
            </button>
        </form>

    </div>
</div>
@endsection

