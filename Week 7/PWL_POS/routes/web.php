<?php

// Import semua controller yang akan digunakan
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;

// Menentukan pola parameter 'id' agar hanya menerima angka 0-9
Route::pattern('id', '[0-9]+');

// Route ke halaman login
Route::get('login', [AuthController::class, 'login'])->name('login');
// Proses login (form post)
Route::post('login', [AuthController::class, 'postlogin']);
// Logout, hanya bisa diakses jika user sudah login
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

// Route ke halaman registrasi
Route::get('register', [AuthController::class, 'register'])->name('register');
// Proses registrasi (form post)
Route::post('register', [AuthController::class, 'postRegister']);

// Group route yang hanya bisa diakses oleh user yang sudah login
Route::middleware(['auth'])->group(function () {

    // Halaman utama (dashboard/welcome)
    Route::get('/', [WelcomeController::class, 'index']);

    // Group route yang hanya bisa diakses oleh user dengan role 'ADM' (Admin)
    Route::middleware(['authorize:ADM'])->group(function () {
        Route::group(['prefix' => 'user'], function () {
            Route::get('/', [UserController::class, 'index']); // Menampilkan daftar user
            Route::post('/list', [UserController::class, 'list']); // Mengambil data list user
            Route::get('/create', [UserController::class, 'create']); // Menampilkan form tambah user
            Route::post('/', [UserController::class, 'store']); // Simpan user baru
            Route::get('/create_ajax', [UserController::class, 'create_ajax']); // Form tambah user via ajax
            Route::post('/ajax', [UserController::class, 'store_ajax']); // Simpan user via ajax
            Route::get('/{id}', [UserController::class, 'show']); // Detail user
            Route::get('/{id}/edit', [UserController::class, 'edit']); // Form edit user
            Route::put('/{id}', [UserController::class, 'update']); // Update user
            Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']); // Edit via ajax
            Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']); // Update ajax
            Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); // Konfirmasi hapus ajax
            Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // Hapus ajax
            Route::delete('/{id}', [UserController::class, 'destroy']); // Hapus user
        });
    });

    // Group route untuk Admin dan Manager
    Route::middleware(['authorize:ADM,MNG'])->group(function () {

        // Prefix untuk route kategori
        Route::group(['prefix' => 'kategori'], function () {
            Route::get('/', [KategoriController::class, 'index']); // Daftar kategori
            Route::post('/list', [KategoriController::class, 'list']); // Data list kategori
            Route::get('/create', [KategoriController::class, 'create']); // Form tambah
            Route::post('/', [KategoriController::class, 'store']); // Simpan kategori
            Route::get('/create_ajax', [KategoriController::class, 'create_ajax']);
            Route::post('/ajax', [KategoriController::class, 'store_ajax']);
            Route::get('/{id}', [KategoriController::class, 'show']); // Detail kategori
            Route::get('/{id}/edit', [KategoriController::class, 'edit']); // Form edit
            Route::put('/{id}', [KategoriController::class, 'update']); // Simpan update
            Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']);
            Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']);
            Route::delete('/{id}', [KategoriController::class, 'destroy']); // Hapus kategori
        });

        // Prefix untuk route level (role user)
        Route::group(['prefix' => 'level'], function () {
            Route::get('/', [LevelController::class, 'index']);
            Route::post('/list', [LevelController::class, 'list']);
            Route::get('/create', [LevelController::class, 'create']);
            Route::post('/', [LevelController::class, 'store']);
            Route::get('/create_ajax', [LevelController::class, 'create_ajax']);
            Route::post('/ajax', [LevelController::class, 'store_ajax']);
            Route::get('/{id}', [LevelController::class, 'show']);
            Route::get('/{id}/edit', [LevelController::class, 'edit']);
            Route::put('/{id}', [LevelController::class, 'update']);
            Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']);
            Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']);
            Route::delete('/{id}', [LevelController::class, 'destroy']);
        });
    });

    // Group route untuk Admin, Manager, dan Staff
    Route::middleware(['authorize:ADM,MNG,STF'])->group(function () {

        // Prefix untuk supplier
        Route::group(['prefix' => 'supplier'], function () {
            Route::get('/', [SupplierController::class, 'index']);
            Route::post('/list', [SupplierController::class, 'list']);
            Route::get('/create', [SupplierController::class, 'create']);
            Route::post('/', [SupplierController::class, 'store']);
            Route::get('/create_ajax', [SupplierController::class, 'create_ajax']);
            Route::post('/ajax', [SupplierController::class, 'store_ajax']);
            Route::get('/{id}', [SupplierController::class, 'show']);
            Route::get('/{id}/edit', [SupplierController::class, 'edit']);
            Route::put('/{id}', [SupplierController::class, 'update']);
            Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']);
            Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']);
            Route::delete('/{id}', [SupplierController::class, 'destroy']);
        });

        // Prefix untuk barang
        Route::group(['prefix' => 'barang'], function () {
            Route::get('/', [BarangController::class, 'index']);
            Route::post('/list', [BarangController::class, 'list']);
            Route::get('/create', [BarangController::class, 'create']);
            Route::post('/', [BarangController::class, 'store']);
            Route::get('/create_ajax', [BarangController::class, 'create_ajax']);
            Route::post('/ajax', [BarangController::class, 'store_ajax']);
            Route::get('/{id}', [BarangController::class, 'show']);
            Route::get('/{id}/edit', [BarangController::class, 'edit']);
            Route::put('/{id}', [BarangController::class, 'update']);
            Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']);
            Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']);
            Route::delete('/{id}', [BarangController::class, 'destroy']);
        });
    });
});
