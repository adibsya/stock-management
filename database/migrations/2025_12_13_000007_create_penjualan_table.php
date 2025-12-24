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
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('no_faktur')->unique();
            $table->date('tanggal');
            $table->foreignId('pelanggan_id')->nullable()->constrained('pelanggan')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('total_kotor', 15, 2)->default(0);
            $table->decimal('diskon_transaksi', 15, 2)->default(0);
            $table->decimal('pajak', 15, 2)->default(0);
            $table->decimal('total_bayar', 15, 2)->default(0);
            $table->string('metode_pembayaran')->default('tunai');
            $table->enum('mode_termin', ['cash', 'termin'])->default('cash');
            $table->date('jatuh_tempo')->nullable();
            $table->enum('status', ['selesai', 'draft'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
