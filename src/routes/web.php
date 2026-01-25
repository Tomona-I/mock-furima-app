<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\VerificationController;

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

Route::get('/index', [ItemController::class, 'index'])->name('index');
Route::get('/item/{product}', [ItemController::class, 'show'])->name('item');

Route::get('/sell', [ItemController::class, 'create'])
    ->middleware(['auth', 'verified'])->name('sell');

Route::post('/products', [ItemController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('products.store');

Route::get('/purchase/{product}', [PurchaseController::class, 'show'])
    ->middleware(['auth', 'verified'])->name('purchase.show');

Route::post('/purchase/{product}', [PurchaseController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('purchase.store');

Route::get('/profile_edit', [ProfileController::class, 'edit'])
    ->middleware(['auth', 'verified'])->name('profile_edit');

Route::post('/profile_edit', [ProfileController::class, 'update'])
    ->middleware(['auth', 'verified'])->name('profile_edit.update');

Route::get('/purchase/address_edit/{product}', [ProfileController::class, 'edit'])
    ->middleware(['auth', 'verified'])->name('purchase.address_edit');

Route::patch('/purchase/address_edit/{product}', [ProfileController::class, 'update'])
    ->middleware(['auth', 'verified'])->name('purchase.address_edit.update');

Route::get('/mypage', [MyPageController::class, 'show'])
    ->middleware(['auth', 'verified'])->name('mypage');

Route::post('/products/{product}/comments', [CommentController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('comments.store');

Route::post('/favorites/{product}', [FavoriteController::class, 'toggle'])
    ->name('favorites.toggle');

Route::get('/email/verify', function () {
    return view('verify');
})->middleware('auth')->name('verification.notice');

Route::post('/email/verification-notification', [VerificationController::class, 'send'])
    ->middleware('auth', 'throttle:6,1')->name('verification.send');

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware('auth', 'signed', 'throttle:6,1')->name('verification.verify');
