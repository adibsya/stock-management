<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pembayaran_penjualan', function (Blueprint $table) {
            $table->string('status_bayar')->default('pending')->after('metode_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran_penjualan', function (Blueprint $table) {
            $table->dropColumn('status_bayar');
        });
    }
};
