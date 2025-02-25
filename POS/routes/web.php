<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\UserController;

//Home
Route::get('/', [HomeController::class, 'index']);

//Product dengan Prefix
Route::prefix('products')->group(function () {
    Route::get('/food-beverages', [ProductController::class, 'foodBeverages'])->name('category.food-beverage');
    Route::get('/beauty-health', [ProductController::class, 'beautyHealth'])->name('category.beauty-health');
    Route::get('/home-care', [ProductController::class, 'homeCare'])->name('category.home-care');
    Route::get('/baby-kid', [ProductController::class, 'babyKid'])->name('category.baby-kid');
});

//User dengan parameter
Route::get('/user/{id}/name/{name}', [UserController::class, 'showProfile'])->name('user.profile');

//Sales
Route::get('/sales', [SalesController::class, 'index'])->name('sales');