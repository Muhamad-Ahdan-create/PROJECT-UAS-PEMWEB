{{-- resources/views/admin/pengajuan/index.blade.php --}}
@extends('layouts.admin')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Daftar Pengajuan Pinjaman</h5>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Anggota</th>
                            <th>Jumlah Diajukan</th>
                            <th>Tenor</th>
                            <th>Tujuan</th>
                            <th>Tanggal</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuan as $p)
                            <tr>
                                <td>{{ $p->anggota->nama_lengkap }}</td>
                                <td>Rp {{ number_format($p->jumlah_diajukan, 0, ',', '.') }}</td>
                                <td>{{ $p->tenor_diajukan }} bln</td>
                                <td class="small text-muted" style="max-width:180px;">
                                    {{ Str::limit($p->tujuan_pinjaman, 50) }}
                                </td>
                                <td class="small">{{ $p->tanggal_pengajuan?->format('d/m/Y') ?? '-' }}</td>
                                <td class="text-center">
                                    @php
                                        $badge = match ($p->status) {
                                            'diajukan' => 'bg-warning text-dark',
                                            'diproses' => 'bg-info',
                                            'disetujui' => 'bg-success',
                                            'ditolak' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ ucfirst($p->status) }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.pengajuan.show', $p->id) }}" class="btn btn-sm btn-outline-primary"
                                        title="Lihat Detail & Review">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada pengajuan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($pengajuan->hasPages())
            <div class="card-footer bg-white">{{ $pengajuan->links() }}</div>
        @endif
    </div>
@endsection