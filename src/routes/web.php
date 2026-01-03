<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\AddressController;
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



// 商品一覧（トップ画面）
Route::get('/', [ItemController::class, 'index'])->name('items.index');

// 会員登録
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// ログイン画面（POST は Fortify が処理）
Route::get('/login', [LoginController::class, 'create'])->name('login');

// 商品詳細
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('items.show');
Route::post('/item/{item_id}', [ItemController::class, 'show'])->middleware('auth');

// ここから「ログイン + メール認証済み」必須
Route::middleware(['auth', 'verified'])->group(function () {

    // 商品購入画面（PG06）
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'create'])
        ->name('purchase.create');

    // 購入処理
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])
        ->name('purchase.store');

    // 購入前の住所変更（FN024）
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress'])
        ->name('purchase.address');
    Route::put('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])
        ->name('purchase.address.update');

    // 出品画面
    Route::get('/sell', [SellController::class, 'create'])->name('sell.create');
    Route::post('/sell', [SellController::class, 'store'])->name('sell.store');

    // マイページ（トップ／購入一覧／出品一覧）
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');

    // プロフィール編集画面（設定画面）
    Route::get('/mypage/profile', [MypageController::class, 'edit'])->name('mypage.profile');
    Route::post('/mypage/profile', [MypageController::class, 'store'])->name('mypage.profile.store');
});



