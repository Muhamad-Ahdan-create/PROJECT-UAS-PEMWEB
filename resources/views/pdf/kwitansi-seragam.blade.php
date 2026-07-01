<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1a1a1a; }
.kop { text-align: center; border-bottom: 2px solid #1e40af; padding-bottom: 10px; margin-bottom: 14px; }
.kop h2 { font-size: 16px; font-weight: bold; color: #1e40af; }
.kop p { font-size: 10px; color: #555; }
.judul { text-align: center; font-size: 13px; font-weight: bold; text-transform: uppercase;
letter-spacing: 1px; margin-bottom: 14px; border-bottom: 1px dashed #999; padding-bottom: 8px; }
table.info { width: 100%; margin-bottom: 12px; }
table.info td { padding: 3px 4px; font-size: 11px; }
table.info td:first-child { width: 38%; color: #555; }
table.detail { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
table.detail th { background: #1e40af; color: #fff; padding: 5px 8px; font-size: 10px; text-align: left; }
table.detail td { padding: 5px 8px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
.total-row { font-weight: bold; background: #eff6ff; }
.status-box { border: 2px solid #16a34a; color: #16a34a; text-align: center;
padding: 6px; font-weight: bold; font-size: 13px; margin-bottom: 14px; border-radius: 4px; }
.ttd { display: flex; justify-content: flex-end; margin-top: 20px; }
.ttd-box { text-align: center; width: 160px; }
.ttd-box .line { border-top: 1px solid #333; margin-top: 40px; padding-top: 4px; font-size: 10px; }
</style>
</head>
<body>
<div class="kop">
<h2>KOPERASI SEKOLAH</h2>
<p>Jl. Pendidikan No. 1 | Telp: (021) 000-0000</p>
</div>
 
<div class="judul">Kwitansi Pembayaran Seragam</div>
 
<table class="info">
<tr><td>Kode Tagihan</td><td>: <strong>{{ $pembayaran->kode_tagihan }}</strong></td></tr>
<tr><td>Nama Siswa</td><td>: {{ $pembayaran->siswaBaru->nama_lengkap }}</td></tr>
<tr><td>NISN</td><td>: {{ $pembayaran->siswaBaru->nisn }}</td></tr>
<tr><td>Kelas / Jurusan</td><td>: {{ $pembayaran->siswaBaru->kelas }} / {{ $pembayaran->siswaBaru->jurusan }}</td></tr>
<tr><td>Tahun Masuk</td><td>: {{ $pembayaran->siswaBaru->tahun_masuk }}</td></tr>
<tr><td>Metode Bayar</td><td>: {{ $pembayaran->metode_bayar ?? '-' }}</td></tr>
<tr><td>Tanggal Validasi</td><td>: {{ $pembayaran->tanggal_validasi?->format('d F Y') ?? '-' }}</td></tr>
<tr><td>Divalidasi Oleh</td><td>: {{ $pembayaran->validator?->name ?? '-' }}</td></tr>
</table>
 
<table class="detail">
<thead>
<tr>
<th>No</th>
<th>Item Seragam</th>
<th>Ukuran</th>
<th style="text-align:center">Qty</th>
<th style="text-align:right">Harga</th>
<th style="text-align:right">Subtotal</th>
</tr>
</thead>
<tbody>
@foreach($pembayaran->details as $i => $d)
<tr>
<td>{{ $i + 1 }}</td>
<td>{{ $d->item->nama_item }}</td>
<td>{{ $d->item->ukuran ?? '-' }}</td>
<td style="text-align:center">{{ $d->jumlah }}</td>
<td style="text-align:right">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
<td style="text-align:right">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
</tr>
@endforeach
<tr class="total-row">
<td colspan="5" style="text-align:right">TOTAL TAGIHAN</td>
<td style="text-align:right">Rp {{ number_format($pembayaran->total_tagihan, 0, ',', '.') }}</td>
</tr>
<tr class="total-row">
<td colspan="5" style="text-align:right">JUMLAH BAYAR</td>
<td style="text-align:right">Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</td>
</tr>
<tr class="total-row">
<td colspan="5" style="text-align:right">SISA TAGIHAN</td>
<td style="text-align:right">Rp {{ number_format($pembayaran->sisa_tagihan, 0, ',', '.') }}</td>
</tr>
</tbody>
</table>
 
@if($pembayaran->status === 'tervalidasi')
<div class="status-box">✓ LUNAS &amp; TERVALIDASI</div>
@endif
 
<div class="ttd">
<div class="ttd-box">
<p style="font-size:10px">Petugas Koperasi,</p>
<div class="line">{{ $pembayaran->validator?->name ?? '____________________' }}</div>
</div>
</div>
</body>
</html>
