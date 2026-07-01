@extends('layouts.anggota')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Pengajuan Pinjaman Saya</h5>
    <a href="{{ route('anggota.pengajuan.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Ajukan Baru
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Jumlah</th>
                        <th class="text-center">Tenor</th>
                        <th>Tujuan</th>
                        <th>Tanggal</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuan as $p)
                    <tr>
                        <td class="fw-semibold">Rp {{ number_format($p->jumlah_diajukan, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $p->tenor_diajukan }} bln</td>
                        <td class="small text-muted" style="max-width:200px;">
                            {{ Str::limit($p->tujuan_pinjaman, 50) }}
                        </td>
                        <td class="small">{{ $p->tanggal_pengajuan?->format('d/m/Y') ?? '-' }}</td>
                        <td class="text-center">
                            @php
                                $badge = match($p->status) {
                                    'diajukan'  => 'bg-warning text-dark',
                                    'diproses'  => 'bg-info',
                                    'disetujui' => 'bg-success',
                                    'ditolak'   => 'bg-danger',
                                    default     => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $badge }}">{{ ucfirst($p->status) }}</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('anggota.pengajuan.show', $p) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-file-earmark-x fs-1 d-block mb-2 opacity-25"></i>
                            Belum ada pengajuan pinjaman.
                            <div class="mt-2">
                                <a href="{{ route('anggota.pengajuan.create') }}" class="btn btn-primary btn-sm">
                                    Ajukan Sekarang
                                </a>
                            </div>
                        </td>
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