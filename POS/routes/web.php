<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SupplierController;

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

// m_level
Route::group(['prefix' => 'level'], function () {
    Route::get('/', [LevelController::class, 'index'])->name('level.index'); // Menampilkan daftar level
    Route::post('/list', [LevelController::class, 'getLevels'])->name('level.list'); // DataTables JSON
    Route::get('/create', [LevelController::class, 'create'])->name('level.create'); // Form tambah
    Route::post('/', [LevelController::class, 'store'])->name('level.store'); // Simpan data baru
    Route::get('/{id}', [LevelController::class, 'show'])->name('level.show'); // Menampilkan detail level
    Route::get('/{id}/edit', [LevelController::class, 'edit'])->name('level.edit'); // Form edit
    Route::put('/{id}', [LevelController::class, 'update'])->name('level.update'); // Simpan perubahan
    Route::delete('/{id}', [LevelController::class, 'destroy'])->name('level.destroy'); // Hapus level
});

//m_kategori
Route::group(['prefix'=>'kategori'], function(){
    Route::get('/',[KategoriController::class,'index']);//menampilkan halaman awal
    Route::post('/list',[KategoriController::class,'list']);//menampilkan data kategori bentuk json / datatables
    Route::get('/create',[KategoriController::class,'create']);// meanmpilkan bentuk form untuk tambah kategori
    Route::post('/',[KategoriController::class,'store']);//menyimpan kategori data baru 
    Route::get('/{id}',[KategoriController::class,'show']); // menampilkan detail kategori
    Route::get('/{id}/edit',[KategoriController::class,'edit']);// menampilkan halaman form edit kategori
    Route::put('/{id}',[KategoriController::class,'update']);// menyimpan perubahan data kategori
    Route::delete('/{id}',[KategoriController::class,'destroy']);// menghapus data kategori 
});

// m_supplier
Route::group(['prefix'=>'supplier'], function(){
    Route::get('/',[SupplierController::class,'index']);//menampilkan halaman awal
    Route::post('/list',[SupplierController::class,'list']);//menampilkan data supplier bentuk json / datatables
    Route::get('/create',[SupplierController::class,'create']);// meanmpilkan bentuk form untuk tambah supplier
    Route::post('/',[SupplierController::class,'store']);//menyimpan supplier data baru 
    Route::get('/{id}',[SupplierController::class,'show']); // menampilkan detail supplier
    Route::get('/{id}/edit',[SupplierController::class,'edit']);// menampilkan halaman form edit supplier
    Route::put('/{id}',[SupplierController::class,'update']);// menyimpan perubahan data supplier
    Route::delete('/{id}',[SupplierController::class,'destroy']);// menghapus data supplier 
});

//m_barang
Route::group(['prefix'=>'barang'], function(){
    Route::get('/',[BarangController::class,'index']);//menampilkan halaman awal
    Route::post('/list',[BarangController::class,'list']);//menampilkan data barang bentuk json / datatables
    Route::get('/create',[BarangController::class,'create']);// meanmpilkan bentuk form untuk tambah barang
    Route::post('/',[BarangController::class,'store']);//menyimpan barang data baru 
    Route::get('/{id}',[BarangController::class,'show']); // menampilkan detail barang
    Route::get('/{id}/edit',[BarangController::class,'edit']);// menampilkan halaman form edit barang
    Route::put('/{id}',[BarangController::class,'update']);// menyimpan perubahan data baramg
    Route::delete('/{id}',[BarangController::class,'destroy']);// menghapus data barang
});