@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase-container">
    <div class="purchase-content">
        <div class="purchase-product-section">
            <div class="purchase-product">
                <img src="" alt="商品画像">
                <div class="purchase-product-info">
                    <p class="product-name">商品名</p>
                    <p class="product-price">¥47,000</p>
                </div>
            </div>
        </div>
        <div class="purchase-payment-section">
            <h2>支払い方法</h2>
            <div class="payment-info">
                <select name="payment" class="payment-select">
                    <option value="" selected disabled hidden>選択してください</option>
                    <option value="card">カード払い</option>
                    <option value="convenience">コンビニ払い</option>
                </select>
            </div>
        </div>
        <div class="purchase-address-section">
            <div class="address-section-header">
                <h2>配送先</h2>
                <div class="address-edit-link">
                    <a href="/profile">変更する</a>
                </div>
            </div>
            <div class="address-info">
                <div class="address-info-content"> 
                    <p class="post-code">〒 XXX-YYY</p>
                    <p class="address-value">ここには住所と建物名が入ります</p>
                </div>
            </div>
        </div>
    </div>
    <div class="purchase-summary-wrapper">
        <table class="purchase-summary">
            <tr class="price-row">
                <th>商品代金</th>
                <td>¥47,000</td>
            </tr>
            <tr class="price-row">
                <th>支払い方法</th>
                <td>コンビニ払い</td>
            </tr>
        </table>
        <button type="submit" class="purchase-button">購入する</button>
    </div>
</div>
@endsection
