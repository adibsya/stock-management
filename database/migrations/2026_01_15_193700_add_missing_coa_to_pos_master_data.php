<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $data = [
            ['kode' => '2-01-01', 'nama' => 'Hutang Usaha', 'jenis' => 'pasiva', 'kategori' => 'liabilitas', 'sub_kategori' => 'pendek', 'level' => 2, 'normal_saldo' => 'kredit', 'urutan' => 1, 'is_active' => 1],
            ['kode' => '4-01-01', 'nama' => 'Pendapatan Penjualan', 'jenis' => 'pasiva', 'kategori' => 'ekuitas', 'sub_kategori' => 'modal', 'level' => 2, 'normal_saldo' => 'kredit', 'urutan' => 1, 'is_active' => 1],
            ['kode' => '5-01-01', 'nama' => 'HPP', 'jenis' => 'aktiva', 'kategori' => 'aset', 'sub_kategori' => 'lancar', 'level' => 2, 'normal_saldo' => 'debit', 'urutan' => 1, 'is_active' => 1],
        ];

        foreach ($data as $item) {
            $exists = DB::table('pos_master_data')->where('kode', $item['kode'])->exists();
            if (!$exists) {
                DB::table('pos_master_data')->insert(array_merge($item, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }

    public function down(): void
    {
        DB::table('pos_master_data')->whereIn('kode', ['2-01-01', '4-01-01', '5-01-01'])->delete();
    }
};
