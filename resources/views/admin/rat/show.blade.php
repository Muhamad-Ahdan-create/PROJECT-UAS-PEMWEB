@extends('layouts.admin')
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center gap-2 mb-4">
                <a href="{{ route('admin.rat.index') }}" class="btn btn-sm btn-outline-secondary"><i
                        class="bi bi-arrow-left"></i></a>
                <h5 class="fw-bold mb-0">Detail Catatan RAT {{ $rat->tahun_rat }}</h5>
                <div class="ms-auto d-flex gap-2">
                    <a href="{{ route('admin.rat.edit', $rat) }}" class="btn btn-sm btn-outline-warning">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <a href="{{ route('admin.rat.pdf', $rat) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                        <i class="bi bi-file-pdf me-1"></i>Export PDF
                    </a>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted small" width="160">Tahun RAT</td>
                            <td class="fw-semibold">{{ $rat->tahun_rat }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Tanggal</td>
                            <td>{{ $rat->tanggal_rat->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Tempat</td>
                            <td>{{ $rat->tempat }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Dibuat Oleh</td>
                            <td>{{ $rat->pembuat->name }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header"><i class="bi bi-list-ul me-2 text-primary"></i>Agenda</div>
                <div class="card-body">
                    <p class="mb-0 small" style="white-space:pre-line;">{{ $rat->agenda }}</p>
                </div>
            </div>

            @if($rat->notulensi)
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header"><i class="bi bi-journal-text me-2 text-primary"></i>Notulensi</div>
                    <div class="card-body">
                        <p class="mb-0 small" style="white-space:pre-line;">{{ $rat->notulensi }}</p>
                    </div>
                </div>
            @endif

            @if($rat->hasil_keputusan)
                <div class="card border-0 shadow-sm">
                    <div class="card-header"><i class="bi bi-check2-square me-2 text-primary"></i>Hasil Keputusan</div>
                    <div class="card-body">
                        <p class="mb-0 small" style="white-space:pre-line;">{{ $rat->hasil_keputusan }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
