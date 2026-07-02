{{-- resources/views/admin/seragam/show.blade.php --}}
@extends('layouts.admin')
@section('content')

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.seragam.index') }}" class="btn btn-sm btn-outline-secondary"><i
                class="bi bi-arrow-left"></i></a>
        <h5 class="fw-bold mb-0">Detail Pembayaran Seragam</h5>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header"><i class="bi bi-person me-2 text-primary"></i>Data Siswa</div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted small">Nama</td>
                            <td>{{ $seragam->siswaBaru->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">NISN</td>
                            <td>{{ $seragam->siswaBaru->nisn }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Kelas</td>
                            <td>{{ $seragam->siswaBaru->kelas ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Jurusan</td>
                            <td>{{ $seragam->siswaBaru->jurusan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Tahun Masuk</td>
                            <td>{{ $seragam->siswaBaru->tahun_masuk }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-header"><i class="bi bi-receipt me-2 text-primary"></i>Status Pembayaran</div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted small">Kode Tagihan</td>
                            <td class="font-monospace small">{{ $seragam->kode_tagihan }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Total Tagihan</td>
                            <td>Rp {{ number_format($seragam->total_tagihan, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Sudah Bayar</td>
                            <td class="text-success">Rp {{ number_format($seragam->jumlah_bayar, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Sisa</td>
                            <td class="text-danger fw-semibold">Rp {{ number_format($seragam->sisa_tagihan, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Metode</td>
                            <td>{{ $seragam->metode_bayar ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Status</td>
                            <td>
                                @php
                                    $b = match ($seragam->status) {
                                        'tervalidasi' => 'bg-success',
                                        'menunggu_validasi' => 'bg-info',
                                        'lunas' => 'bg-primary',
                                        'partial' => 'bg-warning text-dark',
                                        default => 'bg-secondary',
                                    };
                                    $label = match ($seragam->status) {
                                        'menunggu_validasi' => 'Menunggu Validasi',
                                        default => ucfirst($seragam->status),
                                    };
                                @endphp
                                <span class="badge {{ $b }}">{{ $label }}</span>
                            </td>
                        </tr>
                        @if($seragam->tanggal_validasi)
                            <tr>
                                <td class="text-muted small">Divalidasi</td>
                                <td>{{ $seragam->tanggal_validasi->format('d/m/Y') }} oleh {{ $seragam->validator?->name }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header"><i class="bi bi-bag me-2 text-primary"></i>Detail Item Seragam</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th class="text-center">Ukuran</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($seragam->details as $d)
                                <tr>
                                    <td>{{ $d->item->nama_item }}</td>
                                    <td class="text-center">{{ $d->item->ukuran ?? '-' }}</td>
                                    <td class="text-center">{{ $d->jumlah }}</td>
                                    <td class="text-end">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="fw-semibold">
                            <tr>
                                <td colspan="3" class="text-end">Total</td>
                                <td class="text-end">Rp {{ number_format($seragam->total_tagihan, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            @if($seragam->bukti_bayar)
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header"><i class="bi bi-image me-2 text-primary"></i>Bukti Pembayaran</div>
                    <div class="card-body text-center">
                        @if(Str::endsWith($seragam->bukti_bayar, ['.jpg', '.jpeg', '.png']))
                            <img src="{{ asset('storage/' . $seragam->bukti_bayar) }}" class="img-fluid rounded"
                                style="max-height:300px;">
                        @else
                            <a href="{{ asset('storage/' . $seragam->bukti_bayar) }}" target="_blank"
                                class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-file-pdf me-1"></i>Lihat PDF
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            @if($seragam->status === 'menunggu_validasi')
                <form action="{{ route('admin.seragam.validasi', $seragam) }}" method="POST" class="card border-0 shadow-sm">
                    @csrf
                    <div class="card-body">
                        <div class="alert alert-info small mb-3">
                            <i class="bi bi-info-circle me-1"></i>
                            Siswa sudah mengirim bukti pembayaran. Periksa bukti di atas, lalu validasi.
                        </div>
                        <label class="form-label small fw-medium">Catatan Validasi (opsional)</label>
                        <textarea name="catatan" class="form-control form-control-sm mb-3" rows="2"></textarea>
                        <button class="btn btn-success btn-sm w-100">
                            <i class="bi bi-check-circle me-1"></i>Validasi Pembayaran
                        </button>
                    </div>
                </form>
            @endif

            @if($seragam->status === 'tervalidasi')
                <a href="{{ route('admin.seragam.kwitansi', $seragam->id) }}" target="_blank"
                    class="btn btn-outline-secondary w-100">
                    <i class="bi bi-printer me-1"></i>Cetak Kwitansi
                </a>
            @endif

            @if($seragam->status === 'belum')
                <div class="alert alert-secondary small mb-0">
                    <i class="bi bi-hourglass me-1"></i>Siswa belum melakukan pembayaran.
                </div>
            @endif
        </div>
    </div>
@endsection
