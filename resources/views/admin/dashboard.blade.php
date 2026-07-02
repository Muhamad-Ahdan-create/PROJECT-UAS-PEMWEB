@extends('layouts.admin')

@section('content')

    <h5 class="fw-bold mb-4">Ringkasan Koperasi</h5>

    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card" style="background: linear-gradient(135deg,#1a56db,#0ea5e9);">
                <div class="label">Total Anggota</div>
                <div class="value">{{ $stats['total_anggota'] }}</div>
                <i class="bi bi-people icon"></i>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card" style="background: linear-gradient(135deg,#d97706,#f59e0b);">
                <div class="label">Pinjaman Aktif</div>
                <div class="value">{{ $stats['total_pinjaman_aktif'] }}</div>
                <i class="bi bi-cash-coin icon"></i>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card" style="background: linear-gradient(135deg,#dc2626,#f87171);">
                <div class="label">Total Sisa Pinjaman</div>
                <div class="value" style="font-size:1.1rem;">
                    Rp {{ number_format($stats['total_sisa_pinjaman'], 0, ',', '.') }}
                </div>
                <i class="bi bi-exclamation-circle icon"></i>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card" style="background: linear-gradient(135deg,#16a34a,#4ade80);">
                <div class="label">Pengajuan Pending</div>
                <div class="value">{{ $stats['pengajuan_pending'] }}</div>
                <i class="bi bi-file-earmark-text icon"></i>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-sm-6">
            <div class="card border-warning">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                        style="width:48px;height:48px;background:#fef3c7;">
                        <i class="bi bi-bag-x fs-4 text-warning"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Seragam Belum Lunas</div>
                        <div class="fw-bold fs-4">{{ $stats['seragam_belum_lunas'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card border-info">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                        style="width:48px;height:48px;background:#e0f2fe;">
                        <i class="bi bi-hourglass-split fs-4 text-info"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Menunggu Validasi Seragam</div>
                        <div class="fw-bold fs-4">{{ $stats['seragam_menunggu_validasi'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-file-earmark-text me-2 text-primary"></i>Pengajuan Terbaru</span>
                    <a href="{{ route('admin.pengajuan.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Anggota</th>
                                <th>Jumlah</th>
                                <th>Tenor</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengajuanTerbaru as $p)
                                <tr>
                                    <td>{{ $p->anggota->nama_lengkap }}</td>
                                    <td>Rp {{ number_format($p->jumlah_diajukan, 0, ',', '.') }}</td>
                                    <td>{{ $p->tenor_diajukan }} bln</td>
                                    <td><span class="badge bg-warning text-dark">{{ ucfirst($p->status) }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3 small">
                                        Tidak ada pengajuan pending.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-cash-coin me-2 text-primary"></i>Pinjaman Aktif Terbaru</span>
                    <a href="{{ route('admin.pinjaman.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Anggota</th>
                                <th>Pinjaman</th>
                                <th>Sisa</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pinjamanTerbaru as $p)
                                <tr>
                                    <td><span class="font-monospace small text-primary">{{ $p->kode_pinjaman }}</span></td>
                                    <td>{{ $p->anggota->nama_lengkap }}</td>
                                    <td>Rp {{ number_format($p->jumlah_pinjaman, 0, ',', '.') }}</td>
                                    <td class="fw-semibold text-danger">Rp {{ number_format($p->sisa_pinjaman, 0, ',', '.') }}
                                    </td>
                                    <td><span class="badge bg-success">{{ ucfirst($p->status) }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3 small">
                                        Belum ada data pinjaman.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
