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



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
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
// Menentukan pola parameter ID agar hanya menerima angka
Route::pattern('id', '[0-9]+');

// Menampilkan halaman form login
Route::get('login',[AuthController::class, 'login'])->name('login');
// Memproses data login yang dikirim dari form
Route::post('login',[AuthController::class, 'postlogin']);
// Keluar dari akun (logout) hanya bisa jika sudah login
Route::get('logout',[AuthController::class, 'logout'])->middleware('auth');

// Menampilkan form pendaftaran user baru
Route::get('register', [AuthController::class, 'register'])->name('register');
// Menyimpan data user baru dari form pendaftaran
Route::post('register', [AuthController::class, 'store_user']);

Route::middleware(['auth'])->group(function(){

    // Halaman utama yang ditampilkan setelah login
    Route::get('/',[WelcomeController::class,'index']);

    // Upload foto profil pengguna
    Route::post('/update-photo', [UserController::class, 'update_photo']);

    // Menghapus foto profil pengguna
    Route::post('/delete-photo', [UserController::class, 'delete_photo']);

    // Khusus untuk role ADM
    Route::middleware(['authorize:ADM'])->group(function() {
        Route::group(['prefix'=>'user'], function(){
            Route::get('/',[UserController::class,'index']); // Tampilkan daftar semua pengguna
            Route::post('/list',[UserController::class,'list']); // Ambil data pengguna dalam format JSON (DataTables)
            Route::get('/create',[UserController::class,'create']); // Formulir untuk menambah pengguna baru
            Route::post('/',[UserController::class,'store']); // Simpan data pengguna yang baru ditambahkan
            Route::get('/create_ajax',[UserController::class,'create_ajax']); // Formulir tambah pengguna via AJAX
            Route::post('/ajax',[UserController::class,'store_ajax']); // Simpan data pengguna via AJAX
            Route::get('/{id}',[UserController::class,'show']); // Tampilkan informasi detail pengguna
            Route::get('/{id}/edit',[UserController::class,'edit']); // Formulir untuk mengubah data pengguna
            Route::put('/{id}',[UserController::class,'update']); // Simpan perubahan pada data pengguna
            Route::get('/{id}/edit_ajax',[UserController::class,'edit_ajax']); // Form edit via AJAX
            Route::put('/{id}/update_ajax',[UserController::class,'update_ajax']); // Simpan perubahan data via AJAX
            Route::get('/{id}/delete_ajax',[UserController::class, 'confirm_ajax']); // Konfirmasi hapus via AJAX
            Route::delete('/{id}/delete_ajax',[UserController::class, 'delete_ajax']); // Hapus data pengguna via AJAX
            Route::delete('/{id}',[UserController::class,'destroy']); // Hapus pengguna secara langsung
            Route::get('/import',[UserController::class,'import']); // Formulir unggah file Excel
            Route::post('/import_ajax',[UserController::class,'import_ajax']); // Proses import dari file Excel
            Route::get('/export_excel',[UserController::class,'export_excel']); // Unduh data pengguna dalam format Excel
            Route::get('/export_pdf',[UserController::class, 'export_pdf']); // Unduh data pengguna dalam format PDF
        });

        // Routes untuk pengelolaan level pengguna
        Route::prefix('level')->group(function () {
            Route::get('/',[LevelController::class,'index']); // Menampilkan daftar level
            Route::post('/list',[LevelController::class,'list']); // Data level dalam format JSON (untuk DataTables)
            Route::get('/create',[LevelController::class,'create']); // Formulir tambah level
            Route::post('/',[LevelController::class,'store']); // Simpan level baru
            Route::get('/create_ajax',[LevelController::class,'create_ajax']); // Form tambah level via AJAX
            Route::post('/ajax',[LevelController::class,'store_ajax']); // Simpan data level via AJAX
            Route::get('/{id}',[LevelController::class,'show']); // Detail informasi level
            Route::get('/{id}/edit',[LevelController::class,'edit']); // Form edit level
            Route::put('/{id}',[LevelController::class,'update']); // Simpan perubahan level
            Route::get('/{id}/edit_ajax',[LevelController::class,'edit_ajax']); // Edit level via AJAX
            Route::put('/{id}/update_ajax',[LevelController::class,'update_ajax']); // Simpan update level via AJAX
            Route::get('/{id}/delete_ajax',[LevelController::class,'confirm_ajax']); // Konfirmasi penghapusan via AJAX
            Route::delete('/{id}/delete_ajax',[LevelController::class,'delete_ajax']); // Hapus level via AJAX
            Route::delete('/{id}',[LevelController::class,'destroy']); // Hapus level
            Route::get('/import',[LevelController::class,'import']); // Form upload Excel
            Route::post('/import_ajax',[LevelController::class,'import_ajax']); // Proses import Excel
            Route::get('/export_excel',[LevelController::class,'export_excel']); // Export ke Excel
            Route::get('/export_pdf',[LevelController::class, 'export_pdf']); // Export ke PDF
        });
    });

    // Routes kategori hanya untuk ADM dan MNG
    Route::middleware(['authorize:ADM,MNG'])->prefix('kategori')->group(function () {
        Route::get('/',[KategoriController::class,'index']); // Tampilkan semua kategori
        Route::post('/list',[KategoriController::class,'list']); // Ambil data kategori untuk DataTables
        Route::get('/create',[KategoriController::class,'create']); // Form tambah kategori
        Route::post('/',[KategoriController::class,'store']); // Simpan kategori baru
        Route::get('/create_ajax',[KategoriController::class,'create_ajax']); // Form tambah via AJAX
        Route::post('/ajax',[KategoriController::class,'store_ajax']); // Simpan kategori via AJAX
        Route::get('/{id}',[KategoriController::class,'show']); // Lihat detail kategori
        Route::get('/{id}/edit',[KategoriController::class,'edit']); // Form edit kategori
        Route::put('/{id}',[KategoriController::class,'update']); // Simpan perubahan kategori
        Route::get('/{id}/edit_ajax',[KategoriController::class,'edit_ajax']); // Edit via AJAX
        Route::put('/{id}/update_ajax',[KategoriController::class,'update_ajax']); // Update data via AJAX
        Route::get('/{id}/delete_ajax',[KategoriController::class,'confirm_ajax']); // Konfirmasi hapus via AJAX
        Route::delete('/{id}/delete_ajax',[KategoriController::class,'delete_ajax']); // Proses hapus via AJAX
        Route::delete('/{id}',[KategoriController::class,'destroy']); // Hapus kategori
        Route::get('/import',[KategoriController::class,'import']); // Form import Excel
        Route::post('/import_ajax',[KategoriController::class,'import_ajax']); // Proses import Excel via AJAX
        Route::get('/export_excel',[KategoriController::class,'export_excel']); // Unduh Excel
        Route::get('/export_pdf',[KategoriController::class, 'export_pdf']); // Unduh PDF
    });

    // Routes barang untuk ADM dan MNG
    Route::middleware(['authorize:ADM,MNG'])->prefix('barang')->group(function () {
        Route::get('/',[BarangController::class,'index']); // Tampilkan semua barang
        Route::post('/list',[BarangController::class,'list']); // Data barang dalam JSON
        Route::get('/create',[BarangController::class,'create']); // Form tambah barang
        Route::post('/',[BarangController::class,'store']); // Simpan data barang
        Route::get('/create_ajax',[BarangController::class,'create_ajax']); // Form tambah AJAX
        Route::post('/ajax',[BarangController::class,'store_ajax']); // Tambah data barang via AJAX
        Route::get('/{id}',[BarangController::class,'show']); // Detail barang
        Route::get('/{id}/edit',[BarangController::class,'edit']); // Form edit barang
        Route::put('/{id}',[BarangController::class,'update']); // Update data barang
        Route::get('/{id}/edit_ajax',[BarangController::class,'edit_ajax']); // Edit barang AJAX
        Route::put('/{id}/update_ajax',[BarangController::class,'update_ajax']); // Simpan update AJAX
        Route::get('/{id}/delete_ajax',[BarangController::class,'confirm_ajax']); // Konfirmasi hapus AJAX
        Route::delete('/{id}/delete_ajax',[BarangController::class,'delete_ajax']); // Hapus AJAX
        Route::delete('/{id}',[BarangController::class,'destroy']); // Hapus barang
        Route::get('/import',[BarangController::class,'import']); // Form upload Excel
        Route::post('/import_ajax',[BarangController::class,'import_ajax']); // Proses import Excel
        Route::get('/export_excel',[BarangController::class,'export_excel']); // Ekspor ke Excel
        Route::get('/export_pdf',[BarangController::class, 'export_pdf']); // Ekspor ke PDF
    });

    // Routes supplier untuk ADM dan MNG
    Route::middleware(['authorize:MNG,ADM'])->prefix('supplier')->group(function () {
        Route::get('/',[SupplierController::class,'index']); // Tampilkan daftar supplier
        Route::post('/list',[SupplierController::class,'list']); // Data supplier JSON
        Route::get('/create',[SupplierController::class,'create']); // Form tambah supplier
        Route::post('/',[SupplierController::class,'store']); // Simpan supplier baru
        Route::get('/create_ajax',[SupplierController::class,'create_ajax']); // Form tambah AJAX
        Route::post('/ajax',[SupplierController::class,'store_ajax']); // Simpan supplier AJAX
        Route::get('/{id}',[SupplierController::class,'show']); // Detail supplier
        Route::get('/{id}/edit',[SupplierController::class,'edit']); // Formulir edit supplier
        Route::put('/{id}',[SupplierController::class,'update']); // Update data supplier
        Route::get('/{id}/edit_ajax',[SupplierController::class,'edit_ajax']); // Edit AJAX
        Route::put('/{id}/update_ajax',[SupplierController::class,'update_ajax']); // Simpan update AJAX
        Route::get('/{id}/delete_ajax',[SupplierController::class,'confirm_ajax']); // Konfirmasi hapus AJAX
        Route::delete('/{id}/delete_ajax',[SupplierController::class,'delete_ajax']); // Hapus via AJAX
        Route::delete('/{id}',[SupplierController::class,'destroy']); // Hapus supplier
        Route::get('/import',[SupplierController::class,'import']); // Form unggah Excel
        Route::post('/import_ajax',[SupplierController::class,'import_ajax']); // Proses unggah Excel
        Route::get('/export_excel',[SupplierController::class,'export_excel']); // Unduh Excel
        Route::get('/export_pdf',[SupplierController::class, 'export_pdf']); // Unduh PDF
    });
});
