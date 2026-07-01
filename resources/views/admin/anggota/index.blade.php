{{-- resources/views/admin/anggota/index.blade.php --}}
@extends('layouts.admin')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Daftar Anggota</h5>
        <a href="{{ route('admin.anggota.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Tambah Anggota
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>No. Anggota</th>
                            <th>Nama</th>
                            <th>No. Telp</th>
                            <th class="text-end">Simpanan Pokok</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anggota as $a)
                            <tr>
                                <td><span class="font-monospace small text-primary">{{ $a->nomor_anggota }}</span></td>
                                <td>{{ $a->nama_lengkap }}</td>
                                <td class="text-muted small">{{ $a->no_telp ?? '-' }}</td>
                                <td class="text-end">Rp {{ number_format($a->simpanan_pokok, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('admin.anggota.show', $a) }}" class="btn btn-sm btn-outline-primary"
                                            title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.anggota.edit', $a) }}" class="btn btn-sm btn-outline-warning"
                                            title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.anggota.destroy', $a) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Hapus anggota ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada anggota.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($anggota->hasPages())
            <div class="card-footer bg-white">{{ $anggota->links() }}</div>
        @endif
    </div>
@endsection