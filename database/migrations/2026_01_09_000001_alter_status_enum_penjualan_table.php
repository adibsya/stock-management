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
        Schema::table('penjualan', function (Blueprint $table) {
            // Change enum values: remove 'draft', add 'belum_lunas', and keep 'selesai'
            $table->enum('status', ['selesai', 'belum_lunas'])->default('belum_lunas')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->enum('status', ['selesai', 'draft'])->default('draft')->change();
        });
    }
};
