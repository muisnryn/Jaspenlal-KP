@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-md-4">
    <div class="row row-cols-2 row-cols-md-3 row-cols-xl-5 g-3 mb-4 admin-metrics">
        <div class="col">
            <article class="card admin-metric-card admin-metric-card--plotting h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="admin-metric-icon"><i class="fas fa-user-tag"></i></div>
                    <span class="admin-metric-kicker">OPERASIONAL</span>
                </div>
                <div class="mt-4">
                    <p class="admin-metric-label mb-1">Antrean Plotting</p>
                    <p class="admin-metric-value mb-2">{{ $stats['pending_plotting'] ?? 0 }}</p>
                    <p class="admin-metric-caption mb-0">Menunggu penugasan konsultan</p>
                </div>
            </article>
        </div>
        <div class="col">
            <article class="card admin-metric-card admin-metric-card--sidang h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="admin-metric-icon"><i class="fas fa-gavel"></i></div>
                    <span class="admin-metric-kicker">OPERASIONAL</span>
                </div>
                <div class="mt-4">
                    <p class="admin-metric-label mb-1">Menunggu Sidang</p>
                    <p class="admin-metric-value mb-2">{{ $stats['pending_sidang'] ?? 0 }}</p>
                    <p class="admin-metric-caption mb-0">Dalam antrean sidang fatwa</p>
                </div>
            </article>
        </div>
        <div class="col">
            <article class="card admin-metric-card admin-metric-card--certificate h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="admin-metric-icon"><i class="fas fa-certificate"></i></div>
                    <span class="admin-metric-kicker">SERTIFIKASI</span>
                </div>
                <div class="mt-4">
                    <p class="admin-metric-label mb-1">Siap Terbit</p>
                    <p class="admin-metric-value mb-2">{{ $stats['pending_sertifikat'] ?? 0 }}</p>
                    <p class="admin-metric-caption mb-0">Menunggu sertifikat final</p>
                </div>
            </article>
        </div>
        <div class="col">
            <article class="card admin-metric-card admin-metric-card--rejected h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="admin-metric-icon"><i class="fas fa-circle-exclamation"></i></div>
                    <span class="admin-metric-kicker">PERLU DITINJAU</span>
                </div>
                <div class="mt-4">
                    <p class="admin-metric-label mb-1">Pendaftaran Ditolak</p>
                    <p class="admin-metric-value mb-2">{{ $stats['total_ditolak'] ?? 0 }}</p>
                    <p class="admin-metric-caption mb-0">Periksa alasan penolakan</p>
                </div>
            </article>
        </div>
        <div class="col">
            <article class="card admin-metric-card admin-metric-card--clients h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="admin-metric-icon"><i class="fas fa-users"></i></div>
                    <span class="admin-metric-kicker">JARINGAN</span>
                </div>
                <div class="mt-4">
                    <p class="admin-metric-label mb-1">Total Klien</p>
                    <p class="admin-metric-value mb-2">{{ $stats['total_klien'] ?? 0 }}</p>
                    <p class="admin-metric-caption mb-0">Klien terdaftar di sistem</p>
                </div>
            </article>
        </div>
    </div>

    {{-- BARIS 2: PEMBAGIAN TABEL & HISTORY --}}
    <div class="row g-4">
        {{-- SISI KIRI: ANTREAN PENDAFTARAN --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-deep-forest"><i class="fas fa-list me-2 text-primary"></i>Antrean Pendaftaran Baru</h6>
                    <a href="{{ route('admin.operasional.plotting') }}" class="btn btn-sm btn-light rounded-pill px-3 border">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="small text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.05em;">
                                    <th class="ps-4 py-3">ID Reg</th>
                                    <th>Klien</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendaftaranTerbaru as $row)
                                <tr class="border-bottom">
                                    <td class="ps-4">
                                        <span class="small fw-bold text-primary">{{ $row->no_pendaftaran }}</span>
                                    </td>
                                    <td>
                                        <div class="small fw-bold text-dark">{{ $row->user->name }}</div>
                                        <div class="text-muted" style="font-size: 0.7rem;"><i class="far fa-calendar-alt me-1"></i>{{ $row->created_at->format('d M Y') }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-primary text-primary rounded-pill px-2 fw-medium" style="font-size: 0.65rem;">Level {{ $row->progress_level }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($row->progress_level == 4)
                                            <button type="button" class="btn btn-xs btn-primary rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalPlotting{{ $row->id }}">
                                                <i class="fas fa-user-plus me-1"></i> Plot
                                            </button>
                                        @else
                                            <i class="fas fa-ellipsis-h text-muted opacity-50"></i>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center py-5 text-muted">Belum ada antrean baru.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- SISI KANAN: HISTORY PENOLAKAN --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-danger mb-0"><i class="fas fa-history me-2"></i>History Penolakan</h6>
                    <span class="badge bg-soft-danger text-danger rounded-pill px-3 py-1 fw-bold" style="font-size: 0.65rem;">Log Terakhir</span>
                </div>
                <div class="card-body p-4">
                    <div class="timeline-container">
                        @forelse($logPenolakan as $log)
                        <div class="d-flex position-relative mb-4">
                            @if(!$loop->last)
                                <div class="position-absolute border-start" style="left: 17px; top: 35px; bottom: -20px; border-color: #f1f1f1 !important; border-width: 2px !important;"></div>
                            @endif
                            <div class="flex-shrink-0 z-index-2">
                                <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 35px; height: 35px;">
                                    <i class="fas fa-times fa-xs"></i>
                                </div>
                            </div>
                            <div class="ms-3 w-100">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="small fw-800 mb-0 text-dark">{{ $log->user->name }}</h6>
                                    <span class="text-muted" style="font-size: 0.6rem;">{{ $log->updated_at->diffForHumans() }}</span>
                                </div>
                                <div class="bg-light p-3 rounded-3 border-start border-danger border-4 shadow-sm">
                                    <p class="small text-muted mb-0 italic" style="font-size: 0.72rem; line-height: 1.4;">
                                        <i class="fas fa-quote-left me-1 opacity-25"></i>
                                        {{ $log->keterangan ?? 'Dokumen tidak valid atau pembayaran tidak sesuai nominal.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/4436/4436481.png" height="60" class="mb-3 opacity-25">
                            <p class="small text-muted mb-0">Semua pendaftaran berjalan lancar.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tambahkan Modals di luar loop utama agar tidak merusak layout --}}
@foreach($pendaftaranTerbaru as $row)
    @if($row->progress_level == 4)
    <div class="modal fade" id="modalPlotting{{ $row->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <form action="{{ route('admin.operasional.assign') }}" method="POST">
                    @csrf
                    <input type="hidden" name="pendaftaran_id" value="{{ $row->id }}">
                    <div class="modal-body p-4">
                        <h6 class="fw-bold mb-3">Tugaskan Konsultan</h6>
                        <label class="x-small fw-bold text-muted text-uppercase mb-2">Pilih Konsultan Pelaksana</label>
                        <select name="konsultan_id" class="form-select rounded-3 shadow-sm mb-3" required>
                            <option value="">-- Pilih --</option>
                            @foreach($konsultans as $k)
                                <option value="{{ $k->id }}">{{ $k->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bold py-2">Konfirmasi Plotting</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach

<style>
    .admin-metric-card {
        --metric-accent: #2563eb;
        --metric-tint: #eff6ff;
        min-height: 190px;
        padding: 1.15rem;
        border: 1px solid #e9eef3;
        border-radius: 1rem;
        background: linear-gradient(145deg, #fff 70%, var(--metric-tint));
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
        transition: transform 160ms ease, box-shadow 160ms ease;
    }
    .admin-metric-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.09);
    }
    .admin-metric-card--plotting { --metric-accent: #2563eb; --metric-tint: #eff6ff; }
    .admin-metric-card--sidang { --metric-accent: #d97706; --metric-tint: #fffbeb; }
    .admin-metric-card--certificate { --metric-accent: #059669; --metric-tint: #ecfdf5; }
    .admin-metric-card--rejected { --metric-accent: #dc3545; --metric-tint: #fff1f2; }
    .admin-metric-card--clients { --metric-accent: #475569; --metric-tint: #f1f5f9; }
    .admin-metric-icon {
        display: grid;
        width: 2.65rem;
        height: 2.65rem;
        place-items: center;
        border-radius: 0.85rem;
        background: var(--metric-tint);
        color: var(--metric-accent);
        font-size: 1.05rem;
    }
    .admin-metric-kicker {
        padding: 0.35rem 0.5rem;
        border-radius: 999px;
        background: var(--metric-tint);
        color: var(--metric-accent);
        font-size: 0.55rem;
        font-weight: 800;
        letter-spacing: 0.06em;
    }
    .admin-metric-label {
        color: #475569;
        font-size: 0.78rem;
        font-weight: 700;
    }
    .admin-metric-value {
        color: #172033;
        font-size: clamp(1.8rem, 2.5vw, 2.15rem);
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -0.04em;
    }
    .admin-metric-card--rejected .admin-metric-value { color: var(--metric-accent); }
    .admin-metric-caption {
        color: #8490a0;
        font-size: 0.68rem;
    }
    .fw-800 { font-weight: 800; }
    .bg-danger-soft { background-color: rgba(220, 53, 69, 0.08); }
    .bg-soft-primary { background-color: #eef2ff; }
    .bg-soft-danger { background-color: #fff1f2; }
    .btn-xs { padding: 0.25rem 0.6rem; font-size: 0.7rem; }
    .italic { font-style: italic; }
    .z-index-2 { z-index: 2; }
    @media (prefers-reduced-motion: reduce) {
        .admin-metric-card { transition: none; }
        .admin-metric-card:hover { transform: none; }
    }
</style>
@endsection
