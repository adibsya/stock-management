<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('neraca', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_id')->constrained('pos_master_data')->onDelete('cascade');
            $table->date('tanggal');
            $table->decimal('jumlah', 20, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('neraca');
    }
};
