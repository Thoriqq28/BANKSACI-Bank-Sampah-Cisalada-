<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jenis_sampah', function (Blueprint $table) {
            if (!Schema::hasColumn('jenis_sampah', 'harga_beli')) {
                $table->integer('harga_beli')->default(0)->after('nama');
            }
        });
    }

    public function down(): void
    {
        Schema::table('jenis_sampah', function (Blueprint $table) {
            if (Schema::hasColumn('jenis_sampah', 'harga_beli')) {
                $table->dropColumn('harga_beli');
            }
        });
    }
};