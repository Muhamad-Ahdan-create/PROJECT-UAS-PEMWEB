@extends('layouts.anggota')
@section('content')

<div class="row justify-content-center">
<div class="col-lg-8">

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('anggota.pengajuan.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h5 class="fw-bold mb-0">Formulir Pengajuan Pinjaman</h5>
    </div>

    <form action="{{ route('anggota.pengajuan.store') }}" method="POST"
          enctype="multipart/form-data" id="formPengajuan">
        @csrf

        {{-- Data Pemohon --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header">
                <i class="bi bi-person me-2 text-primary"></i>Data Pemohon
            </div>
            <div class="card-body row g-3">
                <div class="col-sm-6">
                    <label class="form-label small fw-medium">Nomor Anggota</label>
                    <input type="text" class="form-control form-control-sm bg-light"
                           value="{{ auth()->user()->anggota->nomor_anggota }}" readonly>
                </div>
                <div class="col-sm-6">
                    <label class="form-label small fw-medium">Nama Lengkap</label>
                    <input type="text" class="form-control form-control-sm bg-light"
                           value="{{ auth()->user()->anggota->nama_lengkap }}" readonly>
                </div>
            </div>
        </div>

        {{-- Rincian Pinjaman --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header">
                <i class="bi bi-cash-coin me-2 text-primary"></i>Rincian Pinjaman
            </div>
            <div class="card-body row g-3">

                <div class="col-sm-6">
                    <label class="form-label small fw-medium">Jumlah Pinjaman (Rp)</label>
                    <input type="number" name="jumlah_diajukan" id="inputJumlah"
                           class="form-control form-control-sm @error('jumlah_diajukan') is-invalid @enderror"
                           min="100000" max="50000000" step="50000"
                           value="{{ old('jumlah_diajukan') }}"
                           placeholder="Contoh: 5000000"
                           required oninput="hitungSimulasi()">
                    @error('jumlah_diajukan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-sm-6">
                    <label class="form-label small fw-medium">Tenor (Bulan)</label>
                    <select name="tenor_diajukan" id="inputTenor"
                            class="form-select form-select-sm @error('tenor_diajukan') is-invalid @enderror"
                            required onchange="hitungSimulasi()">
                        <option value="">-- Pilih Tenor --</option>
                        @foreach([3,6,9,12,18,24,36] as $t)
                        <option value="{{ $t }}" {{ old('tenor_diajukan') == $t ? 'selected' : '' }}>
                            {{ $t }} Bulan
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Box Simulasi --}}
                <div class="col-12" id="boxSimulasi" style="display:none;">
                    <div class="p-3 rounded-3 border border-primary-subtle" style="background:#eff6ff;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-calculator text-primary"></i>
                            <span class="fw-semibold small text-primary">Simulasi Angsuran (Bunga 1.5%/bln flat)</span>
                        </div>
                        <div class="row g-2 text-center">
                            <div class="col-4">
                                <div class="p-2 bg-white rounded border">
                                    <div class="text-muted" style="font-size:.7rem;">Angsuran/Bulan</div>
                                    <div class="fw-bold text-primary small" id="outAngsuran">-</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 bg-white rounded border">
                                    <div class="text-muted" style="font-size:.7rem;">Total Bunga</div>
                                    <div class="fw-bold text-warning small" id="outBunga">-</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 bg-white rounded border">
                                    <div class="text-muted" style="font-size:.7rem;">Total Kewajiban</div>
                                    <div class="fw-bold text-danger small" id="outTotal">-</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label small fw-medium">Tujuan Pinjaman</label>
                    <textarea name="tujuan_pinjaman" rows="3"
                              class="form-control form-control-sm @error('tujuan_pinjaman') is-invalid @enderror"
                              placeholder="Jelaskan keperluan pinjaman Anda..."
                              required>{{ old('tujuan_pinjaman') }}</textarea>
                    @error('tujuan_pinjaman')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-sm-6">
                    <label class="form-label small fw-medium">
                        Jaminan <span class="text-muted">(opsional)</span>
                    </label>
                    <input type="text" name="jaminan"
                           class="form-control form-control-sm"
                           value="{{ old('jaminan') }}"
                           placeholder="Contoh: BPKB Motor, Sertifikat">
                </div>

                <div class="col-sm-6">
                    <label class="form-label small fw-medium">
                        Dokumen Pendukung <span class="text-muted">(PDF/JPG, maks 2MB)</span>
                    </label>
                    <input type="file" name="dokumen_path"
                           class="form-control form-control-sm"
                           accept=".pdf,.jpg,.jpeg,.png">
                </div>

            </div>
        </div>

        {{-- Pernyataan --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="chkSetuju" required>
                    <label class="form-check-label small" for="chkSetuju">
                        Saya menyatakan data di atas benar dan sanggup membayar angsuran
                        tepat waktu sesuai ketentuan koperasi.
                    </label>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4 fw-semibold">
                <i class="bi bi-send me-2"></i>Kirim Pengajuan
            </button>
            <a href="{{ route('anggota.pengajuan.index') }}" class="btn btn-outline-secondary">
                Batal
            </a>
        </div>

    </form>
</div>
</div>

<script>
const fmt = v => 'Rp ' + Math.round(v).toLocaleString('id-ID');

function hitungSimulasi() {
    const jumlah = parseFloat(document.getElementById('inputJumlah').value) || 0;
    const tenor  = parseInt(document.getElementById('inputTenor').value) || 0;
    const box    = document.getElementById('boxSimulasi');

    if (jumlah < 100000 || tenor < 1) { box.style.display = 'none'; return; }

    const totalBunga      = jumlah * 0.015 * tenor;
    const totalKewajiban  = jumlah + totalBunga;
    const angsuranPerBulan = totalKewajiban / tenor;

    document.getElementById('outAngsuran').textContent = fmt(angsuranPerBulan);
    document.getElementById('outBunga').textContent    = fmt(totalBunga);
    document.getElementById('outTotal').textContent    = fmt(totalKewajiban);
    box.style.display = 'block';
}
</script>
@endsection