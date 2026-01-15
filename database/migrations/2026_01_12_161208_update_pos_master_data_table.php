<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pos_master_data', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('id');
            $table->enum('kategori', ['aset', 'liabilitas', 'ekuitas'])->after('jenis');
            $table->enum('sub_kategori', [
                'lancar',
                'tidak_lancar',
                'pendek',
                'panjang',
                'modal'
            ])->nullable()->after('kategori');
            $table->enum('normal_saldo', ['debit', 'kredit'])->after('sub_kategori');
            $table->integer('level')->default(1)->after('normal_saldo');
            $table->integer('urutan')->default(0)->after('level');

            $table->foreign('parent_id')
                  ->references('id')
                  ->on('pos_master_data')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('pos_master_data', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn([
                'parent_id',
                'kategori',
                'sub_kategori',
                'normal_saldo',
                'level',
                'urutan'
            ]);
        });
    }
};
