<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::pattern('id', '[0-9]+'); // Pastikan parameter {id} hanya berupa angka

// Rute otentikasi
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'postRegister']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


// Semua rute di bawah ini hanya bisa diakses jika sudah login
Route::middleware(['auth'])->group(function () {
    Route::get('/', [WelcomeController::class, 'index']);
    
    // Semua user dapat melihat profile
    Route::middleware((['authorize:ADM,MNG,STF,KSR']))->group(function(){
        Route::get('/user/profile', [UserController::class, 'profile_page']);
        Route::post('/user/update_picture', [UserController::class, 'update_picture']);
    });
    // Hanya admin yang dapat akses
    Route::middleware(['authorize:ADM'])->group(function(){
        Route::group(['prefix' => 'user'], function () {
            Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax']);
            Route::get('/', [UserController::class, 'index']);
            Route::post('/list', [UserController::class, 'list']);
            Route::get('/create', [UserController::class, 'create']);
            Route::post('/', [UserController::class, 'store']);
            Route::get('/create_ajax', [UserController::class, 'create_ajax']);
            Route::post('/ajax', [UserController::class, 'store_ajax']);
            Route::get('/{id}', [UserController::class, 'show']);
            Route::get('/{id}/edit', [UserController::class, 'edit']);
            Route::put('/{id}', [UserController::class, 'update']);
            Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']);
            Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']);
            Route::delete('/{id}', [UserController::class, 'destroy']);
            Route::get('import', [UserController::class, 'import']);
            Route::post('import_ajax', [UserController::class, 'import_ajax']);
            Route::get('export_excel', [UserController::class, 'export_excel']);
            Route::get('export_pdf', [UserController::class, 'export_pdf']);
        });
    });


    // artinya semua route di dalam group ini harus punya role ADM (Administrator)
    Route::middleware(['authorize:ADM'])->group(function(){
        Route::group(['prefix' => 'level'], function () {
            Route::get('/{id}/show_ajax', [LevelController::class, 'show_ajax']); // Menampilkan detail level menggunakan AJAX
            Route::get('/', [LevelController::class, 'index']); // menampilkan halaman awal level
            Route::post("/list", [LevelController::class, 'list']); // menampilkan data level dalam bentuk json untuk datatables
            Route::get('/create', [LevelController::class, 'create']); // menampilkan halaman form tambah level
            Route::post('/', [LevelController::class, 'store']); // menyimpan data level baru
            //Create Menggunakan AJAX
            Route::get('/create_ajax', [LevelController::class, 'create_ajax']); // Menampilkan halaman form tambah level Ajax
            Route::post('/ajax', [LevelController::class, 'store_ajax']); // Menyimpan data level baru Ajax
            Route::get('/{id}', [LevelController::class, 'show']); // menampilkan detail level
            Route::get('/{id}/edit', [LevelController::class, 'edit']); // menampilkan halaman form edit level
            Route::put('/{id}', [LevelController::class, 'update']); // menyimpan perubahan data level
            //Edit Menggunakan AJAX
            Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']); // Menampilkan halaman form edit level Ajax
            Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']); // Menyimpan perubahan data level Ajax
            //Delete Menggunakan AJAX
            Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete level Ajax
            Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']); // Untuk hapus data level Ajax
            Route::delete('/{id}', [LevelController::class, 'destroy']); // menghapus data level
            // Import menggunakan AJAX
            Route::get('import', [LevelController::class, 'import']); // ajax form upload excel
            Route::post('import_ajax', [LevelController::class, 'import_ajax']); // ajax import excel
            Route::get('export_excel', [LevelController::class, 'export_excel']); //export excel
            Route::get('export_excel', [LevelController::class, 'export_excel']); //export excel
            Route::get('export_pdf', [LevelController::class, 'export_pdf']); //export pdf
        });
    });

     // artinya semua route di dalam group ini harus punya role ADM (Administrator) dan MNG (Manager)
     Route::middleware(['authorize:ADM,MNG'])->group(function(){
        Route::group(['prefix' => 'kategori'], function () {
            Route::get('/{id}/show_ajax', [KategoriController::class, 'show_ajax']); // Menampilkan detail barang menggunakan AJAX

            Route::get('/', [KategoriController::class, 'index']); // menampilkan halaman awal kategori
            Route::post("/list", [KategoriController::class, 'list']); // menampilkan data kategori dalam bentuk json untuk datatables
            Route::get('/create', [KategoriController::class, 'create']); // menampilkan halaman form tambah kategori
            Route::post('/', [KategoriController::class, 'store']); // menyimpan data kategori baru 
            // Create menggunakan AJAX
            Route::get('/create_ajax', [KategoriController::class, 'create_ajax']); // menampilkan halaman form tambah kategori ajax
            Route::post('/ajax', [KategoriController::class, 'store_ajax']); // menyimpan data kategori baru ajax
            Route::get('/{id}', [KategoriController::class, 'show']); // menampilkan detail kategori
            Route::get('/{id}/edit', [KategoriController::class, 'edit']); // menampilkan halaman form edit kategori
            Route::put('/{id}', [KategoriController::class, 'update']); // menyimpan perubahan data kategori
            // Edit menggunakan AJAX
            Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']); // menampilkan halaman form edit kategori ajax
            Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']); // menyimpan perubahan data kategori ajax
            // Delete menggunakan AJAX
            Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']); //menampilkan form confirm delete kategori ajax
            Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']); // menghapus data kategori ajax
            Route::delete('/{id}', [KategoriController::class, 'destroy']); // menghapus data kategori
            // Import menggunakan AJAX
            Route::get('import', [KategoriController::class, 'import']); // ajax form upload excel
            Route::post('import_ajax', [KategoriController::class, 'import_ajax']); // ajax import excel
            Route::get('export_excel', [KategoriController::class, 'export_excel']); //export excel
            Route::get('export_pdf', [KategoriController::class, 'export_pdf']); //export pdf
        });
    });
    
    // Staff hanya bisa melihat data barang
    Route::middleware(['authorize:ADM,MNG,STF'])->group(function(){
        Route::group(['prefix' => 'barang'], function () {
            Route::get('/', [BarangController::class, 'index']); // Menampilkan daftar barang
            Route::post('/list', [BarangController::class, 'list']); // Menampilkan data barang dalam bentuk JSON untuk datatables
            Route::get('/{id}/show_ajax', [BarangController::class, 'show_ajax']); // Menampilkan detail barang menggunakan AJAX
        });
    });

    // ADM & MNG bisa menambah, mengedit, dan menghapus barang
    Route::middleware(['authorize:ADM,MNG'])->group(function () {
        Route::group(['prefix' => 'barang'], function () {
            Route::get('/create', [BarangController::class, 'create']); // menampilkan halaman form tambah barang
             Route::post('/', [BarangController::class, 'store']); // menyimpan data barang baru
             Route::get('/create_ajax', [BarangController::class, 'create_ajax']); // Menampilkan halaman form tambah barang Ajax
             Route::post('/ajax', [BarangController::class, 'store_ajax']); // Menyimpan data barang baru Ajax
             Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']); // Menampilkan halaman form edit barang Ajax
             Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']); // Menyimpan perubahan data barang Ajax
             Route::get('/{id}/edit', [BarangController::class, 'edit']); // menampilkan halaman form edit barang
             Route::put('/{id}', [BarangController::class, 'update']); // menyimpan perubahan data barang
             Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete barang Ajax
             Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']); // Untuk hapus data barang Ajax
             Route::delete('/{id}', [BarangController::class, 'destroy']); // menghapus data barang
             Route::get('/import', [BarangController::class, 'import']); // ajax form upload excel
             Route::post('/import_ajax', [BarangController::class, 'import_ajax']); // AJAX import excel
             Route::get('export_excel', [BarangController::class, 'export_excel']); //export excel
             Route::get('export_pdf', [BarangController::class, 'export_pdf']); //export pdf
             Route::get('export_pdf', [BarangController::class, 'export_pdf']); //export pdf

        });
    });

// artinya semua route di dalam group ini harus punya role ADM (Administrator) dan MNG (Manager)
// artinya semua route di dalam group ini harus punya role ADM (Administrator) dan MNG (Manager)
    Route::middleware(['authorize:ADM,MNG'])->group(function(){
        Route::group(['prefix' => 'supplier'], function () {
            Route::get('/{id}/show_ajax', [SupplierController::class, 'show_ajax'])->name('supplier.show_ajax');

            Route::get('/', [SupplierController::class, 'index']);
            Route::post('/list', [SupplierController::class, 'list']);
            Route::get('/create', [SupplierController::class, 'create']);
            Route::post('/', [SupplierController::class, 'store']);
            // Create menggunakan AJAX
            Route::get('/create_ajax', [SupplierController::class, 'create_ajax']); // menampilkan halaman form tambah Supplier ajax
            Route::post('/ajax', [SupplierController::class, 'store_ajax']); // menyimpan data Supplier baru ajax
            Route::get('/{id}', [SupplierController::class, 'show']);
            Route::get('/{id}/edit', [SupplierController::class, 'edit']);
            Route::put('/{id}', [SupplierController::class, 'update']);
            // Edit menggunakan AJAX
            Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']); // menampilkan halaman form edit Supplier ajax
            Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']); // menyimpan perubahan data Supplier ajax
            // Delete menggunakan AJAX
            Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']); //menampilkan form confirm delete Supplier ajax
            Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']); // menghapus data Supplier ajax
            Route::delete('/{id}', [SupplierController::class, 'destroy']);
            Route::get('import', [SupplierController::class, 'import']); // ajax form upload excel
            Route::post('import_ajax', [SupplierController::class, 'import_ajax']); // ajax import excel
            Route::post('/store', [SupplierController::class, 'store'])->name('supplier.store');
            Route::get('export_excel', [SupplierController::class, 'export_excel']); //export excel
            Route::get('export_pdf', [SupplierController::class, 'export_pdf']); //export pdf
        });
    });
});