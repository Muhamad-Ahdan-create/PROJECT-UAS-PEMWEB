{{-- resources/views/anggota/dashboard.blade.php --}}
@extends('layouts.anggota')
@section('content')

    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card border-0 text-white"
                style="background: linear-gradient(135deg, var(--kop-sidebar), var(--kop-primary)); border-radius:14px;">
                <div class="card-body p-4">
                    <p class="mb-1 opacity-75 small">Selamat datang,</p>
                    <h4 class="fw-bold mb-0">{{ $anggota->nama_lengkap }}</h4>
                    <p class="opacity-75 small mb-0 mt-1">
                        <i class="bi bi-person-badge me-1"></i>{{ $anggota->nomor_anggota }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:52px;height:52px;background:#fee2e2;">
                        <i class="bi bi-exclamation-circle fs-4 text-danger"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Sisa Pinjaman</div>
                        <div class="fw-bold fs-5 text-danger">
                            Rp {{ number_format($totalSisaPinjaman, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:52px;height:52px;background:#dbeafe;">
                        <i class="bi bi-cash-coin fs-4 text-primary"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Pinjaman Aktif</div>
                        <div class="fw-bold fs-5">{{ $jumlahPinjaman }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Angsuran jatuh tempo --}}
    @if($angsuranMendatang->isNotEmpty())
        <div class="alert alert-warning d-flex align-items-start gap-3 mb-4">
            <i class="bi bi-bell-fill fs-5 mt-1 flex-shrink-0"></i>
            <div>
                <strong>Pengingat Angsuran</strong>
                <ul class="mb-0 mt-1 small">
                    @foreach($angsuranMendatang as $a)
                        <li>
                            Angsuran ke-{{ $a->ke_bulan }} —
                            <strong>Rp {{ number_format($a->jumlah_bayar, 0, ',', '.') }}</strong>
                            jatuh tempo <strong>{{ $a->tanggal_bayar->format('d F Y') }}</strong>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Aksi Cepat --}}
    <div class="row g-3">
        <div class="col-sm-6">
            <a href="{{ route('anggota.sisa-pinjaman') }}"
                class="card text-decoration-none border-0 shadow-sm h-100 hover-shadow">
                <div class="card-body text-center py-4">
                    <i class="bi bi-wallet2 fs-1 text-primary mb-2 d-block"></i>
                    <div class="fw-semibold">Lihat Sisa Pinjaman</div>
                    <div class="text-muted small">Cek detail & jadwal angsuran</div>
                </div>
            </a>
        </div>
        <div class="col-sm-6">
            <a href="{{ route('anggota.pengajuan.create') }}" class="card text-decoration-none border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <i class="bi bi-file-earmark-plus fs-1 text-success mb-2 d-block"></i>
                    <div class="fw-semibold">Ajukan Pinjaman</div>
                    <div class="text-muted small">Buat pengajuan baru</div>
                </div>
            </a>
        </div>
    </div>
@endsection