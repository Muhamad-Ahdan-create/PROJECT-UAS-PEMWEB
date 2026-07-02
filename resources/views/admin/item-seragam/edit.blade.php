@extends('layouts.admin')
@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-6">

            <div class="d-flex align-items-center gap-2 mb-4">
                <a href="{{ route('admin.item-seragam.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h5 class="fw-bold mb-0">Edit Item Seragam</h5>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.item-seragam.update', $itemSeragam) }}" method="POST" class="row g-3">
                        @csrf @method('PUT')

                        <div class="col-12">
                            <label class="form-label small fw-medium">Nama Item</label>
                            <input type="text" name="nama_item"
                                class="form-control form-control-sm @error('nama_item') is-invalid @enderror"
                                value="{{ old('nama_item', $itemSeragam->nama_item) }}" required>
                            @error('nama_item')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label small fw-medium">Harga (Rp)</label>
                            <input type="number" name="harga"
                                class="form-control form-control-sm @error('harga') is-invalid @enderror"
                                value="{{ old('harga', $itemSeragam->harga) }}" min="0" required>
                            @error('harga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label small fw-medium">Ukuran</label>
                            <input type="text" name="ukuran" class="form-control form-control-sm"
                                value="{{ old('ukuran', $itemSeragam->ukuran) }}" placeholder="S / M / L / 38">
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label small fw-medium">Stok</label>
                            <input type="number" name="stok"
                                class="form-control form-control-sm @error('stok') is-invalid @enderror"
                                value="{{ old('stok', $itemSeragam->stok) }}" min="0" required>
                            @error('stok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Info stok terpakai --}}
                        <div class="col-12">
                            <div class="p-2 rounded bg-light border small text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Stok saat ini: <strong>{{ $itemSeragam->stok }}</strong> unit.
                                Ubah hanya jika ada penambahan stok baru.
                            </div>
                        </div>

                        <div class="col-12 d-flex gap-2 pt-1">
                            <button type="submit" class="btn btn-warning btn-sm px-4">
                                <i class="bi bi-save me-1"></i>Update
                            </button>
                            <a href="{{ route('admin.item-seragam.index') }}"
                                class="btn btn-outline-secondary btn-sm">Batal</a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
