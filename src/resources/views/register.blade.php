@extends('layouts.authapp')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<h1>会員登録</h1>
<div class="register-form">
    <form action="/register" method="post" novalidate>
        @csrf
        <div class="register-info">
            <p>ユーザー名</p>
            <input type="text" name="name" value="{{ old('name') }}">
            <div class="error-message">
                @error('name')
                {{$message}}
                @enderror
            </div>
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
            <p>確認用パスワード</p>
            <input type="password" name="password_confirmation">
            <div class="error-message">
                @error('password_confirmation')
                {{$message}}
                @enderror
            </div>
            <div class="register-button">
                <button type="submit">登録する</button>
            </div>
            <div class="login-link">
                <a href="/login">ログインはこちら</a>
            </div>
        </div>
    </form>
</div>

@endsection
