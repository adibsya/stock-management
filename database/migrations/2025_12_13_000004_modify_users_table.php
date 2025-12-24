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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'admin', 'viewer'])->default('viewer')->after('password');
            $table->unsignedBigInteger('gudang_id')->nullable()->after('role');
            $table->foreign('gudang_id')->references('id')->on('gudang')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key constraint for gudang_id if exists, then drop the column
        if (Schema::hasColumn('users', 'gudang_id')) {
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropForeign(['gudang_id']);
                });
            } catch (\Throwable $e) {}
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('gudang_id');
            });
        }
        // Drop kolom role jika ada
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }
};
