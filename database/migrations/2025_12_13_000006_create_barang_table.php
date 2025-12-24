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
        Schema::dropIfExists('barang');
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_master_id');
            $table->foreign('barang_master_id')->references('id')->on('barang_master')->onDelete('cascade');
            $table->foreignId('gudang_id')->constrained('gudang')->onDelete('cascade');
            $table->foreignId('pemasok_id')->nullable()->constrained('pemasok')->nullOnDelete();
            $table->decimal('harga_beli', 15, 2)->default(0);
            $table->decimal('harga_jual', 15, 2)->default(0);
            $table->integer('stok')->default(0);
            $table->integer('stok_minimum')->default(0);
            $table->string('foto')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
