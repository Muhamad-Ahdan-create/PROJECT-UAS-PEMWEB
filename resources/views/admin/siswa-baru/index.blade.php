@extends('layouts.admin')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Data Siswa Baru</h5>
        <a href="{{ route('admin.siswa-baru.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Daftarkan Siswa Baru
        </a>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>Kelas / Jurusan</th>
                            <th>Tahun Masuk</th>
                            <th class="text-center">Status Seragam</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswa as $s)
                            <tr>
                                <td class="font-monospace small">{{ $s->nisn }}</td>
                                <td>{{ $s->nama_lengkap }}</td>
                                <td class="small text-muted">{{ $s->kelas }} / {{ $s->jurusan }}</td>
                                <td>{{ $s->tahun_masuk }}</td>
                                <td class="text-center">
                                    @if($s->pembayaran)
                                        @php $b = match ($s->pembayaran->status) { 'tervalidasi' => 'bg-success', 'lunas' => 'bg-info', 'partial' => 'bg-warning text-dark', default => 'bg-secondary'}; @endphp
                                        <span class="badge {{ $b }}">{{ ucfirst($s->pembayaran->status) }}</span>
                                    @else
                                        <span class="badge bg-secondary">Belum Ada Tagihan</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.siswa-baru.show', $s) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.siswa-baru.destroy', $s) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Hapus data siswa ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash3"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada data siswa baru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">{{ $siswa->links() }}</div>
    </div>
@endsection