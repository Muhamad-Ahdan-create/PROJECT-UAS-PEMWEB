@extends('layouts.admin')
@section('content')
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.siswa-baru.index') }}" class="btn btn-sm btn-outline-secondary"><i
                class="bi bi-arrow-left"></i></a>
        <h5 class="fw-bold mb-0">Detail Siswa Baru</h5>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header"><i class="bi bi-person me-2 text-primary"></i>Data Siswa</div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted small">NISN</td>
                            <td class="font-monospace">{{ $siswaBaru->nisn }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Nama</td>
                            <td>{{ $siswaBaru->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Email</td>
                            <td>{{ $siswaBaru->user->email }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Kelas</td>
                            <td>{{ $siswaBaru->kelas ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Jurusan</td>
                            <td>{{ $siswaBaru->jurusan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Tahun Masuk</td>
                            <td>{{ $siswaBaru->tahun_masuk }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Orang Tua</td>
                            <td>{{ $siswaBaru->nama_orang_tua ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">No. Telp Ortu</td>
                            <td>{{ $siswaBaru->no_telp_ortu ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            @if($siswaBaru->pembayaran)
                @php $p = $siswaBaru->pembayaran; @endphp
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-bag-check me-2 text-primary"></i>Tagihan Seragam</span>
                        @php $b = match ($p->status) { 'tervalidasi' => 'bg-success', 'lunas' => 'bg-info', 'partial' => 'bg-warning text-dark', default => 'bg-secondary'}; @endphp
                        <span class="badge {{ $b }}">{{ ucfirst($p->status) }}</span>
                    </div>
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
                                @foreach($siswaBaru->pembayaran->details as $d)
                                    <tr>
                                        <td class="small">{{ $d->item->nama_item }}</td>
                                        <td class="text-center small">{{ $d->item->ukuran ?? '-' }}</td>
                                        <td class="text-center small">{{ $d->jumlah }}</td>
                                        <td class="text-end small">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="fw-semibold small">
                                <tr>
                                    <td colspan="3" class="text-end">Total Tagihan</td>
                                    <td class="text-end">Rp {{ number_format($p->total_tagihan, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end text-success">Sudah Bayar</td>
                                    <td class="text-end text-success">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end text-danger">Sisa</td>
                                    <td class="text-end text-danger">Rp {{ number_format($p->sisa_tagihan, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    @if(in_array($p->status, ['lunas', 'partial']))
                        <form action="{{ route('admin.seragam.validasi', $p->id) }}" method="POST"
                            onsubmit="return confirm('Validasi pembayaran ini?')">
                            @csrf
                            <button class="btn btn-success btn-sm">
                                <i class="bi bi-check-circle me-1"></i>Validasi Pembayaran
                            </button>
                        </form>
                    @endif
                    @if($p->status === 'tervalidasi')
                        <a href="{{ route('admin.seragam.kwitansi', $p->id) }}" target="_blank"
                            class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-printer me-1"></i>Cetak Kwitansi
                        </a>
                    @endif
                </div>
            @else
                <div class="card border-0 shadow-sm text-center py-4">
                    <p class="text-muted small mb-0">Belum ada tagihan seragam untuk siswa ini.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
