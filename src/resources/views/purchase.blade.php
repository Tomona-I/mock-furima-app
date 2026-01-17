@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<form action="{{ route('purchase.store', $product->id) }}" method="post" class="purchase-form">
    @csrf
    <div class="purchase-container">
        <div class="purchase-content">
            <div class="purchase-product-section">
                <div class="purchase-product">
                    <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->name }}">
                    <div class="purchase-product-info">
                        <p class="product-name">{{ $product->name }}</p>
                        <p class="product-price">¥{{ number_format($product->price) }}</p>
                    </div>
                </div>
            </div>
            <div class="purchase-payment-section">
                <h2>支払い方法</h2>
                <div class="payment-info">
                    <select name="payment_method" class="payment-select">
                        <option value="" selected disabled hidden>選択してください</option>
                        <option value="card">カード払い</option>
                        <option value="convenience">コンビニ払い</option>
                    </select>
                    @error('payment_method')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="purchase-address-section">
                <div class="address-section-header">
                    <h2>配送先</h2>
                    <div class="address-edit-link">
                        <a href="{{ route('purchase.address_edit', $product->id) }}">変更する</a>
                    </div>
                </div>
                <div class="address-info">
                    <div class="address-info-content"> 
                        <p class="post-code">〒 {{ $user->postal_code }}</p>
                        <p class="address-value">{{ $user->address }}{{ $user->building }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="purchase-summary-wrapper">
            <table class="purchase-summary">
                <tr class="price-row">
                    <th>商品代金</th>
                    <td>¥{{ number_format($product->price) }}</td>
                </tr>
                <tr class="price-row">
                    <th>支払い方法</th>
                    <td id="payment-display">未選択</td>
                </tr>
            </table>
            <button type="submit" class="purchase-button">購入する</button>
        </div>
    </div>
</form>

<script>
    
    document.querySelector('select[name="payment_method"]').addEventListener('change', function() {
        const paymentDisplay = {
            'card': 'カード払い',
            'convenience': 'コンビニ払い'
        };
        document.getElementById('payment-display').textContent = paymentDisplay[this.value] || '未選択';
    });
</script>
@endsection
