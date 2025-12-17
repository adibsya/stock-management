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
        Schema::create('pembelian', function (Blueprint $table) {
            $table->id();
            $table->string('no_faktur_supplier');
            $table->date('tanggal');
            $table->foreignId('pemasok_id')->constrained('pemasok')->cascadeOnDelete();
            $table->decimal('total_biaya', 15, 2)->default(0);
            $table->date('jatuh_tempo')->nullable();
            $table->enum('status_bayar', ['lunas', 'belum_lunas'])->default('belum_lunas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian');
    }
};
