<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->foreignId('gudang_id')->nullable()->after('user_id')->constrained('gudang')->nullOnDelete();
        });
        Schema::table('detail_penjualan', function (Blueprint $table) {
            $table->foreignId('gudang_id')->nullable()->after('penjualan_id')->constrained('gudang')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->dropForeign(['gudang_id']);
            $table->dropColumn('gudang_id');
        });
        Schema::table('detail_penjualan', function (Blueprint $table) {
            $table->dropForeign(['gudang_id']);
            $table->dropColumn('gudang_id');
        });
    }
};
