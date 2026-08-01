<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('penarikan', 'status')) {
            Schema::table('penarikan', function (Blueprint $table) {
                $table->enum('status', ['pending', 'selesai', 'cancel'])->default('pending')->after('jumlah');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('penarikan', 'status')) {
            Schema::table('penarikan', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};