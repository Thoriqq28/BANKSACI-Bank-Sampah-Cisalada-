<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('nasabah', 'nomor_rumah')) {
            Schema::table('nasabah', function (Blueprint $table) {
                $table->string('nomor_rumah', 10)->nullable()->after('rw');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('nasabah', 'nomor_rumah')) {
            Schema::table('nasabah', function (Blueprint $table) {
                $table->dropColumn('nomor_rumah');
            });
        }
    }
};