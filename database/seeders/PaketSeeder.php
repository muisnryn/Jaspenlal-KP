<?php

namespace Database\Seeders;

use App\Models\Paket;
use Illuminate\Database\Seeder;

class PaketSeeder extends Seeder
{
    public function run(): void
    {
        // Paket untuk Skala Kecil / Mikro
        Paket::firstOrCreate(
            ['nama_paket' => 'Paket Sertifikasi Silver'],
            [
                'harga'      => 2500000,
                'deskripsi'  => 'Cocok untuk Usaha Kecil/Mikro. Maksimal 50 menu dan 1 fasilitas/outlet.',
            ]
        );

        // Paket untuk Skala Menengah
        Paket::firstOrCreate(
            ['nama_paket' => 'Paket Sertifikasi Gold'],
            [
                'harga'      => 10000000,
                'deskripsi'  => 'Cocok untuk Usaha Menengah. Maksimal 50 menu dan 1 fasilitas/outlet.',
            ]
        );

        // Paket untuk Skala Besar
        Paket::firstOrCreate(
            ['nama_paket' => 'Paket Sertifikasi Platinum'],
            [
                'harga'      => 22000000,
                'deskripsi'  => 'Cocok untuk Usaha Besar (Industri). Maksimal 50 menu dan 1 fasilitas/outlet.',
            ]
        );
    }
}
