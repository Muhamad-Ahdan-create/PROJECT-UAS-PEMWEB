<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #111;
        }

        .kop {
            display: flex;
            border-bottom: 3px solid #1e40af;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }

        .kop-text h2 {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
        }

        .kop-text p {
            font-size: 10px;
            color: #666;
        }

        .judul {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 20px;
            border: 1px solid #1e40af;
            padding: 8px;
            background: #eff6ff;
        }

        .section {
            margin-bottom: 16px;
        }

        .section h3 {
            font-size: 12px;
            font-weight: bold;
            background: #1e40af;
            color: white;
            padding: 5px 10px;
            margin-bottom: 8px;
        }

        table.form {
            width: 100%;
        }

        table.form td {
            padding: 5px 8px;
            font-size: 11px;
        }

        table.form td:first-child {
            width: 35%;
            color: #444;
        }

        .dot {
            border-bottom: 1px dotted #333;
            padding-bottom: 2px;
            display: inline-block;
            min-width: 200px;
        }

        .ttd-grid {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .ttd-box {
            text-align: center;
            width: 160px;
        }

        .ttd-box .line {
            border-top: 1px solid #333;
            margin-top: 50px;
            padding-top: 4px;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="kop">
        <div class="kop-text">
            <h2>KOPERASI SEKOLAH</h2>
            <p>Jl. Pendidikan No. 1 | Telp: (021) 000-0000</p>
        </div>
    </div>

    <div class="judul">Formulir Pengajuan Pinjaman</div>

    <div class="section">
        <h3>A. Data Pemohon</h3>
        <table class="form">
            <tr>
                <td>Nomor Anggota</td>
                <td>: <span class="dot">{{ $pengajuan->anggota->nomor_anggota }}</span></td>
            </tr>
            <tr>
                <td>Nama Lengkap</td>
                <td>: <span class="dot">{{ $pengajuan->anggota->nama_lengkap }}</span></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>: <span class="dot">{{ $pengajuan->anggota->alamat }}</span></td>
            </tr>
            <tr>
                <td>No. Telepon</td>
                <td>: <span class="dot">{{ $pengajuan->anggota->no_telp }}</span></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h3>B. Rincian Pinjaman</h3>
        <table class="form">
            <tr>
                <td>Jumlah Pinjaman</td>
                <td>: <span class="dot">Rp {{ number_format($pengajuan->jumlah_diajukan, 0, ',', '.') }}</span></td>
            </tr>
            <tr>
                <td>Tenor</td>
                <td>: <span class="dot">{{ $pengajuan->tenor_diajukan }} Bulan</span></td>
            </tr>
            <tr>
                <td>Tujuan Pinjaman</td>
                <td>: <span class="dot">{{ $pengajuan->tujuan_pinjaman }}</span></td>
            </tr>
            <tr>
                <td>Jaminan</td>
                <td>: <span class="dot">{{ $pengajuan->jaminan ?? '-' }}</span></td>
            </tr>
            <tr>
                <td>Tanggal Pengajuan</td>
                <td>: <span class="dot">{{ $pengajuan->tanggal_pengajuan?->format('d F Y') }}</span></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h3>C. Pernyataan</h3>
        <p style="font-size:10px; line-height:1.7; padding: 0 8px;">
            Dengan ini saya menyatakan bahwa data yang saya isi adalah benar dan saya sanggup membayar
            angsuran tepat waktu sesuai ketentuan koperasi. Jika terjadi keterlambatan, saya bersedia
            dikenakan sanksi sesuai peraturan yang berlaku.
        </p>
    </div>

    <div class="ttd-grid">
        <div class="ttd-box">
            <p style="font-size:10px">Mengetahui,</p>
            <p style="font-size:10px">Ketua Koperasi</p>
            <div class="line">(_________________)</div>
        </div>
        <div class="ttd-box">
            <p style="font-size:10px">Pemohon,</p>
            <div class="line">{{ $pengajuan->anggota->nama_lengkap }}</div>
        </div>
    </div>
</body>

</html>
