<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/level', [LevelController::class, 'index']);
Route::get('/kategori', [KategoriController::class, 'index']); 
Route::get('/user', [UserController::class, 'index']);

Route::get('/user/tambah', [UserController::class, 'tambah']);
Route::post('/user/tambah_simpan', [UserController::class, 'tambah_simpan']);

Route::get('/user/ubah/{id}', [UserController::class, 'ubah']);
Route::put('/user/ubah_simpan/{id}', [UserController::class, 'ubah_simpan']);

Route::get('/user/hapus/{id}', [UserController::class, 'hapus']);

Route::get('/', [WelcomeController::class, 'index']);

Route::group(['prefix'=>'user'], function(){
    Route::get('/',[UserController::class,'index']);//menampilkan halaman awal
    Route::post('/list',[UserController::class,'list']);//menampilkan data user bentuk json / datatables
    Route::get('/create',[UserController::class,'create']);// meanmpilkan bentuk form untuk tambah user
    Route::post('/',[UserController::class,'store']);//menyimpan user data baru 
    Route::get('/{id}',[UserController::class,'show']); // menampilkan detil user
    Route::get('/{id}/edit',[UserController::class,'edit']);// menampilkan halaman form edit user
    Route::put('/{id}',[UserController::class,'update']);// menyimpan perubahan data user 
    Route::delete('/{id}',[UserController::class,'destroy']);// menghapus data user 
});