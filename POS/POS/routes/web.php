<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Routing\Router;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

/*
|----------------------------------------------------------------------
| Daftar Route
|----------------------------------------------------------------------
|
| Daftar route yang digunakan dalam aplikasi ini.
| Route dikelola oleh RouteServiceProvider.
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/level', [LevelController::class,'index']);
// Route::get('/kategori', [KategoriController::class,'index']);
// Route::get('/user', [UserController::class,'index']);


// Route::get('/user/tambah',[UserController::class,'tambah']);
// Route::post('/user/tambah_simpan',[UserController::class,'tambah_simpan']);
// Route::get('/user/ubah/{id}',[UserController::class,'ubah']);
// Route::put('/user/ubah_simpan/{id}',[UserController::class,'ubah_simpan']);
// Route::get('/user/hapus/{id}',[UserController::class,'hapus']);

Route::pattern('id', '[0-9]+');
Route::get('login',[AuthController::class, 'login'])->name('login');
Route::post('login',[AuthController::class, 'postlogin']);
Route::get('logout',[AuthController::class, 'logout'])->middleware('auth');
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'store_user']);

Route::middleware(['auth'])->group(function(){

    Route::get('/',[WelcomeController::class,'index']);
    Route::get('/profile', [UserController::class, 'profilePage']);
    Route::post('/user/editPhoto', [UserController::class, 'editPhoto']);

Route::middleware(['authorize:ADM'])->group(function() {
Route::group(['prefix'=>'user'], function(){
    Route::get('/',[UserController::class,'index']); // Halaman Utama
    Route::post('/list',[UserController::class,'list']); // Daftar User (JSON/Datatables)
    Route::get('/create',[UserController::class,'create']); // Form Tambah User
    Route::post('/',[UserController::class,'store']); // Simpan User Baru
    Route::get('/create_ajax',[UserController::class,'create_ajax']); // Form Tambah User Ajax
    Route::post('/ajax',[UserController::class,'store_ajax']); // Simpan User (Ajax)
    Route::get('/{id}',[UserController::class,'show']); // Detail User
    Route::get('/{id}/edit',[UserController::class,'edit']); // Form Edit User
    Route::put('/{id}',[UserController::class,'update']); // Simpan Perubahan User
    Route::get('/{id}/edit_ajax',[UserController::class,'edit_ajax']); // Form Edit User Ajax
    Route::put('/{id}/update_ajax',[UserController::class,'update_ajax']); // Simpan Perubahan User (Ajax)
    Route::get('/{id}/delete_ajax',[UserController::class, 'confirm_ajax']); // Konfirmasi Hapus (Ajax)
    Route::delete('/{id}/delete_ajax',[UserController::class, 'delete_ajax']); // Hapus User (Ajax)
    Route::delete('/{id}',[UserController::class,'destroy']); // Hapus User
    Route::get('/import',[UserController::class,'import']); // Form Import Excel
    Route::post('/import_ajax',[UserController::class,'import_ajax']); // Import Excel (Ajax)
    Route::get('/export_excel',[UserController::class,'export_excel']); // Export Excel
    Route::get('/export_pdf',[UserController::class, 'export_pdf']); // Export PDF
});
});

Route::middleware(['authorize:ADM'])->prefix('level')->group(function () {
    Route::get('/',[LevelController::class,'index']); // Halaman Utama Level
    Route::post('/list',[LevelController::class,'list']); // Daftar Level (JSON/Datatables)
    Route::get('/create',[LevelController::class,'create']); // Form Tambah Level
    Route::post('/',[LevelController::class,'store']); // Simpan Level Baru
    Route::get('/create_ajax',[LevelController::class,'create_ajax']); // Form Tambah Level Ajax
    Route::post('/ajax',[LevelController::class,'store_ajax']); // Simpan Level (Ajax)
    Route::get('/{id}',[LevelController::class,'show']); // Detail Level
    Route::get('/{id}/edit',[LevelController::class,'edit']); // Form Edit Level
    Route::put('/{id}',[LevelController::class,'update']); // Simpan Perubahan Level
    Route::get('/{id}/edit_ajax',[LevelController::class,'edit_ajax']); // Form Edit Level Ajax
    Route::put('/{id}/update_ajax',[LevelController::class,'update_ajax']); // Simpan Perubahan Level (Ajax)
    Route::get('/{id}/delete_ajax',[LevelController::class,'confirm_ajax']); // Konfirmasi Hapus Level (Ajax)
    Route::delete('/{id}/delete_ajax',[LevelController::class,'delete_ajax']); // Hapus Level (Ajax)
    Route::delete('/{id}',[LevelController::class,'destroy']); // Hapus Level
    Route::get('/import',[LevelController::class,'import']); // Form Import Excel
    Route::post('/import_ajax',[LevelController::class,'import_ajax']); // Import Excel (Ajax)
    Route::get('/export_excel',[LevelController::class,'export_excel']); // Export Excel
    Route::get('/export_pdf',[LevelController::class, 'export_pdf']); // Export PDF
});

Route::middleware(['authorize:ADM,MNG'])->prefix('kategori')->group(function () {
    Route::get('/',[KategoriController::class,'index']); // Halaman Utama Kategori
    Route::post('/list',[KategoriController::class,'list']); // Daftar Kategori (JSON/Datatables)
    Route::get('/create',[KategoriController::class,'create']); // Form Tambah Kategori
    Route::post('/',[KategoriController::class,'store']); // Simpan Kategori Baru
    Route::get('/create_ajax',[KategoriController::class,'create_ajax']); // Form Tambah Kategori Ajax
    Route::post('/ajax',[KategoriController::class,'store_ajax']); // Simpan Kategori (Ajax)
    Route::get('/{id}',[KategoriController::class,'show']); // Detail Kategori
    Route::get('/{id}/edit',[KategoriController::class,'edit']); // Form Edit Kategori
    Route::put('/{id}',[KategoriController::class,'update']); // Simpan Perubahan Kategori
    Route::get('/{id}/edit_ajax',[KategoriController::class,'edit_ajax']); // Form Edit Kategori Ajax
    Route::put('/{id}/update_ajax',[KategoriController::class,'update_ajax']); // Simpan Perubahan Kategori (Ajax)
    Route::get('/{id}/delete_ajax',[KategoriController::class,'confirm_ajax']); // Konfirmasi Hapus Kategori (Ajax)
    Route::delete('/{id}/delete_ajax',[KategoriController::class,'delete_ajax']); // Hapus Kategori (Ajax)
    Route::delete('/{id}',[KategoriController::class,'destroy']); // Hapus Kategori
    Route::get('/import',[KategoriController::class,'import']); // Form Import Excel
    Route::post('/import_ajax',[KategoriController::class,'import_ajax']); // Import Excel (Ajax)
    Route::get('/export_excel',[KategoriController::class,'export_excel']); // Export Excel
    Route::get('/export_pdf',[KategoriController::class, 'export_pdf']); // Export PDF
});


Route::middleware(['authorize:ADM,MNG'])->prefix('barang')->group(function () {
    Route::get('/',[BarangController::class,'index']); // Halaman Utama Barang
    Route::post('/list',[BarangController::class,'list']); // Daftar Barang (JSON/Datatables)
    Route::get('/create',[BarangController::class,'create']); // Form Tambah Barang
    Route::post('/',[BarangController::class,'store']); // Simpan Barang Baru
    Route::get('/create_ajax',[BarangController::class,'create_ajax']); // Form Tambah Barang Ajax
    Route::post('/ajax',[BarangController::class,'store_ajax']); // Simpan Barang (Ajax)
    Route::get('/{id}',[BarangController::class,'show']); // Detail Barang
    Route::get('/{id}/edit',[BarangController::class,'edit']); // Form Edit Barang
    Route::put('/{id}',[BarangController::class,'update']); // Simpan Perubahan Barang
    Route::get('/{id}/edit_ajax',[BarangController::class,'edit_ajax']); // Form Edit Barang Ajax
    Route::put('/{id}/update_ajax',[BarangController::class,'update_ajax']); // Simpan Perubahan Barang (Ajax)
    Route::get('/{id}/delete_ajax',[BarangController::class,'confirm_ajax']); // Konfirmasi Hapus Barang (Ajax)
    Route::delete('/{id}/delete_ajax',[BarangController::class,'delete_ajax']); // Hapus Barang (Ajax)
    Route::delete('/{id}',[BarangController::class,'destroy']); // Hapus Barang
    Route::get('/import',[BarangController::class,'import']); // Form Import Excel
    Route::post('/import_ajax',[BarangController::class,'import_ajax']); // Import Excel (Ajax)
    Route::get('/export_excel',[BarangController::class,'export_excel']); // Export Excel
    Route::get('/export_pdf',[BarangController::class, 'export_pdf']); // Export PDF
});


Route::middleware(['authorize:ADM,MNG'])->prefix('supplier')->group(function () {
    Route::get('/',[SupplierController::class,'index']); // Halaman Utama Supplier
    Route::post('/list',[SupplierController::class,'list']); // Daftar Supplier (JSON/Datatables)
    Route::get('/create',[SupplierController::class,'create']); // Form Tambah Supplier
    Route::post('/',[SupplierController::class,'store']); // Simpan Supplier Baru
    Route::get('/create_ajax',[SupplierController::class,'create_ajax']); // Form Tambah Supplier Ajax
    Route::post('/ajax',[SupplierController::class,'store_ajax']); // Simpan Supplier (Ajax)
    Route::get('/{id}',[SupplierController::class,'show']); // Detail Supplier
    Route::get('/{id}/edit',[SupplierController::class,'edit']); // Form Edit Supplier
    Route::put('/{id}',[SupplierController::class,'update']); // Simpan Perubahan Supplier
    Route::get('/{id}/edit_ajax',[SupplierController::class,'edit_ajax']); // Form Edit Supplier Ajax
    Route::put('/{id}/update_ajax',[SupplierController::class,'update_ajax']); // Simpan Perubahan Supplier (Ajax)
    Route::get('/{id}/delete_ajax',[SupplierController::class,'confirm_ajax']); // Konfirmasi Hapus Supplier (Ajax)
    Route::delete('/{id}/delete_ajax',[SupplierController::class,'delete_ajax']); // Hapus Supplier (Ajax)
    Route::delete('/{id}',[SupplierController::class,'destroy']); // Hapus Supplier
    Route::get('/import',[SupplierController::class,'import']); // Form Import Excel
    Route::post('/import_ajax',[SupplierController::class,'import_ajax']); // Import Excel (Ajax)
    Route::get('/export_excel',[SupplierController::class,'export_excel']); // Export Excel
    Route::get('/export_pdf',[SupplierController::class, 'export_pdf']); // Export PDF
});
});