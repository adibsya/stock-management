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
        Schema::create('retur', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->enum('jenis_retur', ['retur_penjualan', 'retur_pembelian']);
            $table->string('referensi_faktur');
            $table->foreignId('barang_id')->constrained('barang')->cascadeOnDelete();
            $table->integer('jumlah');
            $table->text('alasan')->nullable();
            $table->enum('kondisi_barang', ['bagus', 'rusak'])->default('bagus');
            $table->enum('aksi_stok', ['kembali_ke_stok', 'buang'])->default('kembali_ke_stok');
            $table->decimal('nilai_pengembalian', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retur');
    }
};
