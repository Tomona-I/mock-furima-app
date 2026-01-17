@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="sell-container">
    <div class="sell-form-section">
        <h1>商品の出品</h1>
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="product-image-upload">
                <label>商品画像</label>
                <div class="image-preview-wrapper">
                    <img src="" alt="" class="product-image-preview">
                    <button type="button" class="image-select-button" onclick="document.querySelector('.image-input').click()">画像を選択する</button>
                </div>
                <input type="file" class="image-input" name="product_image" accept="image/*">
                <div class="error-message">
                    @error('product_image')
                    {{$message}}
                    @enderror
                </div>
            </div>
            <div class="product-details-form">
                <h2>商品の詳細</h2>
                <div class="category-selection">
                    <label>カテゴリー</label>
                    <div class="category-buttons">
                        @foreach($categories as $category)
                            <label class="category-label">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                                <button type="button" class="category-button">{{ $category->name }}</button>
                            </label>
                        @endforeach
                    </div>
                    <div class="error-message">
                        @error('categories')
                        {{$message}}
                        @enderror
                    </div>
                </div>
                <div class="product-condition">
                    <label>商品の状態</label>
                    <select name="condition">
                        <option value="" {{ old('condition') === null ? 'selected' : '' }} disabled hidden>選択してください</option>
                        <option value="new" {{ old('condition') === 'new' ? 'selected' : '' }}>新品</option>
                        <option value="used" {{ old('condition') === 'used' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                        <option value="damaged" {{ old('condition') === 'damaged' ? 'selected' : '' }}>やや傷や汚れあり</option>
                        <option value="junk" {{ old('condition') === 'junk' ? 'selected' : '' }}>状態が悪い</option>
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
                    <div class="sell-button">
                        <button type="submit">出品する</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>

    document.addEventListener('DOMContentLoaded', function() {
        
        document.querySelectorAll('.category-label input[type="checkbox"]:checked').forEach(checkbox => {
            checkbox.parentElement.querySelector('.category-button').classList.add('active');
        });

        document.querySelectorAll('.category-button').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                this.classList.toggle('active');
                const checkbox = this.parentElement.querySelector('input[type="checkbox"]');
                checkbox.checked = this.classList.contains('active');
            });
        });

        document.querySelector('form').addEventListener('submit', function() {
            localStorage.removeItem('productPreviewImage');
        });
    });

    document.querySelector('.image-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const dataUrl = event.target.result;
                document.querySelector('.product-image-preview').src = dataUrl;
                localStorage.setItem('productPreviewImage', dataUrl);
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
