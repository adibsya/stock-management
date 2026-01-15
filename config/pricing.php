<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Nukleer/Rokok Bundle Pricing
    |--------------------------------------------------------------------------
    |
    | Konfigurasi harga bundling untuk produk rokok/nukleer.
    | Format: [jumlah_minimum => harga_bundle]
    | Diurutkan dari yang terbesar ke terkecil.
    |
    */
    'nukleer_bundles' => [
        600 => 5100000,  // 600 pcs = Rp 5.100.000
        100 => 870000,   // 100 pcs = Rp 870.000
        10  => 91000,    // 10 pcs = Rp 91.000
    ],

    /*
    |--------------------------------------------------------------------------
    | Nukleer Product Identifiers
    |--------------------------------------------------------------------------
    |
    | Kata kunci untuk mengidentifikasi produk rokok/nukleer.
    | Akan dicek di nama_barang dan kode_barang (case-insensitive).
    |
    */
    'nukleer_identifiers' => [
        'nama' => ['nuklerr'],
        'kode' => ['rkk'],
    ],
];
