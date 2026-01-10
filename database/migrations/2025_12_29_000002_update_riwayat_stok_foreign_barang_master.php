<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('riwayat_stok', function (Blueprint $table) {
            $table->dropForeign(['barang_id']);
            $table->foreign('barang_id')
                ->references('id')
                ->on('barang_master')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('riwayat_stok', function (Blueprint $table) {
            $table->dropForeign(['barang_id']);
            $table->foreign('barang_id')
                ->references('id')
                ->on('barang')
                ->onDelete('cascade');
        });
    }
};
