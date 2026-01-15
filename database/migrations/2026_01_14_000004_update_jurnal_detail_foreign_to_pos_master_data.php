<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('jurnal_detail', function (Blueprint $table) {
            // Drop old foreign key if exists
            try {
                $table->dropForeign(['coa_id']);
            } catch (\Exception $e) {}
            // Add new foreign key to pos_master_data
            $table->foreign('coa_id')
                ->references('id')
                ->on('pos_master_data')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('jurnal_detail', function (Blueprint $table) {
            $table->dropForeign(['coa_id']);
            // Tidak otomatis restore ke foreign key sebelumnya
        });
    }
};
