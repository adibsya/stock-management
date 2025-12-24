# Penjelasan Proyek Stock Management

Proyek ini adalah aplikasi manajemen stok berbasis Laravel yang digunakan untuk mengelola barang, gudang, pelanggan, pemasok, pembelian, penjualan, pengeluaran, dan laporan terkait. Berikut adalah penjelasan singkat mengenai struktur dan fungsionalitas utama:

## Struktur Folder Utama
- **app/**: Berisi kode utama aplikasi, termasuk controller, model, service, dan komponen Livewire.
- **bootstrap/**: File inisialisasi aplikasi Laravel.
- **config/**: Konfigurasi aplikasi seperti database, mail, cache, dll.
- **database/**: Berisi migrasi, seeder, dan factory untuk database.
- **public/**: Entry point aplikasi web dan aset publik.
- **resources/**: Berisi file view (Blade), CSS, dan JS.
- **routes/**: Definisi rute aplikasi (web dan console).
- **storage/**: Penyimpanan file sementara, cache, dan log.
- **tests/**: Berisi file untuk testing aplikasi.
- **vendor/**: Dependency yang diinstal melalui Composer.

## Fitur Utama
- **Manajemen Barang & Gudang**: CRUD barang dan gudang, serta pengelolaan stok.
- **Transaksi Pembelian & Penjualan**: Pencatatan pembelian, penjualan, dan pembayaran terkait.
- **Laporan**: Laporan stok, laba rugi, dan riwayat transaksi.
- **Manajemen Pengguna**: Pengelolaan data user dan hak akses.
- **Livewire**: Komponen interaktif untuk form dan tabel data secara realtime.

## Cara Menjalankan
1. Install dependency dengan `composer install` dan `npm install`.
2. Konfigurasi database di `config/database.php`.
3. Jalankan migrasi dengan `php artisan migrate`.
4. Jalankan aplikasi dengan `php artisan serve`.

## Kontak & Dokumentasi
- Dokumentasi lebih lanjut dapat dilihat di file README.md.
- Untuk pertanyaan, silakan hubungi pengembang melalui kontak yang tertera di README.
