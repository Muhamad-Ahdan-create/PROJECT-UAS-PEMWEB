{{-- resources/views/admin/seragam/index.blade.php --}}
@extends('layouts.admin')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Pembayaran Seragam Siswa Baru</h5>
        <a href="{{ route('admin.seragam.export') }}" class="btn btn-success btn-sm">
            <i class="bi bi-file-excel me-1"></i>Export Excel
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="text-muted small">Belum Bayar</div>
                <div class="fw-bold fs-4 text-secondary">{{ $stats['belum'] }}</div>
            </div>
        </div>
        <div class="col-6 col-lg">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="text-muted small">Bayar Sebagian</div>
                <div class="fw-bold fs-4 text-warning">{{ $stats['partial'] }}</div>
            </div>
        </div>
        <div class="col-6 col-lg">
            <div class="card border-0 shadow-sm text-center py-3 border border-info">
                <div class="text-muted small">Menunggu Validasi</div>
                <div class="fw-bold fs-4 text-info">{{ $stats['menunggu_validasi'] }}</div>
            </div>
        </div>
        <div class="col-6 col-lg">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="text-muted small">Lunas</div>
                <div class="fw-bold fs-4 text-primary">{{ $stats['lunas'] }}</div>
            </div>
        </div>
        <div class="col-6 col-lg">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="text-muted small">Tervalidasi</div>
                <div class="fw-bold fs-4 text-success">{{ $stats['tervalidasi'] }}</div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Siswa</th>
                            <th class="text-end">Total Tagihan</th>
                            <th class="text-end">Sudah Bayar</th>
                            <th class="text-end">Sisa</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembayaran as $p)
                            <tr class="{{ $p->status === 'menunggu_validasi' ? 'table-warning bg-opacity-25' : '' }}">
                                <td><span class="font-monospace small">{{ $p->kode_tagihan }}</span></td>
                                <td>{{ $p->siswaBaru->nama_lengkap }}</td>
                                <td class="text-end small">Rp {{ number_format($p->total_tagihan, 0, ',', '.') }}</td>
                                <td class="text-end small text-success">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}
                                </td>
                                <td class="text-end fw-semibold {{ $p->sisa_tagihan > 0 ? 'text-danger' : 'text-success' }}">
                                    Rp {{ number_format($p->sisa_tagihan, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    @php
                                        $badge = match ($p->status) {
                                            'tervalidasi' => 'bg-success',
                                            'menunggu_validasi' => 'bg-info',
                                            'lunas' => 'bg-primary',
                                            'partial' => 'bg-warning text-dark',
                                            default => 'bg-secondary',
                                        };
                                        $label = match ($p->status) {
                                            'menunggu_validasi' => 'Menunggu Validasi',
                                            default => ucfirst($p->status),
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ $label }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('admin.seragam.show', $p) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($p->status === 'menunggu_validasi')
                                            <form action="{{ route('admin.seragam.validasi', $p) }}" method="POST"
                                                onsubmit="return confirm('Validasi pembayaran ini?')">
                                                @csrf
                                                <button class="btn btn-sm btn-success" title="Validasi">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if($p->status === 'tervalidasi')
                                            <a href="{{ route('admin.seragam.kwitansi', $p->id) }}"
                                                class="btn btn-sm btn-outline-secondary" target="_blank" title="Cetak Kwitansi">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($pembayaran->hasPages())
            <div class="card-footer bg-white">{{ $pembayaran->links() }}</div>
        @endif
    </div>
@endsection
