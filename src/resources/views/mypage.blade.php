@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage-container">
    <div class="profile-section">
        <div class="profile-icon">
            <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('img/プロフィールアイコン.png') }}" alt="プロフィールアイコン">
        </div>
        <div class="profile-username">
            <p class="username-value">{{ $user->name }}</p>
        </div>
        <div class="profile-edit-button">
            <a href="{{ route('profile_edit') }}">プロフィールを編集</a>
        </div>
    </div>
    <div class="mypage-list-section">
        <div class="mypage-list-tabs">
            <a href="/mypage?page=sell" class="mypage-list-tab-item {{ $page === 'sell' ? 'active' : '' }}">出品した商品</a>
            <a href="/mypage?page=buy" class="mypage-list-tab-item {{ $page === 'buy' ? 'active' : '' }}">購入した商品</a>
        </div>
        @if($page === 'sell')
        <div class="mypage-products">
            @forelse($listedProducts as $product)
                <a href="{{ route('item', $product->id) }}" class="mypage-product-card">
                    <div class="mypage-product-image-wrapper">
                        <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->name }}" class="mypage-product-image">
                        @if($product->is_sold)
                            <div class="product-sold-label">売却済み</div>
                        @endif
                    </div>
                    <p class="mypage-product-name">{{ $product->name }}</p>
                </a>
            @empty
                <p class="no-products">出品した商品がありません</p>
            @endforelse
        </div>
        @else
        <div class="mypage-products">
            @forelse($purchasedOrders as $order)
                <a href="{{ route('item', $order->product->id) }}" class="mypage-product-card">
                    <div class="mypage-product-image-wrapper">
                        <img src="{{ asset('storage/' . $order->product->product_image) }}" alt="{{ $order->product->name }}" class="mypage-product-image">
                    </div>
                    <p class="mypage-product-name">{{ $order->product->name }}</p>
                </a>
            @empty
                <p class="no-products">購入した商品がありません</p>
            @endforelse
        </div>
        @endif
    </div>
</div>
@endsection
