<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    protected $fillable = [
        'user_id',
        'paket_id',
        'konsultan_id',
        'no_pendaftaran',
        'total_menu',
        'total_outlet',
        'luar_jabodetabek',
        'biaya_dasar',
        'biaya_menu_tambahan',
        'biaya_outlet_tambahan',
        'total_biaya',
        'progress_level',
        'status',
        'alasan_penolakan',
        // --- Kolom Dokumen Final Admin ---
        'file_ketetapan_halal',
        'tgl_sidang',
        'file_sertifikat',     
        // --- Kolom Dokumen Klien (Jika simpan path di sini) ---
        'surat_permohonan',
        'formulir_pendaftaran',
        'nib',
        'penyelia_halal',
        'fasilitas_pabrik',
        'daftar_produk',
        'daftar_bahan',
        'diagram_alir',
        'manual_sjh',
        // --- Kolom Audit Konsultan ---
        'tgl_audit',
        'file_lha'
    ];

    // Relasi User/Klien
    public function user() { return $this->belongsTo(User::class, 'user_id'); }

    // Relasi Konsultan
    public function konsultan() { return $this->belongsTo(User::class, 'konsultan_id'); }

    public function paket() { return $this->belongsTo(Paket::class, 'paket_id'); }

    // Gunakan satu nama relasi saja agar tidak bingung (pembayarans)
    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'pendaftaran_id');
    }

    public function bahans() { return $this->hasMany(Bahan::class, 'pendaftaran_id'); }

    public function klienDetail()
    {
        return $this->hasOne(KlienDetail::class, 'user_id', 'user_id');
    }

    public function dokumens()
    {
        return $this->hasMany(Dokumen::class, 'pendaftaran_id');
    }
}
