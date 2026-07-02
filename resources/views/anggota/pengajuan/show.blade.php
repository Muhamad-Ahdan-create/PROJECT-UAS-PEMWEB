@extends('layouts.anggota')
@section('content')

<div class="row justify-content-center">
<div class="col-lg-7">

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('anggota.pengajuan.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h5 class="fw-bold mb-0">Detail Pengajuan</h5>
    </div>

    {{-- Status Banner --}}
    @php
        $statusConfig = [
            'draft'     => ['class' => 'bg-secondary', 'icon' => 'bi-pencil'],
            'diajukan'  => ['class' => 'bg-warning text-dark', 'icon' => 'bi-hourglass-split'],
            'diproses'  => ['class' => 'bg-info', 'icon' => 'bi-gear'],
            'disetujui' => ['class' => 'bg-success', 'icon' => 'bi-check-circle-fill'],
            'ditolak'   => ['class' => 'bg-danger', 'icon' => 'bi-x-circle-fill'],
        ];
        $sc = $statusConfig[$pengajuan->status] ?? ['class' => 'bg-secondary', 'icon' => 'bi-question'];
    @endphp

    <div class="alert {{ str_replace('bg-', 'alert-', $pengajuan->status === 'diajukan' ? 'bg-warning' : ($pengajuan->status === 'disetujui' ? 'bg-success' : ($pengajuan->status === 'ditolak' ? 'bg-danger' : 'bg-secondary'))) }} d-flex align-items-center gap-2 mb-4">
        <i class="bi {{ $sc['icon'] }} fs-5"></i>
        <div>
            <strong>Status: {{ ucfirst($pengajuan->status) }}</strong>
            @if($pengajuan->catatan_admin)
                <div class="small mt-1">Catatan Admin: {{ $pengajuan->catatan_admin }}</div>
            @endif
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header"><i class="bi bi-file-earmark-text me-2 text-primary"></i>Rincian Pengajuan</div>
        <div class="card-body">
            <table class="table table-sm table-borderless mb-0">
                <tr>
                    <td class="text-muted small" width="160">Jumlah Diajukan</td>
                    <td class="fw-semibold">Rp {{ number_format($pengajuan->jumlah_diajukan, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-muted small">Tenor</td>
                    <td>{{ $pengajuan->tenor_diajukan }} Bulan</td>
                </tr>
                <tr>
                    <td class="text-muted small">Tujuan Pinjaman</td>
                    <td>{{ $pengajuan->tujuan_pinjaman }}</td>
                </tr>
                <tr>
                    <td class="text-muted small">Jaminan</td>
                    <td>{{ $pengajuan->jaminan ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="text-muted small">Tanggal Pengajuan</td>
                    <td>{{ $pengajuan->tanggal_pengajuan?->format('d F Y H:i') ?? '-' }}</td>
                </tr>
                @if($pengajuan->dokumen_path)
                <tr>
                    <td class="text-muted small">Dokumen</td>
                    <td>
                        <a href="{{ asset('storage/'.$pengajuan->dokumen_path) }}"
                           target="_blank" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-file-earmark me-1"></i>Lihat Dokumen
                        </a>
                    </td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    {{-- Simulasi jika sudah disetujui --}}
    @if($pengajuan->status === 'disetujui')
    @php
        $totalBunga     = $pengajuan->jumlah_diajukan * 0.015 * $pengajuan->tenor_diajukan;
        $totalKewajiban = $pengajuan->jumlah_diajukan + $totalBunga;
        $angsuran       = $totalKewajiban / $pengajuan->tenor_diajukan;
    @endphp
    <div class="card border-success border-0 shadow-sm mt-3">
        <div class="card-header bg-success text-white">
            <i class="bi bi-check-circle-fill me-2"></i>Pengajuan Disetujui — Info Pinjaman
        </div>
        <div class="card-body">
            <div class="row g-3 text-center">
                <div class="col-4">
                    <div class="p-2 bg-light rounded">
                        <div class="text-muted small">Angsuran/Bulan</div>
                        <div class="fw-bold text-primary">Rp {{ number_format($angsuran, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-2 bg-light rounded">
                        <div class="text-muted small">Total Bunga</div>
                        <div class="fw-bold text-warning">Rp {{ number_format($totalBunga, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-2 bg-light rounded">
                        <div class="text-muted small">Total Kewajiban</div>
                        <div class="fw-bold text-danger">Rp {{ number_format($totalKewajiban, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="mt-3 text-center">
                <a href="{{ route('anggota.sisa-pinjaman') }}" class="btn btn-success btn-sm">
                    <i class="bi bi-wallet2 me-1"></i>Lihat Sisa Pinjaman
                </a>
            </div>
        </div>
    </div>
    @endif

</div>
</div>
@endsection
