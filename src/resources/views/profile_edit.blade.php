@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile_edit.css') }}">
@endsection

@section('content')
<h1>プロフィール設定</h1>
<div class="profile-form">
    <form action="/profile" method="post">
        @csrf
        <div class="profile-info">
            <div class="profile-icon-section">
                <img id="profileIconPreview" src="" alt="プロフィールアイコン" class="profile-icon">
                <input type="file" id="iconInput" name="icon" accept="image/*" style="display: none;">
                <button type="button" class="icon-select-button" onclick="document.getElementById('iconInput').click()">画像を選択する</button>
            </div>
            <div class="error-message">
                @error('icon')
                {{$message}}
                @enderror
            </div>
            <p>ユーザー名</p>
            <input type="text" name="name" value="{{ old('name') }}">
            <div class="error-message">
                @error('name')
                {{$message}}
                @enderror
            </div>
            <p>郵便番号</p>
            <input type="text" name="postal_code" value="{{ old('postal_code') }}">
            <div class="error-message">
                @error('postal_code')
                {{$message}}
                @enderror
            </div>
            <p>住所</p>
            <input type="text" name="address" value="{{ old('address') }}">
            <div class="error-message">
                @error('address')
                {{$message}}
                @enderror
            </div>
            <p>建物名</p>
            <input type="text" name="building" value="{{ old('building') }}">
            <div class="error-message">
                @error('building')
                {{$message}}
                @enderror
            </div>
            <div class="profile-button">
                <button type="submit">更新する</button>
            </div>
        </div>
    </form>
@endsection