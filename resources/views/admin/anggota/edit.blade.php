{{-- resources/views/admin/anggota/edit.blade.php --}}
@extends('layouts.admin')

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="d-flex align-items-center gap-2 mb-4">
                <a href="{{ route('admin.anggota.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h5 class="fw-bold mb-0">Edit Data Anggota</h5>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    {{-- Pesan error validasi --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <p class="fw-semibold small mb-1">Gagal menyimpan perubahan!</p>
                            <ul class="mb-0 small ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.anggota.update', $anggota->id) }}" method="POST" class="row g-3">
                        @csrf
                        @method('PUT')

                        <div class="col-sm-6">
                            <label class="form-label small fw-medium">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control form-control-sm"
                                value="{{ old('nama_lengkap', $anggota->nama_lengkap) }}" required>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label small fw-medium">No. Telepon</label>
                            <input type="text" name="no_telp" class="form-control form-control-sm"
                                value="{{ old('no_telp', $anggota->no_telp) }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-medium">Simpanan Wajib/Bulan (Rp)</label>
                            <input type="number" name="simpanan_wajib" class="form-control form-control-sm"
                                value="{{ old('simpanan_wajib', $anggota->simpanan_wajib) }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-medium">Alamat</label>
                            <textarea name="alamat" rows="3"
                                class="form-control form-control-sm">{{ old('alamat', $anggota->alamat) }}</textarea>
                        </div>

                        <div class="col-12 d-flex gap-2 pt-1">
                            <button type="submit" class="btn btn-primary btn-sm px-4">
                                <i class="bi bi-save me-1"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.anggota.index') }}" class="btn btn-outline-secondary btn-sm">
                                Batal
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection