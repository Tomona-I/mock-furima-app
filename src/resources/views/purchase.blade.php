@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')

<form id="payment-form" action="{{ route('purchase.store', $product->id) }}" method="post" class="purchase-form">
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
                        <option value="" disabled @if(old('payment_method') === null) selected hidden @endif>選択してください</option>
                        <option value="card" @if(old('payment_method') === 'card') selected @endif>クレジットカード</option>
                        <option value="convenience" @if(old('payment_method') === 'convenience') selected @endif>コンビニ払い</option>
                    </select>
                    @error('payment_method')
                        <div class="validation-error">
                            <p>{{ $message }}</p>
                        </div>
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
                @error('postal_code')
                    <div class="validation-error">
                        <p>{{ $message }}</p>
                    </div>
                @enderror
                @error('address')
                    <div class="validation-error">
                        <p>{{ $message }}</p>
                    </div>
                @enderror
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
                    <td id="payment-display">
                        @if(old('payment_method') === 'card')
                            クレジットカード
                        @elseif(old('payment_method') === 'convenience')
                            コンビニ払い
                        @else
                            未選択
                        @endif
                    </td>
                </tr>
            </table>
            <button type="submit" class="purchase-button">購入する</button>
        </div>
    </div>
    <input type="hidden" name="postal_code" value="{{ $user->postal_code }}">
    <input type="hidden" name="address" value="{{ $user->address }}">
    <input type="hidden" name="building" value="{{ $user->building }}">
</form>

<script>
    const paymentDisplay_text = {
        'card': 'クレジットカード',
        'convenience': 'コンビニ払い'
    };

    const selectElement = document.querySelector('select[name="payment_method"]');
    const paymentDisplay = document.getElementById('payment-display');

    selectElement.addEventListener('change', function() {
        paymentDisplay.textContent = paymentDisplay_text[this.value] || '未選択';
    });
</script>

@endsection
