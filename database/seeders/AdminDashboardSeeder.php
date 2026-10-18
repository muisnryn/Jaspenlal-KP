<?php

namespace Database\Seeders;

use App\Models\KlienDetail;
use App\Models\Paket;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminDashboardSeeder extends Seeder
{
    public function run(): void
    {
        $paketData = [
            ['nama_paket' => 'Paket Sertifikasi Silver', 'harga' => 2500000, 'deskripsi' => 'Untuk usaha kecil atau mikro.'],
            ['nama_paket' => 'Paket Sertifikasi Gold', 'harga' => 10000000, 'deskripsi' => 'Untuk usaha menengah.'],
            ['nama_paket' => 'Paket Sertifikasi Platinum', 'harga' => 22000000, 'deskripsi' => 'Untuk usaha besar atau industri.'],
        ];

        $paketIds = collect($paketData)
            ->map(fn (array $data) => Paket::firstOrCreate(
                ['nama_paket' => $data['nama_paket']],
                $data
            )->id)
            ->all();

        $konsultan = User::firstOrCreate(
            ['email' => 'konsultan@test.com'],
            [
                'name' => 'Konsultan',
                'username' => 'konsultan',
                'password' => Hash::make('password'),
                'role' => 'konsultan',
            ]
        );

        $progressCounts = [
            1 => 5,
            2 => 5,
            3 => 5,
            4 => 12,
            5 => 6,
            6 => 6,
            7 => 5,
            8 => 6,
            9 => 5,
            10 => 5,
        ];

        $progressLevels = [];
        foreach ($progressCounts as $level => $count) {
            array_push($progressLevels, ...array_fill(0, $count, $level));
        }

        $statusByLevel = [
            1 => 'Menunggu DP',
            2 => 'Verifikasi DP',
            3 => 'DP Bermasalah / Ditolak',
            4 => 'Antrean Plotting Konsultan',
            5 => 'Unggah Dokumen Persyaratan',
            6 => 'Evaluasi & Daftar Bahan',
            7 => 'Audit Lapangan Berlangsung',
            8 => 'Proses Sidang Fatwa',
            9 => 'Menunggu Pelunasan (40%)',
            10 => 'Sertifikat Terbit - Selesai',
        ];

        $rejectedIndexes = [7, 13, 22, 31, 43, 57];
        $businessScales = ['kecil', 'menengah', 'besar'];
        $demoPassword = Hash::make('password');
        $companyNames = [
            'Rasa Nusantara',
            'Dapur Sejahtera',
            'Pangan Berkah',
            'Kreasi Kuliner',
            'Mitra Boga',
            'Cita Rasa Mandiri',
        ];

        foreach ($progressLevels as $offset => $progressLevel) {
            $index = $offset + 1;
            $suffix = str_pad((string) $index, 3, '0', STR_PAD_LEFT);
            $email = "klien.demo.{$suffix}@example.test";
            $client = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => "Klien Demo {$suffix}",
                    'username' => "klien-demo-{$suffix}",
                    'password' => $demoPassword,
                    'role' => 'klien',
                    'no_telepon' => '0812' . str_pad((string) $index, 8, '0', STR_PAD_LEFT),
                ]
            );

            $scale = $businessScales[($index - 1) % count($businessScales)];
            KlienDetail::updateOrCreate(
                ['user_id' => $client->id],
                [
                    'nama_perusahaan' => $companyNames[($index - 1) % count($companyNames)] . " {$suffix}",
                    'npwp' => sprintf('01.%03d.%03d.%d-%03d.%03d', 100 + $index, 200 + $index, $index % 10, 300 + $index, 400 + $index),
                    'alamat_perusahaan' => "Jl. Pangan Halal No. {$index}, Bandung",
                    'skala_usaha' => $scale,
                ]
            );

            $paketId = $paketIds[($index - 1) % count($paketIds)];
            $paketHarga = Paket::findOrFail($paketId)->harga;
            $totalMenu = 20 + (($index * 7) % 100);
            $totalOutlet = 1 + ($index % 4);
            $biayaMenuTambahan = $totalMenu > 50
                ? (int) ceil(($totalMenu - 50) / 30) * 500000
                : 0;
            $biayaOutletTambahan = ($totalOutlet - 1) * 1500000;
            $isRejected = in_array($index, $rejectedIndexes, true);
            $createdAt = now()->subDays(60 - $index)->setTime(9 + ($index % 8), ($index * 7) % 60);

            $pendaftaran = Pendaftaran::updateOrCreate(
                ['no_pendaftaran' => "DEMO-REG-{$suffix}"],
                [
                    'user_id' => $client->id,
                    'paket_id' => $paketId,
                    'konsultan_id' => $progressLevel >= 5 ? $konsultan->id : null,
                    'total_menu' => $totalMenu,
                    'total_outlet' => $totalOutlet,
                    'luar_jabodetabek' => $index % 2 === 0,
                    'biaya_dasar' => $paketHarga,
                    'biaya_menu_tambahan' => $biayaMenuTambahan,
                    'biaya_outlet_tambahan' => $biayaOutletTambahan,
                    'total_biaya' => $paketHarga + $biayaMenuTambahan + $biayaOutletTambahan,
                    'progress_level' => $progressLevel,
                    'status' => $isRejected ? 'Ditolak' : $statusByLevel[$progressLevel],
                    'alasan_penolakan' => $isRejected
                        ? 'Dokumen persyaratan perlu diperbaiki sebelum pendaftaran dapat dilanjutkan.'
                        : null,
                    'tgl_audit' => $progressLevel >= 7 ? $createdAt->toDateString() : null,
                    'tgl_sidang' => $progressLevel >= 9 ? $createdAt->toDateString() : null,
                ]
            );

            $pendaftaran->created_at = $createdAt;
            $pendaftaran->updated_at = $createdAt;
            $pendaftaran->saveQuietly();
        }
    }
}
