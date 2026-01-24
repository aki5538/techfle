@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/verify.css') }}">
@endsection

@section('content')
    <div class="verify-message">
        登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください。
    </div>

    @php
        use Illuminate\Support\Facades\URL;
    @endphp

    <a href="{{ URL::signedRoute('verification.verify', [
            'id' => auth()->id(),
            'hash' => sha1(auth()->user()->email)
        ]) }}" class="verify-main-button">
        認証はこちらから
    </a>

    <!-- 認証メール再送（青文字リンク） -->
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="verify-resend-link">
            認証メールを再送する
        </button>
    </form>

@endsection

