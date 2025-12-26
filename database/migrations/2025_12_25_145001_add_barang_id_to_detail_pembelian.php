<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('detail_pembelian', function (Blueprint $table) {
            $table->foreignId('barang_id')
                  ->after('pembelian_id')
                  ->constrained('barang_master')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('detail_pembelian', function (Blueprint $table) {
            $table->dropForeign(['barang_id']);
            $table->dropColumn('barang_id');
        });
    }
};
