@extends('layouts.admin')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Catatan RAT</h5>
    <a href="{{ route('admin.rat.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Buat Catatan RAT
    </a>
</div>

<div class="row g-3">
    @forelse($catatan as $rat)
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="fw-semibold mb-1">RAT Tahun {{ $rat->tahun_rat }}</h6>
                        <p class="text-muted small mb-1">
                            <i class="bi bi-calendar3 me-1"></i>{{ $rat->tanggal_rat->format('d F Y') }}
                            &nbsp;|&nbsp;
                            <i class="bi bi-geo-alt me-1"></i>{{ $rat->tempat }}
                        </p>
                        <p class="small mb-0 text-secondary">{{ Str::limit($rat->agenda, 100) }}</p>
                    </div>
                    <div class="d-flex gap-2 flex-shrink-0 ms-3">
                        <a href="{{ route('admin.rat.show', $rat) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('admin.rat.edit', $rat) }}" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="{{ route('admin.rat.pdf', $rat) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                            <i class="bi bi-file-pdf"></i>
                        </a>
                        <form action="{{ route('admin.rat.destroy', $rat) }}" method="POST"
                              onsubmit="return confirm('Hapus catatan RAT ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash3"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="text-muted">
                <i class="bi bi-journal-x fs-1 d-block mb-2"></i>
                Belum ada catatan RAT.
            </div>
        </div>
    </div>
    @endforelse
</div>
<div class="mt-3">{{ $catatan->links() }}</div>
@endsection
