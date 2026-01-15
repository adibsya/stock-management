<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PosMasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode' => '1-01-01', 'nama' => 'Kas dan Bank', 'jenis' => 'aktiva', 'kategori' => 'aset', 'sub_kategori' => 'lancar', 'level' => 2, 'normal_saldo' => 'debit', 'urutan' => 1],
            ['kode' => '1-01-02', 'nama' => 'Piutang Usaha', 'jenis' => 'aktiva', 'kategori' => 'aset', 'sub_kategori' => 'lancar', 'level' => 2, 'normal_saldo' => 'debit', 'urutan' => 2],
            ['kode' => '1-01-04', 'nama' => 'Persediaan Barang', 'jenis' => 'aktiva', 'kategori' => 'aset', 'sub_kategori' => 'lancar', 'level' => 2, 'normal_saldo' => 'debit', 'urutan' => 4],
            ['kode' => '2-01-01', 'nama' => 'Hutang Usaha', 'jenis' => 'pasiva', 'kategori' => 'liabilitas', 'sub_kategori' => 'pendek', 'level' => 2, 'normal_saldo' => 'kredit', 'urutan' => 1],
            ['kode' => '4-01-01', 'nama' => 'Pendapatan Penjualan', 'jenis' => 'pasiva', 'kategori' => 'ekuitas', 'sub_kategori' => 'modal', 'level' => 2, 'normal_saldo' => 'kredit', 'urutan' => 1],
            ['kode' => '5-01-01', 'nama' => 'HPP', 'jenis' => 'aktiva', 'kategori' => 'aset', 'sub_kategori' => 'lancar', 'level' => 2, 'normal_saldo' => 'debit', 'urutan' => 1],
        ];

        foreach ($data as $item) {
            DB::table('pos_master_data')->updateOrInsert(
                ['kode' => $item['kode']],
                array_merge($item, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}

