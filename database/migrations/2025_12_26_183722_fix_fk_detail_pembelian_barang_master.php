<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('detail_pembelian', function (Blueprint $table) {
            // DROP FK LAMA
            $table->dropForeign(['barang_id']);
        });

        Schema::table('detail_pembelian', function (Blueprint $table) {
            // BUAT FK BARU KE barang_master
            $table->foreign('barang_master_id')
                ->references('id')
                ->on('barang_master')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('detail_pembelian', function (Blueprint $table) {
            $table->dropForeign(['barang_master_id']);

            // rollback ke FK lama (kalau perlu)
            $table->foreign('barang_id')
                ->references('id')
                ->on('barang')
                ->cascadeOnDelete();
        });
    }
};
