@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="sell-container">
    <div class="sell-form-section">
        <h1>商品の出品</h1>
        <div class="product-image-upload">
            <label>商品画像</label>
            <div class="image-preview-wrapper">
                <img src="" alt="商品画像プレビュー" class="product-image-preview">
                <button type="button" class="image-select-button" onclick="document.getElementById('imageInput').click()">画像を選択する</button>
            </div>
            <input type="file" id="imageInput" name="image" accept="image/*" style="display:none;">
            <div class="error-message">
                @error('image')
                {{$message}}
                @enderror
            </div>
        </div>
        <div class="product-details-form">
            <h2>商品の詳細</h2>
            <form action="/sell" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="category-selection">
                    <label>カテゴリー</label>
                        <div class="category-buttons">
                        <button type="button" class="category-button">ファッション</button>
                        <button type="button" class="category-button">家電</button>
                        <button type="button" class="category-button">インテリア</button>
                        <button type="button" class="category-button">レディース</button>
                        <button type="button" class="category-button">メンズ</button>
                        <button type="button" class="category-button">コスメ</button>
                        <button type="button" class="category-button">本</button>
                        <button type="button" class="category-button">ゲーム</button>
                        <button type="button" class="category-button">スポーツ</button>
                        <button type="button" class="category-button">キッチン</button>
                        <button type="button" class="category-button">ハンドメイド</button>
                        <button type="button" class="category-button">アクセサリー</button>
                        <button type="button" class="category-button">おもちゃ</button>
                        <button type="button" class="category-button">ベビー・キッズ</button>
                    </div>
                </div>
            </div>
            <div class="product-condition">
                <label>商品の状態</label>
                <select name="condition">
                    <option value="" selected disabled hidden>選択してください</option>
                    <option value="new">新品</option>
                    <option value="used">目立った傷や汚れなし</option>
                    <option value="damaged">やや傷や汚れあり</option>
                    <option value="junk">状態が悪い</option>
                </select>
                <div class="error-message">
                    @error('condition')
                    {{$message}}
                    @enderror
                </div>
            </div>
            <div class="product-description">
                <h2>商品名と説明</h2>
                
                <div class="form-group">
                    <label>商品名</label>
                    <input type="text" name="name" value="{{ old('name') }}">
                    <div class="error-message">
                        @error('name')
                        {{$message}}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>ブランド名</label>
                    <input type="text" name="brand" value="{{ old('brand') }}">
                    <div class="error-message">
                        @error('brand')
                        {{$message}}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>商品の説明</label>
                    <textarea name="description">{{ old('description') }}</textarea>
                    <div class="error-message">
                        @error('description')
                        {{$message}}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>販売価格</label>
                    <div class="price-input-wrapper">
                        <input type="text" name="price" value="{{ old('price') }}">
                    </div>
                    <div class="error-message">
                        @error('price')
                        {{$message}}
                        @enderror
                    </div>
                </div>
                    <div class="error-message">
                        @error('price')
                        {{$message}}
                        @enderror
                    </div>
                    <div class="sell-button">
                        <button type="submit">出品する</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.category-button').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            this.classList.toggle('active');
        });
    });
</script>
@endsection
