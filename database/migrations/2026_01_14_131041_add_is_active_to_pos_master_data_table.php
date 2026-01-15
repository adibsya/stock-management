<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pos_master_data', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('urutan');
        });
    }

    public function down()
    {
        Schema::table('pos_master_data', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
