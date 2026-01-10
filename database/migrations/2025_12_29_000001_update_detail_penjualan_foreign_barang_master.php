<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('detail_penjualan', function (Blueprint $table) {
            // Drop old foreign key if exists
            $table->dropForeign(['barang_id']);
            // Add new foreign key to barang_master
            $table->foreign('barang_id')
                ->references('id')
                ->on('barang_master')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('detail_penjualan', function (Blueprint $table) {
            $table->dropForeign(['barang_id']);
            $table->foreign('barang_id')
                ->references('id')
                ->on('barang')
                ->onDelete('cascade');
        });
    }
};
