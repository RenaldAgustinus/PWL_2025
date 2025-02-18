# Membuat CRUD Laravel Sederhana NO CSS menggunakan Laravel dengan Eloquent ORM
## 1. Langkah-Langkah Instalasi
- Install Composer 
- Pastikan PHP versi 8.1 sudah terinstall atau lebih ( Laragon )
- buka terminal dan install laravel ( versi 10)

    ![Deskripsi Gambar](./Gambar/image1.png)

- Gantilah nama_proyek dengan nama yang Anda inginkan untuk aplikasi Laravel Anda.
- Masuk ke Direktori Proyek: Setelah instalasi selesai, masuk ke direktori proyek Anda:
    
    ![Deskripsi Gambar](./Gambar/image2.png)
- Jalankan Server Lokal: Anda dapat menjalankan server pengembangan lokal dengan perintah:
    
    ![Deskripsi Gambar](./Gambar/image3.png)
## 2. Persiapan Proyek CRUD
- buatlah proyek Laravel dengan nama crud-app
    
    ![Deskripsi Gambar](./Gambar/image4.png)
- jalankan server
    
    ![Deskripsi Gambar](./Gambar/image5.png)
## 3. Buat Database
-   Buat Database: Buat database baru di MySQL, misalnya crud_db.
- Konfigurasi Database: Edit file .env untuk mengatur koneksi database

![Deskripsi Gambar](./Gambar/image6.png)
## 4. Buat Model dan Migrasi
- Buat Model dan Migrasi

![Deskripsi Gambar](./Gambar/image7.png)
- Edit Migrasi: Buka file migrasi di database/migrations/xxxx_xx_xx_create_items_table.php dan tambahkan kolom:

![Deskripsi Gambar](./Gambar/image8.png)
- Jalankan Migrasi

![Deskripsi Gambar](./Gambar/image9.png)
## 5. Buat Controller
- Buat Controller

![Deskripsi Gambar](./Gambar/image10.png)
- Implementasikan CRUD di Controller: Buka app/Http/Controllers/ItemController.php dan tambahkan kode berikut:

![Deskripsi Gambar](./Gambar/image11A.png)
![Deskripsi Gambar](./Gambar/image11B.png)

## 6. Buat Routing
-   Edit File Routing: Buka routes/web.php dan tambahkan

![Deskripsi Gambar](./Gambar/image12.png)

## 7. Buat View
- Buat Folder Views: Buat folder items di resources/views.
-   Buat File View:
     1. index.blade.php

     ![Deskripsi Gambar](./Gambar/image13.png)

     2. create.blade.php

     ![Deskripsi Gambar](./Gambar/image14.png)

     3. edit.blade.php

     ![Deskripsi Gambar](./Gambar/image15.png)
     
     4. show.blade.php

     ![Deskripsi Gambar](./Gambar/image16.png)

## 8. Done
- Jalankan 

  ![Deskripsi Gambar](./Gambar/image17.png)

- hasilnya

  ![Deskripsi Gambar](./Gambar/image18.png)

