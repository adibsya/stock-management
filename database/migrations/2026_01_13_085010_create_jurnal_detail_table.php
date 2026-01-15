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
        Schema::create('jurnal_detail', function (Blueprint $table) {
    $table->id();

    $table->foreignId('jurnal_id')
          ->references('id')
          ->on('jurnal')
          ->cascadeOnDelete();

    $table->foreignId('coa_id')
          ->references('id')
          ->on('coa');

    $table->decimal('debit', 18, 2)->default(0);
    $table->decimal('kredit', 18, 2)->default(0);
    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_detail');
    }
};
