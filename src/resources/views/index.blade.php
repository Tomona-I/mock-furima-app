@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/products.css') }}">
@endsection

@section('content')
<div class="section-tabs">
    <button class="section-tab-item">おすすめ</button>
    <button class="section-tab-item">マイリスト</button>
</div>
<div class="products">
    <div class="product-card">
        <img src="" alt="商品画像" class="product-image">
        <p class="product-name">商品名</p>
    </div>
    <div class="product-card">
        <img src="" alt="商品画像" class="product-image">
        <p class="product-name">商品名</p>
    </div>
    <div class="product-card">
        <img src="" alt="商品画像" class="product-image">
        <p class="product-name">商品名</p>
    </div>
    <div class="product-card">
        <img src="" alt="商品画像" class="product-image">
        <p class="product-name">商品名</p>
    </div>
    <div class="product-card">
        <img src="" alt="商品画像" class="product-image">
        <p class="product-name">商品名</p>
    </div>
    <div class="product-card">
        <img src="" alt="商品画像" class="product-image">
        <p class="product-name">商品名</p> 
    </div>
    <div class="product-card">
        <img src="" alt="商品画像" class="product-image">
        <p class="product-name">商品名</p>
    </div>
</div>
@endsection 