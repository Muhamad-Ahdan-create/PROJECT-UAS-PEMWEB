@extends('layouts.admin')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.rat.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
        <h5 class="fw-bold mb-0">Buat Catatan RAT</h5>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.rat.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                @csrf
                <div class="col-sm-4">
                    <label class="form-label small fw-medium">Tahun RAT</label>
                    <input type="number" name="tahun_rat" class="form-control form-control-sm"
                           value="{{ old('tahun_rat', date('Y')) }}" required>
                </div>
                <div class="col-sm-4">
                    <label class="form-label small fw-medium">Tanggal Pelaksanaan</label>
                    <input type="date" name="tanggal_rat" class="form-control form-control-sm"
                           value="{{ old('tanggal_rat', date('Y-m-d')) }}" required>
                </div>
                <div class="col-sm-4">
                    <label class="form-label small fw-medium">Tempat</label>
                    <input type="text" name="tempat" class="form-control form-control-sm"
                           value="{{ old('tempat') }}" placeholder="Contoh: Aula Sekolah" required>
                </div>
                <div class="col-12">
                    <label class="form-label small fw-medium">Agenda Rapat</label>
                    <textarea name="agenda" class="form-control form-control-sm" rows="3" required
                              placeholder="Tuliskan agenda rapat...">{{ old('agenda') }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label small fw-medium">Notulensi / Jalannya Rapat</label>
                    <textarea name="notulensi" class="form-control form-control-sm" rows="5"
                              placeholder="Tuliskan jalannya rapat...">{{ old('notulensi') }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label small fw-medium">Hasil Keputusan</label>
                    <textarea name="hasil_keputusan" class="form-control form-control-sm" rows="3"
                              placeholder="Tuliskan hasil keputusan rapat...">{{ old('hasil_keputusan') }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label small fw-medium">Lampiran (PDF/JPG, maks 5MB)</label>
                    <input type="file" name="dokumen_lampiran" class="form-control form-control-sm"
                           accept=".pdf,.jpg,.jpeg,.png">
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm px-4">
                        <i class="bi bi-save me-1"></i>Simpan
                    </button>
                    <a href="{{ route('admin.rat.index') }}" class="btn btn-outline-secondary btn-sm">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
