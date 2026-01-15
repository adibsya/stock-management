<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('coa', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->enum('tipe', [
                'aset',
                'liabilitas',
                'ekuitas',
                'pendapatan',
                'beban'
            ]);
            $table->unsignedBigInteger('pos_master_data_id');
            $table->boolean('aktif')->default(true);
            $table->timestamps();

            $table->foreign('pos_master_data_id')
                  ->references('id')
                  ->on('pos_master_data');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coa');
    }
};
