<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PhotoController;

Route::resource('photos', PhotoController::class)->only([
    'index', 'show'
]);

Route::resource('photos', PhotoController::class)->except([
    'create', 'store', 'update', 'destroy'
]);

Route::resource('photos', PhotoController::class);

// untuk halaman utama
Route::get('/', HomeController::class);

// untuk halaman about
Route::get('/about', AboutController::class);

// untuk halaman artikel berdasarkan ID
Route::get('/articles/{id}', ArticleController::class);

// untuk halaman utama
Route::get('/', [PageController::class, 'index']);

// untuk halaman about
Route::get('/about', [PageController::class, 'about']);

// untuk halaman artikel dengan parameter ID
Route::get('/articles/{id}', [PageController::class, 'articles']);

Route::get('/', function () {
    return view('welcome');
});
Route::resource('items', ItemController::class);

// route basic
Route::get('/hello', [WelcomeController::class, 'hello']);

Route::get('/world', function () {
    return 'world';
});

Route::get('/welcome', function () {
    return 'Selamat Datang';
});

Route::get('/about', function () {
    return 'Nama : Lovelyta Sekarayu <br> NIM : 2341760081';
});

// route parameter
Route::get('/user/{name}', function ($name) {
    return 'Nama saya ' . $name;
});

Route::get('/posts/{post}/comments/{comment}', function ($postId, $commentId) {
    return 'Post ke- ' . $postId . ' Komentar ke- ' . $commentId;
});

Route::get('/articles/{id}', function ($id) {
    return 'Halaman Artikel dengan ID ' . $id;
});

//Optional Parameter
Route::get('/user/{name?}', function ($name = 'John') {
    return 'Nama saya ' . $name;
});


//view
Route::get('/greeting', [WelcomeController::class, 'greeting']);