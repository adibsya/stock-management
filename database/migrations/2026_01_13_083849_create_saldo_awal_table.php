<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('saldo_awal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coa_id');
            $table->year('tahun');
            $table->decimal('debit', 18, 2)->default(0);
            $table->decimal('kredit', 18, 2)->default(0);
            $table->timestamps();

            $table->foreign('coa_id')
                  ->references('id')
                  ->on('coa');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saldo_awal');
    }
};
