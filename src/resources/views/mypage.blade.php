@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage-container">
    <div class="profile-section">
        <div class="profile-icon">
            <img id="profileIconPreview" src="" alt="プロフィールアイコン">
            <input type="file" id="iconInput" name="icon" accept="image/*" style="display: none;">
        </div>
        <div class="profile-username">
            <p class="username-value">ユーザー名</p>
        </div>
        <div class="profile-edit-button">
            <button action="mypage_edit" method="get">プロフィールを編集</button>
        </div>
    </div>
    <div class="mypage-list-section">
        <div class="mypage-list-tabs">
            <button class="mypage-list-tab-item active">出品した商品</button>
            <button class="mypage-list-tab-item">購入した商品</button>
        </div>
        <div class="mypage-products">
            <div class="mypage-product-card">
                <div class="mypage-product-image-wrapper">
                    <img src="" alt="商品画像" class="mypage-product-image">
                </div>
                <p class="mypage-product-name">商品名</p>
            </div>
            <div class="mypage-product-card">
                <div class="mypage-product-image-wrapper">
                    <img src="" alt="商品画像" class="mypage-product-image">
                </div>
                <p class="mypage-product-name">商品名</p>
            </div>
            <div class="mypage-product-card">
                <div class="mypage-product-image-wrapper">
                    <img src="" alt="商品画像" class="mypage-product-image">
                </div>
                <p class="mypage-product-name">商品名</p>
            </div>
            <div class="mypage-product-card">
                <div class="mypage-product-image-wrapper">
                    <img src="" alt="商品画像" class="mypage-product-image">
                </div>
                <p class="mypage-product-name">商品名</p>
            </div>
            <div class="mypage-product-card">
                <div class="mypage-product-image-wrapper">
                    <img src="" alt="商品画像" class="mypage-product-image">
                </div>
                <p class="mypage-product-name">商品名</p>
            </div>
            <div class="mypage-product-card">
                <div class="mypage-product-image-wrapper">
                    <img src="" alt="商品画像" class="mypage-product-image">
                </div>
                <p class="mypage-product-name">商品名</p>
            </div>
            <div class="mypage-product-card">
                <div class="mypage-product-image-wrapper">
                    <img src="" alt="商品画像" class="mypage-product-image">
                </div>
                <p class="mypage-product-name">商品名</p>
            </div>
        </div>
</div>
@endsection
