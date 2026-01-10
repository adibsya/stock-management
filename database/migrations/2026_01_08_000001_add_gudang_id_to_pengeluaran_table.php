<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->unsignedBigInteger('gudang_id')->nullable()->after('keterangan');
            $table->foreign('gudang_id')->references('id')->on('gudang')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->dropForeign(['gudang_id']);
            $table->dropColumn('gudang_id');
        });
    }
};
