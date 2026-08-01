<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pakai Schema::table langsung & tambahkan ->change()
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 50)->default('nasabah')->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kembalikan ke struktur awal jika di-rollback (sesuaikan jika sebelumnya enum)
            $table->string('role', 255)->change(); 
        });
    }
};