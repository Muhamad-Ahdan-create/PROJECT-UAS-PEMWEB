{{-- resources/views/admin/pinjaman/show.blade.php --}}
@extends('layouts.admin')
@section('content')

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.pinjaman.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h5 class="fw-bold mb-0">Detail Pinjaman</h5>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Pinjaman</div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted small">Kode Pinjaman</td>
                            <td class="fw-semibold font-monospace">{{ $pinjaman->kode_pinjaman }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Anggota</td>
                            <td>{{ $pinjaman->anggota->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Jumlah Pinjaman</td>
                            <td>Rp {{ number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Bunga</td>
                            <td>{{ $pinjaman->bunga_persen }}% / bulan (flat)</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Tenor</td>
                            <td>{{ $pinjaman->tenor_bulan }} bulan</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Angsuran/Bulan</td>
                            <td class="fw-semibold">Rp {{ number_format($pinjaman->angsuran_per_bulan, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header"><i class="bi bi-wallet2 me-2 text-primary"></i>Status Pembayaran</div>
                <div class="card-body text-center">
                    <p class="text-muted small mb-1">Sisa Pinjaman</p>
                    <h2 class="fw-bold {{ $pinjaman->sisa_pinjaman > 0 ? 'text-danger' : 'text-success' }}">
                        Rp {{ number_format($pinjaman->sisa_pinjaman, 0, ',', '.') }}
                    </h2>
                    @php
                        $lunas = $pinjaman->angsuran->where('status', 'lunas')->count();
                        $total = $pinjaman->angsuran->count();
                        $pct = $total > 0 ? round($lunas / $total * 100) : 0;
                    @endphp
                    <p class="small text-muted mt-2">
                        <span class="fw-semibold text-primary">{{ $lunas }}</span> / {{ $total }} angsuran lunas
                        ({{ $pct }}%)
                    </p>
                    <div class="progress mt-1" style="height:8px;">
                        <div class="progress-bar bg-primary" style="width:{{ $pct }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header"><i class="bi bi-list-ol me-2 text-primary"></i>Jadwal Angsuran</div>
        <div class="card-body p-0">
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
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pinjaman->angsuran->sortBy('ke_bulan') as $a)
                            <tr
                                class="{{ $a->status === 'terlambat' ? 'table-danger' : ($a->status === 'lunas' ? 'table-success bg-opacity-25' : '') }}">
                                <td class="text-center fw-semibold">{{ $a->ke_bulan }}</td>
                                <td class="text-end small">Rp {{ number_format($a->pokok, 0, ',', '.') }}</td>
                                <td class="text-end small">Rp {{ number_format($a->bunga, 0, ',', '.') }}</td>
                                <td class="text-end fw-semibold small">Rp {{ number_format($a->jumlah_bayar, 0, ',', '.') }}
                                </td>
                                <td class="text-center small">{{ $a->tanggal_bayar->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    @php
                                        $b = match ($a->status) {
                                            'lunas' => 'bg-success',
                                            'terlambat' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $b }}">{{ ucfirst($a->status) }}</span>
                                </td>
                                <td class="text-center">
                                    @if($a->status !== 'lunas')
                                        <form action="{{ route('admin.pinjaman.bayar', $a) }}" method="POST"
                                            onsubmit="return confirm('Tandai angsuran ini lunas?')">
                                            @csrf
                                            <button class="btn btn-sm btn-success">
                                                <i class="bi bi-check-lg"></i> Lunas
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-success small"><i class="bi bi-check-circle-fill"></i> Lunas</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection