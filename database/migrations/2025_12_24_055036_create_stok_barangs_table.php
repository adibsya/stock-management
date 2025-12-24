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
        Schema::create('stok_barangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_master_id');
            $table->unsignedBigInteger('gudang_id');
            $table->integer('jumlah')->default(0);
            $table->timestamps();

            $table->foreign('barang_master_id')->references('id')->on('barang_master')->onDelete('cascade');
            $table->foreign('gudang_id')->references('id')->on('gudang')->onDelete('cascade');
            $table->unique(['barang_master_id', 'gudang_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_barangs');
    }
};
