{{-- resources/views/admin/pengajuan/show.blade.php --}}
@extends('layouts.admin')
@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="d-flex align-items-center gap-2 mb-4">
                <a href="{{ route('admin.pengajuan.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h5 class="fw-bold mb-0">Review Pengajuan Pinjaman</h5>
            </div>

            {{-- Status Banner --}}
            @php
                $statusConfig = [
                    'diajukan' => ['class' => 'alert-warning', 'icon' => 'bi-hourglass-split', 'label' => 'Menunggu Keputusan'],
                    'diproses' => ['class' => 'alert-info', 'icon' => 'bi-gear', 'label' => 'Sedang Diproses'],
                    'disetujui' => ['class' => 'alert-success', 'icon' => 'bi-check-circle-fill', 'label' => 'Disetujui'],
                    'ditolak' => ['class' => 'alert-danger', 'icon' => 'bi-x-circle-fill', 'label' => 'Ditolak'],
                ];
                $sc = $statusConfig[$pengajuan->status] ?? ['class' => 'alert-secondary', 'icon' => 'bi-question', 'label' => ucfirst($pengajuan->status)];
            @endphp

            <div class="alert {{ $sc['class'] }} d-flex align-items-center gap-2 mb-4">
                <i class="bi {{ $sc['icon'] }} fs-5"></i>
                <div>
                    <strong>Status: {{ $sc['label'] }}</strong>
                    @if($pengajuan->catatan_admin)
                        <div class="small mt-1">Catatan: {{ $pengajuan->catatan_admin }}</div>
                    @endif
                </div>
            </div>

            {{-- Data Pemohon --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header"><i class="bi bi-person me-2 text-primary"></i>Data Pemohon</div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted small" width="180">Nomor Anggota</td>
                            <td class="font-monospace">{{ $pengajuan->anggota->nomor_anggota }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Nama Lengkap</td>
                            <td>{{ $pengajuan->anggota->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">No. Telepon</td>
                            <td>{{ $pengajuan->anggota->no_telp ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Alamat</td>
                            <td>{{ $pengajuan->anggota->alamat ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Tanggal Bergabung</td>
                            <td>{{ \Carbon\Carbon::parse($pengajuan->anggota->tanggal_bergabung)->format('d F Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Rincian Pengajuan --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header"><i class="bi bi-file-earmark-text me-2 text-primary"></i>Rincian Pengajuan</div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted small" width="180">Jumlah Diajukan</td>
                            <td class="fw-bold fs-5 text-primary">Rp
                                {{ number_format($pengajuan->jumlah_diajukan, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Tenor</td>
                            <td>{{ $pengajuan->tenor_diajukan }} Bulan</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Tujuan Pinjaman</td>
                            <td>{{ $pengajuan->tujuan_pinjaman }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Jaminan</td>
                            <td>{{ $pengajuan->jaminan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Tanggal Pengajuan</td>
                            <td>{{ $pengajuan->tanggal_pengajuan?->format('d F Y H:i') ?? '-' }}</td>
                        </tr>
                        @if($pengajuan->dokumen_path)
                            <tr>
                                <td class="text-muted small">Dokumen Pendukung</td>
                                <td>
                                    <a href="{{ asset('storage/' . $pengajuan->dokumen_path) }}" target="_blank"
                                        class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-file-earmark me-1"></i>Lihat Dokumen
                                    </a>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Simulasi Angsuran --}}
            @php
                $totalBunga = $pengajuan->jumlah_diajukan * 0.015 * $pengajuan->tenor_diajukan;
                $totalKewajiban = $pengajuan->jumlah_diajukan + $totalBunga;
                $angsuran = $totalKewajiban / $pengajuan->tenor_diajukan;
            @endphp
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header"><i class="bi bi-calculator me-2 text-primary"></i>Simulasi Angsuran (Bunga 1.5%/bln
                    flat)</div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-4">
                            <div class="p-2 bg-light rounded">
                                <div class="text-muted small">Angsuran/Bulan</div>
                                <div class="fw-bold text-primary">Rp {{ number_format($angsuran, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 bg-light rounded">
                                <div class="text-muted small">Total Bunga</div>
                                <div class="fw-bold text-warning">Rp {{ number_format($totalBunga, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 bg-light rounded">
                                <div class="text-muted small">Total Kewajiban</div>
                                <div class="fw-bold text-danger">Rp {{ number_format($totalKewajiban, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Riwayat Pinjaman Anggota --}}
            @php
                $riwayatPinjaman = $pengajuan->anggota->pinjaman()->latest()->take(3)->get();
            @endphp
            @if($riwayatPinjaman->isNotEmpty())
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Pinjaman Sebelumnya
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th class="text-end">Jumlah</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayatPinjaman as $rp)
                                    <tr>
                                        <td class="font-monospace small">{{ $rp->kode_pinjaman }}</td>
                                        <td class="text-end small">Rp {{ number_format($rp->jumlah_pinjaman, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            @php
                                                $rb = match ($rp->status) {
                                                    'lunas' => 'bg-primary',
                                                    'disetujui' => 'bg-success',
                                                    default => 'bg-secondary',
                                                };
                                            @endphp
                                            <span class="badge {{ $rb }}">{{ ucfirst($rp->status) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Tombol Aksi --}}
            @if($pengajuan->status === 'diajukan')
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <p class="small text-muted mb-3">
                            <i class="bi bi-info-circle me-1"></i>
                            Pastikan data sudah ditinjau dengan teliti sebelum mengambil keputusan.
                        </p>
                        <div class="d-flex gap-2">
                            <form action="{{ route('admin.pengajuan.setujui', $pengajuan->id) }}" method="POST"
                                class="flex-fill">
                                @csrf
                                <button type="submit" class="btn btn-success w-100"
                                    onclick="return confirm('Setujui pengajuan ini? Pinjaman dan jadwal angsuran akan otomatis dibuat.')">
                                    <i class="bi bi-check-lg me-1"></i>Setujui Pengajuan
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger flex-fill" data-bs-toggle="modal"
                                data-bs-target="#modalTolak">
                                <i class="bi bi-x-lg me-1"></i>Tolak Pengajuan
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Modal Tolak --}}
                <div class="modal fade" id="modalTolak" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{ route('admin.pengajuan.tolak', $pengajuan->id) }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h6 class="modal-title fw-semibold">Tolak Pengajuan</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="small text-muted">
                                        Pengajuan dari <strong>{{ $pengajuan->anggota->nama_lengkap }}</strong>
                                        sebesar <strong>Rp
                                            {{ number_format($pengajuan->jumlah_diajukan, 0, ',', '.') }}</strong>
                                    </p>
                                    <label class="form-label small fw-medium">Alasan Penolakan</label>
                                    <textarea name="catatan_admin" class="form-control form-control-sm" rows="3" required
                                        placeholder="Jelaskan alasan penolakan, contoh: dokumen jaminan tidak lengkap..."></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-secondary"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-sm btn-danger">Tolak Pengajuan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if($pengajuan->status === 'disetujui')
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.pengajuan.cetak', $pengajuan->id) }}" target="_blank"
                        class="btn btn-outline-secondary flex-fill">
                        <i class="bi bi-printer me-1"></i>Cetak Formulir
                    </a>
                </div>
            @endif

        </div>
    </div>
@endsection
