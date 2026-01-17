<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\PurchaseController;

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

Route::get('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

Route::get('/index', [ProductController::class, 'index'])->name('index');
Route::get('/item/{product}', [ProductController::class, 'show'])->name('item');

Route::get('/sell', [ProductController::class, 'create'])
    ->middleware('auth')->name('sell');

Route::post('/products', [ProductController::class, 'store'])
    ->middleware('auth')->name('products.store');

Route::get('/purchase/{product}', [PurchaseController::class, 'show'])
    ->middleware('auth')->name('purchase.show');

Route::post('/purchase/{product}', [PurchaseController::class, 'store'])
    ->middleware('auth')->name('purchase.store');

Route::get('/profile_edit', [ProfileController::class, 'edit'])
    ->middleware('auth')->name('profile_edit');

Route::post('/profile_edit', [ProfileController::class, 'update'])
    ->middleware('auth')->name('profile_edit.update');

Route::get('/purchase/address_edit/{product}', [ProfileController::class, 'edit'])
    ->middleware('auth')->name('purchase.address_edit');

Route::patch('/purchase/address_edit/{product}', [ProfileController::class, 'update'])
    ->middleware('auth')->name('purchase.address_edit.update');

Route::get('/mypage', [MyPageController::class, 'show'])
    ->middleware('auth')->name('mypage');

Route::post('/products/{product}/comments', [CommentController::class, 'store'])
    ->middleware('auth')->name('comments.store');

Route::post('/favorites/{product}', [FavoriteController::class, 'toggle'])
    ->middleware('auth')->name('favorites.toggle');