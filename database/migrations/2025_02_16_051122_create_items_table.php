<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration //Membuat migration dengan class anonim yang mewarisi Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) { //Membuat tabel baru bernama items
            $table->id(); // Membuat kolom id sebagai primary key
            $table->string('name'); //Menambahkan kolom name dengan tipe VARCHAR
            $table->text('description'); //Menambahkan kolom description dengan tipe TEXT 
            $table->timestamps(); // Menambahkan kolom created_at dan updated_at secara otomatis.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');//menghapus tabel jika tabel sudah ada
    }
};
