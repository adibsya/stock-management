<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayaran_penjualan', function (Blueprint $table) {
            $table->decimal('pembayaran_terakhir', 15, 2)->default(0)->after('jumlah_bayar');
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran_penjualan', function (Blueprint $table) {
            $table->dropColumn('pembayaran_terakhir');
        });
    }
};
