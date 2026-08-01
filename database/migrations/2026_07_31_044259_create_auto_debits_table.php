<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auto_debits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('nasabah')->onDelete('cascade');
            $table->string('keterangan'); // contoh: "Iuran Sampah" / "Admin Bulanan"
            $table->decimal('nominal', 15, 2);
            $table->integer('tanggal_eksekusi')->default(1); // Tanggal potong bulanan
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_executed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auto_debits');
    }
};