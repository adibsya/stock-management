<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mutasi_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang_master')->cascadeOnDelete();
            $table->integer('jumlah');
            $table->foreignId('gudang_asal_id')->constrained('gudang')->cascadeOnDelete();
            $table->foreignId('gudang_tujuan_id')->constrained('gudang')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('catatan')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('mutasi_stok');
    }
};
