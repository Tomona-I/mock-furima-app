@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<div class="product">
    <div class="product-image">
        <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->name }}">
    </div>
    <div class="product-details">
        <div class="product-summary">
            <div class="product-name">{{ $product->name }}</div>
            @if($product->brand)
                <p class="product-brand">{{ $product->brand }}</p>
            @endif
            <div class="price-row">
                <p class="product-price">¥{{ number_format($product->price) }}</p>
                <span class="tax-included">（税込）</span>
            </div>
        </div>
        <div class="product-actions">
            <div class="likes">
                <button type="button" class="favorite-button" data-product-id="{{ $product->id }}">
                    <img class="favorite-icon" src="{{ asset('img/ハートロゴ_' . ($isFavorited ? 'ピンク' : 'デフォルト') . '.png') }}" alt="マイリストアイコン">
                </button>
                <span class="like-count favorite-count">{{ $favoriteCount }}</span>
            </div>
            <div class="comments">
                <img class="comment-icon" src="{{ asset('img/fukidasi_logo.png') }}" alt="コメントアイコン">
                <span class="comment-count">{{ $commentCount }}</span>
            </div>
        </div>
        <div class="purchase-link">
            @if(!$product->is_sold && (!auth()->check() || auth()->id() !== $product->user_id))
                <a href="/purchase/{{ $product->id }}" class="purchase-button">購入手続きへ</a>
            @elseif($product->is_sold)
                <button class="purchase-button" disabled style="background-color: #ccc; cursor: not-allowed;">売却済み</button>
            @endif
        </div>
        <div class="product-description">
            <h3>商品説明</h3>
            <p class="product-description-content">{{ $product->description }}</p>
        </div>
        <div class="product-info">
            <h3>商品情報</h3>
            <p class="product-category">
                カテゴリー
                @foreach($product->categories as $category)
                    <span class="category-name">{{ $category->name }}</span>
                @endforeach
            </p>
            <p class="product-condition">商品の状態<span class="condition-name">
                @switch($product->condition)
                    @case('new')
                        良好
                        @break
                    @case('used')
                        目立った傷や汚れなし
                        @break
                    @case('damaged')
                        やや傷や汚れあり
                        @break
                    @case('junk')
                        状態が悪い
                        @break
                    @default
                        {{ $product->condition }}
                @endswitch
            </span></p>
        </div>
        <div class="product-comments">
            <h2 class="product-comments-title">コメント<span class="comment-count">({{ $commentCount }})</span></h2>
            <div class="comment-list">
                @forelse($comments as $comment)
                    <div class="comment-avatar">
                        <img src="{{ asset('storage/' . $comment->user->profile_image) }}" alt="{{ $comment->user->name }}">
                        <p class="comment-user">{{ $comment->user->name }}</p>
                    </div>
                    <div class="comment-content">{{ $comment->content }}</div>
                @empty
                    <p>こちらにコメントが入ります。</p>
                @endforelse
            </div>
        </div>
            <div class="comment-form">
                <p>商品へのコメント</p>
                <form action="{{ route('comments.store', $product->id) }}" method="POST">
                @csrf
                <textarea name="content" class="comment-textarea" placeholder="コメントを入力してください"></textarea>
                @error('content')
                    <span class="error-message">{{ $message }}</span>
                @enderror
                <button type="submit" class="comment-submit">コメントを送信する</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelector('.favorite-button').addEventListener('click', async function(e) {
    e.preventDefault();
    
    const productId = this.getAttribute('data-product-id');
    const icon = this.querySelector('.favorite-icon');
    const count = this.parentElement.querySelector('.favorite-count');
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                  || document.querySelector('input[name="_token"]')?.value;
    
    try {
        const res = await fetch(`/favorites/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
            },
        });
        
        if (res.status === 401) {
            window.location.href = '/login';
            return;
        }
        
        const data = await res.json();
        icon.src = data.favorited 
            ? '{{ asset("img/ハートロゴ_ピンク.png") }}' 
            : '{{ asset("img/ハートロゴ_デフォルト.png") }}';
        count.textContent = data.count;
    } catch (error) {
        console.error('Error:', error);
    }
});
</script>

@endsection
