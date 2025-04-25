<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::resource('book', BookController::class);
Route::post('book/update/{id}', [BookController::class, 'update'])->name('book.update_post');

Route::resource('author', AuthorController::class);

Route::get('checkout', [CartController::class, 'checkout'])->name('checkout');

Route::group(['prefix' => 'cart'], function () {
    Route::post('addItem', [CartController::class, 'addToCart'])->name('cart.addItem');
    Route::get('count', [CartController::class, 'getCartCount'])->name('cart.count');
    Route::post('update', [CartController::class, 'update'])->name('cart.update');
    Route::post('remove', [CartController::class, 'remove'])->name('cart.remove');
});

Route::get('order', [OrderController::class, 'create'])->name('order.create');
Route::post('order', [OrderController::class, 'store'])->name('order.store');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
