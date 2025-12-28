@extends('layouts.authapp')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<h1>ログイン</h1>
<div class="login-form">
    <form action="/login" method="post" novalidate>
        @csrf
        <div class="login-info">
            <p>メールアドレス</p>
            <input type="email" name="email" value="{{ old('email') }}">
            <div class="error-message">
                @error('email')
                {{$message}}
                @enderror
            </div>
            <p>パスワード</p>
            <input type="password" name="password">
            <div class="error-message">
                @error('password')
                {{$message}}
                @enderror
            </div>
            <div class="login-button">
                <button type="submit">ログインする</button>
            </div>
            <div class="register-link">
                <a href="/register">会員登録はこちら</a>
            </div>
        </div>
    </form>
</div>

@endsection

