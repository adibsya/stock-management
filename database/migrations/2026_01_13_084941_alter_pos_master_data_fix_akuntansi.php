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
    Schema::table('pos_master_data', function (Blueprint $table) {

        if (Schema::hasColumn('pos_master_data', 'jenis')) {
            $table->dropColumn('jenis');
        }

        if (Schema::hasColumn('pos_master_data', 'kategori')) {
            $table->enum('kategori', ['aset','liabilitas','ekuitas'])->change();
        }

        if (Schema::hasColumn('pos_master_data', 'sub_kategori')) {
            $table->enum('sub_kategori', [
                'lancar','tidak_lancar',
                'pendek','panjang',
                'modal'
            ])->nullable()->change();
        }
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
