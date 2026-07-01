{{-- resources/views/admin/pinjaman/index.blade.php --}}
@extends('layouts.admin')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Data Pinjaman</h5>
        <div class="bg-danger bg-opacity-10 border border-danger-subtle rounded px-3 py-2 small">
            Total Sisa: <strong class="text-danger">Rp {{ number_format($totalSisa, 0, ',', '.') }}</strong>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Anggota</th>
                            <th class="text-end">Jumlah</th>
                            <th class="text-end">Sisa</th>
                            <th class="text-center">Tenor</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pinjaman as $p)
                            <tr>
                                <td><span class="font-monospace small text-primary">{{ $p->kode_pinjaman }}</span></td>
                                <td>{{ $p->anggota->nama_lengkap }}</td>
                                <td class="text-end small">Rp {{ number_format($p->jumlah_pinjaman, 0, ',', '.') }}</td>
                                <td class="text-end fw-semibold {{ $p->sisa_pinjaman > 0 ? 'text-danger' : 'text-success' }}">
                                    Rp {{ number_format($p->sisa_pinjaman, 0, ',', '.') }}
                                </td>
                                <td class="text-center">{{ $p->tenor_bulan }} bln</td>
                                <td class="text-center">
                                    @php
                                        $badge = match ($p->status) {
                                            'disetujui' => 'bg-success',
                                            'pending' => 'bg-warning text-dark',
                                            'lunas' => 'bg-primary',
                                            'ditolak' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ ucfirst($p->status) }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.pinjaman.show', $p) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada data pinjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($pinjaman->hasPages())
            <div class="card-footer bg-white">{{ $pinjaman->links() }}</div>
        @endif
    </div>
@endsection