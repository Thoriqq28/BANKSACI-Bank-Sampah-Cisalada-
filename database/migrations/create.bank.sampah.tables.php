<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Utama Nasabah/User
        Schema::create('nasabahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke auth Laravel
            $table->string('kode_nasabah')->unique(); // Contoh: BS-0008
            $table->string('nama');
            $table->string('tingkatan')->default('WARGA PEDULI');
            $table->string('alamat')->nullable();
            $table->integer('saldo')->default(0);
            $table->timestamps();
        });

        // Tabel Riwayat Setoran Sampah (Mutasi Masuk)
        Schema::create('setorans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('nasabahs')->onDelete('cascade');
            $table->decimal('berat', 8, 2); // Dalam satuan Kg
            $table->integer('total_harga'); // Konversi Rupiah
            $table->timestamps();
        });

        // Tabel Riwayat Penarikan Uang (Mutasi Keluar)
        Schema::create('penarikans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('nasabahs')->onDelete('cascade');
            $table->integer('nominal');
            $table->string('status')->default('proses'); // proses, selesai
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penarikans');
        Schema::dropIfExists('setorans');
        Schema::dropIfExists('nasabahs');
    }
};