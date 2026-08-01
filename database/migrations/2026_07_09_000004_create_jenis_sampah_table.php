<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_sampah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_sampah')->onDelete('cascade');
            $table->string('nama');
            $table->decimal('harga_per_kg', 12, 2)->default(0);
            $table->string('satuan', 20)->default('kg');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_sampah');
    }
};