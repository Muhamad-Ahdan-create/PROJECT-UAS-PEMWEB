@extends('layouts.anggota')
@section('content')

<h5 class="fw-bold mb-4">Sisa Pinjaman Saya</h5>

{{-- Banner total --}}
<div class="card text-white border-0 mb-4"
     style="background: linear-gradient(135deg,#dc2626,#f87171); border-radius:14px;">
    <div class="card-body p-4">
        <p class="mb-1 opacity-75 small">Total Sisa Pinjaman per {{ now()->format('d F Y') }}</p>
        <h3 class="fw-bold mb-0">Rp {{ number_format($totalSisa, 0, ',', '.') }}</h3>
    </div>
</div>

@forelse($pinjaman as $p)
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="font-monospace small text-primary fw-semibold">{{ $p->kode_pinjaman }}</span>
        <span class="badge bg-success">Aktif</span>
    </div>

    <div class="card-body">
        {{-- Ringkasan 3 kolom --}}
        <div class="row g-3 mb-3">
            <div class="col-4 text-center">
                <div class="text-muted small">Pinjaman Awal</div>
                <div class="fw-semibold">Rp {{ number_format($p->jumlah_pinjaman, 0, ',', '.') }}</div>
            </div>
            <div class="col-4 text-center border-start border-end">
                <div class="text-muted small">Angsuran/Bulan</div>
                <div class="fw-semibold">Rp {{ number_format($p->angsuran_per_bulan, 0, ',', '.') }}</div>
            </div>
            <div class="col-4 text-center">
                <div class="text-muted small">Sisa Tenor</div>
                <div class="fw-semibold">{{ $p->angsuranBelumBayar()->count() }} bulan</div>
            </div>
        </div>

        {{-- Progress bar --}}
        @php
            $totalAng = $p->angsuran->count();
            $lunasAng = $p->angsuran->where('status', 'lunas')->count();
            $pct = $totalAng > 0 ? round($lunasAng / $totalAng * 100) : 0;
        @endphp
        <div class="mb-3">
            <div class="d-flex justify-content-between small text-muted mb-1">
                <span>Progress Pembayaran</span>
                <span>{{ $lunasAng }}/{{ $totalAng }} ({{ $pct }}%)</span>
            </div>
            <div class="progress" style="height:8px;">
                <div class="progress-bar bg-primary" style="width:{{ $pct }}%"></div>
            </div>
        </div>

        {{-- Sisa pinjaman besar --}}
        <div class="d-flex justify-content-between align-items-center p-3 rounded-3 mb-3"
             style="background:#fff5f5;">
            <span class="small text-muted">Sisa Pinjaman</span>
            <span class="fw-bold text-danger fs-5">
                Rp {{ number_format($p->sisa_pinjaman, 0, ',', '.') }}
            </span>
        </div>

        {{-- Angsuran berikutnya --}}
        @php $next = $p->angsuranBelumBayar()->orderBy('ke_bulan')->first(); @endphp
        @if($next)
        <div class="alert alert-warning d-flex align-items-center gap-2 py-2 mb-3">
            <i class="bi bi-calendar-event-fill flex-shrink-0"></i>
            <div class="small">
                <strong>Angsuran ke-{{ $next->ke_bulan }}</strong> jatuh tempo
                {{ $next->tanggal_bayar->format('d F Y') }} —
                <strong>Rp {{ number_format($next->jumlah_bayar, 0, ',', '.') }}</strong>
            </div>
        </div>
        @endif

        {{-- Accordion jadwal --}}
        <div class="accordion" id="acc{{ $p->id }}">
            <div class="accordion-item border-0">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed py-2 small fw-semibold bg-light rounded"
                            type="button" data-bs-toggle="collapse"
                            data-bs-target="#jadwal{{ $p->id }}">
                        <i class="bi bi-list-ul me-2"></i>Lihat Jadwal Angsuran Lengkap
                    </button>
                </h2>
                <div id="jadwal{{ $p->id }}" class="accordion-collapse collapse">
                    <div class="accordion-body p-0 mt-2">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center">Ke-</th>
                                        <th class="text-end">Pokok</th>
                                        <th class="text-end">Bunga</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-center">Jatuh Tempo</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($p->angsuran->sortBy('ke_bulan') as $a)
                                    <tr class="{{ $a->status === 'terlambat' ? 'table-danger' : ($a->status === 'lunas' ? 'table-success bg-opacity-25' : '') }}">
                                        <td class="text-center fw-semibold">{{ $a->ke_bulan }}</td>
                                        <td class="text-end small">Rp {{ number_format($a->pokok, 0, ',', '.') }}</td>
                                        <td class="text-end small">Rp {{ number_format($a->bunga, 0, ',', '.') }}</td>
                                        <td class="text-end fw-semibold small">Rp {{ number_format($a->jumlah_bayar, 0, ',', '.') }}</td>
                                        <td class="text-center small">{{ $a->tanggal_bayar->format('d/m/Y') }}</td>
                                        <td class="text-center">
                                            @php
                                                $bc = match($a->status) {
                                                    'lunas'    => 'bg-success',
                                                    'terlambat'=> 'bg-danger',
                                                    default    => 'bg-secondary',
                                                };
                                            @endphp
                                            <span class="badge {{ $bc }}">{{ ucfirst($a->status) }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@empty
<div class="card border-0 shadow-sm text-center py-5">
    <div class="fs-1 mb-2">🎉</div>
    <h5 class="fw-semibold">Tidak Ada Pinjaman Aktif</h5>
    <p class="text-muted small">Anda bebas dari hutang pinjaman koperasi.</p>
    <div class="mt-3">
        <a href="{{ route('anggota.pengajuan.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-file-earmark-plus me-1"></i>Ajukan Pinjaman Baru
        </a>
    </div>
</div>
@endforelse

@endsection
