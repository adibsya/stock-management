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
        Schema::table('pembayaran_pembelian', function (Blueprint $table) {
            $table->decimal('jumlah', 15, 2)->default(0)->after('jumlah_bayar');
            $table->date('tanggal_jatuh_tempo')->nullable()->after('jumlah');
            $table->string('status')->default('belum_lunas')->after('tanggal_jatuh_tempo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran_pembelian', function (Blueprint $table) {
            $table->dropColumn(['jumlah', 'tanggal_jatuh_tempo', 'status']);
        });
    }
};
