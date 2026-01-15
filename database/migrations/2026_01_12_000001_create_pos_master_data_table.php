<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pos_master_data', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->enum('jenis', ['aktiva', 'pasiva']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_master_data');
    }
};
