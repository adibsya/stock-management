<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pembayaran_penjualan', function (Blueprint $table) {
            $table->decimal('jumlah_bayar', 15, 2)->default(0)->after('jumlah');
            $table->string('metode_pembayaran')->default('tunai')->after('jumlah_bayar');
            $table->text('catatan')->nullable()->after('metode_pembayaran');
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran_penjualan', function (Blueprint $table) {
            $table->dropColumn(['jumlah_bayar', 'metode_pembayaran', 'catatan']);
        });
    }
};
