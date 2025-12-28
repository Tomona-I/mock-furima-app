@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="product">
    <div class="product-image">
        <img src="" alt="商品画像">
    </div>
    <div class="product-details">
        <div class="product-summary">
            <div class="product-name">商品名</div>
            <p class="product-brand">ブランド名</p>
            <div class="price-row">
                <p class="product-price">¥00000</p>
                <span class="tax-included">（税込）</span>
            </div>
        </div>
        <div class="product-actions">
            <div class="likes">
                <img src="/img/ハートロゴ_デフォルト.png" alt="マイリストアイコン">
                <span class="like-count">0</span>
            </div>
            <div class="comments">
                <img src="/img/ふきだしロゴ.png" alt="コメントアイコン">
                <span class="comment-count">0</span>
            </div>
        </div>
        <div class="purchase-link">
            <a href="" class="purchase-button">購入手続きへ</a>
        </div>
        <div class="product-description">
            <h3>商品説明</h3>
            <p class="product-description-content">ここに商品説明が入ります。</p>
        </div>
        <div class="product-info">
            <h3>商品情報</h3>
                <p class="product-category">カテゴリー<span class="category-name">洋服</span></p>
                <p class="product-condition">商品の状態<span class="condition-name">新品</span></p>
        </div>
        <div class="product-comments">
            <h2 class="product-comments-title">コメント<span class="comment-count">(0)</span></h2>
            <div class="comment-list">
                <div class="comment-avatar">
                    <img src="" alt="ユーザーアイコン">
                    <p class="comment-user">admin</p>
                </div>
                <div class="comment-content">ここにコメントが入ります。</div>
            </div>
        </div>
            <div class="comment-form">
                <p>商品へのコメント</p>
                <form action="" method="POST">
                @csrf
                <textarea name="content" class="comment-textarea"></textarea>
                <button type="submit" class="comment-submit">コメントを送信する</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection