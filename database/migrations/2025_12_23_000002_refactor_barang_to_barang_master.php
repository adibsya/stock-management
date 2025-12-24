<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            if (!Schema::hasColumn('barang', 'barang_master_id')) {
                $table->unsignedBigInteger('barang_master_id')->nullable()->after('id');
                $table->foreign('barang_master_id')->references('id')->on('barang_master')->onDelete('set null');
            }
            if (Schema::hasColumn('barang', 'kode_barang')) {
                $table->dropUnique(['kode_barang']);
                $table->dropColumn(['kode_barang']);
            }
            if (Schema::hasColumn('barang', 'nama_barang')) {
                $table->dropColumn(['nama_barang']);
            }
            if (Schema::hasColumn('barang', 'kategori')) {
                $table->dropColumn(['kategori']);
            }
            if (Schema::hasColumn('barang', 'satuan')) {
                $table->dropColumn(['satuan']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->string('kode_barang')->nullable();
            $table->string('nama_barang')->nullable();
            $table->string('kategori')->nullable();
            $table->string('satuan')->nullable();
            $table->dropForeign(['barang_master_id']);
            $table->dropColumn('barang_master_id');
        });
    }
};
