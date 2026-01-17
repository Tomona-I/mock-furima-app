@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile_edit.css') }}">
@endsection

@section('content')
<h1>プロフィール設定</h1>
<div class="profile-form">
    @if(isset($product))
        <form action="{{ route('purchase.address_edit.update', $product->id) }}" method="post" enctype="multipart/form-data">
            @method('PATCH')
    @else
        <form action="{{ route('profile_edit.update') }}" method="post" enctype="multipart/form-data">
    @endif
        @csrf
        <div class="profile-info">
            <div class="profile-icon-section">
                <img id="profileIconPreview" src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('img/プロフィールアイコン.png') }}" alt="プロフィールアイコン" class="profile-icon">
                <input type="file" id="iconInput" name="profile_image" accept="image/*" style="display: none;">
                <button type="button" class="icon-select-button" onclick="document.getElementById('iconInput').click()">画像を選択する</button>
            </div>
            <div class="error-message">
                @error('profile_image')
                {{$message}}
                @enderror
            </div>
            <p>ユーザー名</p>
            <input type="text" name="name" value="{{ old('name', $user->name) }}">
            <div class="error-message">
                @error('name')
                {{$message}}
                @enderror
            </div>
            <p>郵便番号</p>
            <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
            <div class="error-message">
                @error('postal_code')
                {{$message}}
                @enderror
            </div>
            <p>住所</p>
            <input type="text" name="address" value="{{ old('address', $user->address) }}">
            <div class="error-message">
                @error('address')
                {{$message}}
                @enderror
            </div>
            <p>建物名</p>
            <input type="text" name="building" value="{{ old('building', $user->building) }}">
            <div class="error-message">
                @error('building')
                {{$message}}
                @enderror
            </div>
            <div class="profile-button">
                <button type="submit">更新する</button>
            </div>
        </div>
    </form>
</div>

<script>
    let hasImageSelected = false;

    window.addEventListener('load', function() {
        const savedPreview = sessionStorage.getItem('profileIconPreview');
        if (savedPreview) {
            document.getElementById('profileIconPreview').src = savedPreview;
            hasImageSelected = true;
        }
    });

    document.getElementById('iconInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const base64Data = event.target.result;
                document.getElementById('profileIconPreview').src = base64Data;
                sessionStorage.setItem('profileIconPreview', base64Data);
                hasImageSelected = true;
            };
            reader.readAsDataURL(file);
        }
    });

    document.querySelector('form').addEventListener('submit', function() {
        // ユーザーが新しい画像を選択していない場合は、input[type=file]をリセット
        if (!hasImageSelected || !document.getElementById('iconInput').files.length) {
            document.getElementById('iconInput').value = '';
        }
        sessionStorage.removeItem('profileIconPreview');
    });
</script>

@endsection