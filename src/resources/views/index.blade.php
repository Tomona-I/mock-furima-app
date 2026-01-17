@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/products.css') }}">
@endsection

@section('content')
<div class="section-tabs">
    <a href="{{ route('index', ['tab' => 'recommended'] + ($keyword ? ['keyword' => $keyword] : [])) }}" 
       class="section-tab-item {{ $tab === 'recommended' ? 'active' : '' }}">
        おすすめ
    </a>
    <a href="{{ route('index', ['tab' => 'mylist'] + ($keyword ? ['keyword' => $keyword] : [])) }}" 
       class="section-tab-item {{ $tab === 'mylist' ? 'active' : '' }}">
        マイリスト
    </a>
</div>
<div class="products">
    @forelse($products as $product)
        <a href="/item/{{ $product->id }}" class="product-card">
            <div class="product-image-wrapper">
                <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->name }}" class="product-image">
                @if($product->is_sold)
                    <div class="sold-label">Sold</div>
                @endif
            </div>
            <p class="product-name">{{ $product->name }}</p>
        </a>
    @empty
        <p>商品がありません</p>
    @endforelse
    
    <!-- ハードコーディング済みの商品カード（参考用） -->
    <!-- <div class="product-card">
        <div class="product-image-wrapper">
            <img src="" alt="商品画像" class="product-image">
        </div>
        <p class="product-name">商品名</p>
    </div>
    <div class="product-card">
        <div class="product-image-wrapper">
            <img src="" alt="商品画像" class="product-image">
        </div>
        <p class="product-name">商品名</p>
    </div>
    <div class="product-card">
        <div class="product-image-wrapper">
            <img src="" alt="商品画像" class="product-image">
        </div>
        <p class="product-name">商品名</p>
    </div>
    <div class="product-card">
        <div class="product-image-wrapper">
            <img src="" alt="商品画像" class="product-image">
        </div>
        <p class="product-name">商品名</p>
    </div>
    <div class="product-card">
        <div class="product-image-wrapper">
            <img src="" alt="商品画像" class="product-image">
        </div>
        <p class="product-name">商品名</p>
    </div>
    <div class="product-card">
        <div class="product-image-wrapper">
            <img src="" alt="商品画像" class="product-image">
        </div>
        <p class="product-name">商品名</p> 
    </div>
    <div class="product-card">
        <div class="product-image-wrapper">
            <img src="" alt="商品画像" class="product-image">
        </div>
        <p class="product-name">商品名</p>
    </div> -->
</div>
@endsection 