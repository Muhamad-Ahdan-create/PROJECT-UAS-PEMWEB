{{-- resources/views/siswa/seragam/tagihan.blade.php --}}
@extends('layouts.siswa')
@section('content')

    <h5 class="fw-bold mb-4">
        <i class="bi bi-bag-check me-2 text-primary"></i>Tagihan Seragam Saya
    </h5>

    @if(!$pembayaran)
        <div class="alert alert-warning d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill"></i>
            Belum ada tagihan seragam. Silakan hubungi admin koperasi.
        </div>
    @else

        {{-- Status Badge --}}
        @php
            $statusMap = [
                'belum' => ['label' => 'Belum Bayar', 'class' => 'bg-secondary'],
                'partial' => ['label' => 'Bayar Sebagian', 'class' => 'bg-warning text-dark'],
                'menunggu_validasi' => ['label' => 'Menunggu Validasi Admin', 'class' => 'bg-info'],
                'lunas' => ['label' => 'Lunas & Tervalidasi', 'class' => 'bg-success'],
                'tervalidasi' => ['label' => 'Lunas & Tervalidasi', 'class' => 'bg-success'],
            ];
            $st = $statusMap[$pembayaran->status] ?? ['label' => ucfirst($pembayaran->status), 'class' => 'bg-secondary'];
        @endphp

        <div class="text-center mb-4">
            <span class="badge {{ $st['class'] }} fs-6 px-4 py-2 rounded-pill">
                @if(in_array($pembayaran->status, ['lunas', 'tervalidasi']))<i class="bi bi-check-circle-fill me-1"></i>@endif
                @if($pembayaran->status === 'menunggu_validasi')<i class="bi bi-hourglass-split me-1"></i>@endif
                {{ $st['label'] }}
            </span>
        </div>

        {{-- Detail Tagihan --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="font-monospace small fw-semibold text-primary">{{ $pembayaran->kode_tagihan }}</span>
                <span class="small text-muted">{{ $siswa->nama_lengkap }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Item Seragam</th>
                                <th class="text-center">Ukuran</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pembayaran->details as $d)
                                <tr>
                                    <td>{{ $d->item->nama_item }}</td>
                                    <td class="text-center">{{ $d->item->ukuran ?? '-' }}</td>
                                    <td class="text-center">{{ $d->jumlah }}</td>
                                    <td class="text-end small">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="text-end fw-semibold small">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="fw-semibold">
                            <tr class="border-top border-2">
                                <td colspan="4" class="text-end">Total Tagihan</td>
                                <td class="text-end">Rp {{ number_format($pembayaran->total_tagihan, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end text-success">Sudah Dibayar</td>
                                <td class="text-end text-success">Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end text-danger">Sisa Tagihan</td>
                                <td class="text-end text-danger fw-bold">Rp
                                    {{ number_format($pembayaran->sisa_tagihan, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Info sedang menunggu validasi --}}
        @if($pembayaran->status === 'menunggu_validasi')
            <div class="alert alert-info d-flex align-items-center gap-2">
                <i class="bi bi-hourglass-split fs-5"></i>
                <div class="small">
                    Bukti pembayaran Anda sudah dikirim dan sedang diperiksa oleh admin koperasi.
                    Mohon tunggu validasi.
                </div>
            </div>
        @endif

        {{-- Form Upload Bukti --}}
        @if(in_array($pembayaran->status, ['belum', 'partial']))
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header">
                    <i class="bi bi-upload me-2 text-primary"></i>Upload Bukti Pembayaran
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success small">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger small">{{ session('error') }}</div>
                    @endif
                    <form action="{{ route('siswa.seragam.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label small fw-medium">Jumlah yang Dibayar (Rp)</label>
                                <input type="number" name="jumlah_bayar" class="form-control form-control-sm" min="1"
                                    max="{{ $pembayaran->sisa_tagihan }}" value="{{ $pembayaran->sisa_tagihan }}" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small fw-medium">Metode Pembayaran</label>
                                <select name="metode_bayar" class="form-select form-select-sm" required>
                                    <option>Transfer Bank</option>
                                    <option>QRIS</option>
                                    <option>Tunai</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-medium">
                                    Bukti Pembayaran <span class="text-muted">(JPG/PNG/PDF, maks 2MB)</span>
                                </label>
                                <input type="file" name="bukti_bayar" class="form-control form-control-sm"
                                    accept=".jpg,.jpeg,.png,.pdf" required>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-sm px-4">
                                    <i class="bi bi-send me-1"></i>Kirim Bukti Pembayaran
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- Sudah tervalidasi --}}
        @if(in_array($pembayaran->status, ['lunas', 'tervalidasi']))
            <div class="card border-0 shadow-sm text-center py-5">
                <div class="fs-1 text-success mb-2"><i class="bi bi-check-circle-fill"></i></div>
                <h5 class="fw-semibold text-success">Pembayaran Sudah Tervalidasi!</h5>
                <p class="text-muted small">
                    Divalidasi pada {{ $pembayaran->tanggal_validasi?->format('d F Y') }}
                    oleh {{ $pembayaran->validator?->name }}
                </p>
            </div>
        @endif

    @endif
@endsection