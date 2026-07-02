@extends('layouts.admin')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-9">
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.siswa-baru.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
        <h5 class="fw-bold mb-0">Daftarkan Siswa Baru</h5>
    </div>

    <form action="{{ route('admin.siswa-baru.store') }}" method="POST">
    @csrf

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header"><i class="bi bi-person me-2 text-primary"></i>Data Siswa</div>
        <div class="card-body row g-3">
            <div class="col-sm-6">
                <label class="form-label small fw-medium">NISN</label>
                <input type="text" name="nisn" class="form-control form-control-sm @error('nisn') is-invalid @enderror"
                       value="{{ old('nisn') }}" required>
                @error('nisn') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-sm-6">
                <label class="form-label small fw-medium">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control form-control-sm @error('nama_lengkap') is-invalid @enderror"
                       value="{{ old('nama_lengkap') }}" required>
                @error('nama_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-sm-4">
                <label class="form-label small fw-medium">Email (untuk login)</label>
                <input type="email" name="email" class="form-control form-control-sm @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-sm-4">
                <label class="form-label small fw-medium">Kelas</label>
                <input type="text" name="kelas" class="form-control form-control-sm"
                       value="{{ old('kelas') }}" placeholder="Contoh: X">
            </div>
            <div class="col-sm-4">
                <label class="form-label small fw-medium">Jurusan</label>
                <input type="text" name="jurusan" class="form-control form-control-sm"
                       value="{{ old('jurusan') }}" placeholder="Contoh: RPL">
            </div>
            <div class="col-sm-4">
                <label class="form-label small fw-medium">Tahun Masuk</label>
                <input type="number" name="tahun_masuk" class="form-control form-control-sm"
                       value="{{ old('tahun_masuk', date('Y')) }}" required>
            </div>
            <div class="col-sm-4">
                <label class="form-label small fw-medium">Nama Orang Tua</label>
                <input type="text" name="nama_orang_tua" class="form-control form-control-sm"
                       value="{{ old('nama_orang_tua') }}">
            </div>
            <div class="col-sm-4">
                <label class="form-label small fw-medium">No. Telp Orang Tua</label>
                <input type="text" name="no_telp_ortu" class="form-control form-control-sm"
                       value="{{ old('no_telp_ortu') }}">
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header"><i class="bi bi-bag me-2 text-primary"></i>Pilih Item Seragam</div>
        <div class="card-body">
            @error('items') <div class="alert alert-danger small py-2">{{ $message }}</div> @enderror
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Item Seragam</th>
                            <th class="text-center">Ukuran</th>
                            <th class="text-end">Harga</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $i => $item)
                        <tr>
                            <td>
                                {{ $item->nama_item }}
                                <input type="hidden" name="items[{{ $i }}][item_seragam_id]" value="{{ $item->id }}">
                            </td>
                            <td class="text-center">{{ $item->ukuran ?? '-' }}</td>
                            <td class="text-end small">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="text-center small text-muted">{{ $item->stok }}</td>
                            <td class="text-center" style="width:100px;">
                                <input type="number" name="items[{{ $i }}][jumlah]"
                                       class="form-control form-control-sm text-center"
                                       min="0" max="{{ $item->stok }}" value="0">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="small text-muted mt-1">
                <i class="bi bi-info-circle me-1"></i>Isi jumlah 0 untuk item yang tidak dibeli. Password login siswa = NISN.
            </p>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary btn-sm px-4">
            <i class="bi bi-save me-1"></i>Daftarkan & Buat Tagihan
        </button>
        <a href="{{ route('admin.siswa-baru.index') }}" class="btn btn-outline-secondary btn-sm">Batal</a>
    </div>
    </form>
</div>
</div>
@endsection
