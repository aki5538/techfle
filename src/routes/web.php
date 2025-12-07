<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\ProfileController;

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

// 商品一覧（トップ画面）
Route::get('/', [ItemController::class, 'index'])->name('items.index');

// 会員登録画面
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// ログイン画面
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

// 商品詳細画面
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('items.show');

// 商品購入画面
Route::get('/purchase/{item_id}', [PurchaseController::class, 'create'])->name('purchase.create');
Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');

// 送付先住所変更画面
Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress'])->name('purchase.address');
Route::put('/purchase/address/{item_id}', [AddressController::class, 'update'])->name('purchase.address.update');

// 商品出品画面
Route::get('/sell', [SellController::class, 'create'])->name('sell.create');
Route::post('/sell', [SellController::class, 'store'])->name('sell.store');

// プロフィール画面（トップ／購入一覧／出品一覧）
Route::get('/mypage', [ProfileController::class, 'index'])->name('mypage.index');

// プロフィール編集画面（設定画面）
Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('mypage.profile');
Route::post('/mypage/profile', [ProfileController::class, 'store'])->name('mypage.profile.store');

