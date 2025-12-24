<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Gudang;
use App\Models\Barang;
use App\Models\Pelanggan;
use App\Models\Pemasok;
use App\Models\Pengaturan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Super Admin User
        User::updateOrCreate(
            ['email' => 'superadmin@ngarumi.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]
        );

        // Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@ngarumi.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'gudang_id' => 1, // Akan diupdate setelah Gudang Utama dibuat
            ]
        );

        // Create Viewer User
        User::updateOrCreate(
            ['email' => 'viewer@ngarumi.com'],
            [
                'name' => 'Viewer',
                'password' => Hash::make('password'),
                'role' => 'viewer',
            ]
        );

        // Create Gudang
        $gudangUtama = Gudang::updateOrCreate(
            ['nama_gudang' => 'Gudang Utama'],
            [
                'lokasi' => 'Jl. Raya Utama No. 1, Surabaya',
            ]
        );

        // Update admin user dengan gudang_id yang benar
        $admin = User::where('email', 'admin@ngarumi.com')->first();
        if ($admin && $gudangUtama) {
            $admin->gudang_id = $gudangUtama->id;
            $admin->save();
        }

        $gudangCabang = Gudang::updateOrCreate(
            ['nama_gudang' => 'Gudang Cabang'],
            [
                'lokasi' => 'Jl. Raya Cabang No. 2, Surabaya',
            ]
        );

        // Create Pemasok
        $pemasokList = [
            ['nama_supplier' => 'PT. Indofood', 'kontak' => '081234567890', 'alamat' => 'Jakarta', 'catatan_termin_pembayaran' => 'Pembayaran 30 hari'],
            ['nama_supplier' => 'CV. Sumber Makmur', 'kontak' => '081234567891', 'alamat' => 'Surabaya', 'catatan_termin_pembayaran' => 'Cash on Delivery'],
            ['nama_supplier' => 'UD. Sejahtera', 'kontak' => '081234567892', 'alamat' => 'Malang', 'catatan_termin_pembayaran' => 'Pembayaran 14 hari'],
        ];

        foreach ($pemasokList as $pemasok) {
            Pemasok::updateOrCreate(['nama_supplier' => $pemasok['nama_supplier']], $pemasok);
        }

        // Create Pelanggan
        $pelangganList = [
            ['kode_pelanggan' => 'PLG-001', 'nama_pelanggan' => 'Toko Sejahtera', 'no_hp' => '081111111111', 'email' => 'sejahtera@gmail.com', 'jenis_pelanggan' => 'grosir', 'alamat' => 'Jl. Pasar No. 1'],
            ['kode_pelanggan' => 'PLG-002', 'nama_pelanggan' => 'Warung Bu Siti', 'no_hp' => '081222222222', 'email' => null, 'jenis_pelanggan' => 'grosir', 'alamat' => 'Jl. Kampung No. 5'],
            ['kode_pelanggan' => 'PLG-003', 'nama_pelanggan' => 'Budi Santoso', 'no_hp' => '081333333333', 'email' => 'budi@gmail.com', 'jenis_pelanggan' => 'eceran', 'alamat' => null],
        ];

        foreach ($pelangganList as $pelanggan) {
            Pelanggan::updateOrCreate(['kode_pelanggan' => $pelanggan['kode_pelanggan']], $pelanggan);
        }

        // Create Barang - Minyak Goreng GMNU
        $barangList = [
            // Minyak Goreng GMNU
            ['kode_barang' => 'MGR-001', 'nama_barang' => 'Minyak Kita 1L + GMNU 700ml (Bundling)', 'kategori' => 'Minyak Goreng', 'satuan' => 'paket', 'harga_beli' => 35000, 'harga_jual' => 42000, 'stok' => 50, 'stok_minimum' => 10, 'gudang_id' => $gudangUtama->id],
            ['kode_barang' => 'MGR-002', 'nama_barang' => 'GMNU Botol 800ml', 'kategori' => 'Minyak Goreng', 'satuan' => 'botol', 'harga_beli' => 18000, 'harga_jual' => 22000, 'stok' => 100, 'stok_minimum' => 20, 'gudang_id' => $gudangUtama->id],
            ['kode_barang' => 'MGR-003', 'nama_barang' => 'GMNU Botol 400ml', 'kategori' => 'Minyak Goreng', 'satuan' => 'botol', 'harga_beli' => 10000, 'harga_jual' => 13000, 'stok' => 120, 'stok_minimum' => 25, 'gudang_id' => $gudangUtama->id],
            ['kode_barang' => 'MGR-004', 'nama_barang' => 'GMNU Botol 1 Liter', 'kategori' => 'Minyak Goreng', 'satuan' => 'botol', 'harga_beli' => 22000, 'harga_jual' => 27000, 'stok' => 80, 'stok_minimum' => 15, 'gudang_id' => $gudangUtama->id],
            
            // Rokok NUKlerr (1 slop = 10 pack, 1 bal = 10 slop = 100 pack, 1 karton = 6 bal = 60 slop = 600 pack)
            ['kode_barang' => 'RKK-001', 'nama_barang' => 'NUKlerr 1 Slop', 'kategori' => 'Rokok', 'satuan' => 'slop', 'harga_beli' => 85000, 'harga_jual' => 91000, 'stok' => 50, 'stok_minimum' => 10, 'gudang_id' => $gudangUtama->id, 'keterangan' => 'Beli 2 slop bonus 1 pack'],
            ['kode_barang' => 'RKK-002', 'nama_barang' => 'NUKlerr 1 Bal', 'kategori' => 'Rokok', 'satuan' => 'bal', 'harga_beli' => 820000, 'harga_jual' => 870000, 'stok' => 20, 'stok_minimum' => 5, 'gudang_id' => $gudangUtama->id, 'keterangan' => 'Beli 1 bal bonus 5 pack'],
            ['kode_barang' => 'RKK-003', 'nama_barang' => 'NUKlerr 1 Karton', 'kategori' => 'Rokok', 'satuan' => 'karton', 'harga_beli' => 4800000, 'harga_jual' => 5100000, 'stok' => 5, 'stok_minimum' => 2, 'gudang_id' => $gudangUtama->id, 'keterangan' => 'Beli 1 karton bonus 3 slop'],
        ];

        foreach ($barangList as $barang) {
            Barang::updateOrCreate(['kode_barang' => $barang['kode_barang']], $barang);
        }

        // Create Pengaturan
        Pengaturan::updateOrCreate(
            ['kunci' => 'nama_toko'],
            ['nilai' => 'Ngarumi Store']
        );

        Pengaturan::updateOrCreate(
            ['kunci' => 'alamat_toko'],
            ['nilai' => 'Jl. Raya Utama No. 1, Surabaya']
        );

        Pengaturan::updateOrCreate(
            ['kunci' => 'telepon_toko'],
            ['nilai' => '031-12345678']
        );

        Pengaturan::updateOrCreate(
            ['kunci' => 'ppn'],
            ['nilai' => '0']
        );
    }
}
