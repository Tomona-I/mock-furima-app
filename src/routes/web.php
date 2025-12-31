<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/index', function () {
    return view('index');
})->name('index');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/detail', function () {
    return view('detail');
});
Route::get('/profile_edit', function () {
    return view('profile_edit');
})->name('profile_edit');

Route::get('/purchase', function () {
    return view('purchase');
});

Route::get('/mypage', function () {
    return view('mypage');
})->middleware('auth')->name('mypage');
Route::get('/sell', function () {
    return view('sell');
})->middleware('auth')->name('sell');
Route::get('/address', function () {
    return view('address');
});