@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<h1>住所の変更</h1>
<div class="address-form">
    <form action="/address" method="POST">
        @csrf
        <div class="address-info">
            <div class="form-group">
                <label>郵便番号</label>
                <input type="text" name="postal_code" value="{{ old('postal_code') }}">
                <div class="error-message">
                    @error('postal_code')
                    {{$message}}
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label>住所</label>
                <input type="text" name="address" value="{{ old('address') }}">
                <div class="error-message">
                    @error('address')
                    {{$message}}
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label>建物名</label>
                <input type="text" name="building" value="{{ old('building') }}">
                <div class="error-message">
                    @error('building')
                    {{$message}}
                    @enderror
                </div>
            </div>

            <div class="address-button">
                <button type="submit">変更する</button>
            </div>
        </div>
    </form>
</div>
@endsection
