<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('setoran_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('setoran_id')->constrained('setoran')->onDelete('cascade');
            $table->foreignId('jenis_sampah_id')->constrained('jenis_sampah')->onDelete('cascade');
            $table->decimal('berat', 8, 2);
            $table->decimal('harga', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('setoran_detail');
    }
};