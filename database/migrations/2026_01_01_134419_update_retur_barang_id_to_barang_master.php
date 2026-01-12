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
        Schema::table('retur', function (Blueprint $table) {
            // Drop foreign key constraint ke tabel barang
            $table->dropForeign(['barang_id']);
            
            // Update foreign key ke tabel barang_master
            $table->foreign('barang_id')->references('id')->on('barang_master')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('retur', function (Blueprint $table) {
            // Kembalikan ke tabel barang
            $table->dropForeign(['barang_id']);
            $table->foreign('barang_id')->references('id')->on('barang')->cascadeOnDelete();
        });
    }
};
