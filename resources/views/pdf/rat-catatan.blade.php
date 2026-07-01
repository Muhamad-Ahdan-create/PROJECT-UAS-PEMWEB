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
            text-align: center;
            border-bottom: 3px double #1e40af;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }

        .kop h2 {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
        }

        .kop p {
            font-size: 10px;
            color: #666;
        }

        .judul {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .sub-judul {
            text-align: center;
            font-size: 12px;
            color: #444;
            margin-bottom: 16px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }

        .section {
            margin-bottom: 16px;
        }

        .section h3 {
            font-size: 12px;
            font-weight: bold;
            color: #1e40af;
            border-left: 4px solid #1e40af;
            padding-left: 8px;
            margin-bottom: 8px;
        }

        .section p {
            font-size: 11px;
            line-height: 1.8;
            text-align: justify;
        }

        table.info {
            width: 100%;
            margin-bottom: 14px;
        }

        table.info td {
            padding: 3px 6px;
            font-size: 11px;
        }

        table.info td:first-child {
            width: 30%;
            color: #555;
        }

        .ttd-grid {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .ttd-box {
            text-align: center;
            width: 180px;
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
        <h2>KOPERASI SEKOLAH</h2>
        <p>Jl. Pendidikan No. 1 | Telp: (021) 000-0000</p>
    </div>

    <div class="judul">Notulensi Rapat Anggota Tahunan</div>
    <div class="sub-judul">Tahun {{ $rat->tahun_rat }}</div>

    <table class="info">
        <tr>
            <td>Tanggal</td>
            <td>: {{ $rat->tanggal_rat->format('d F Y') }}</td>
        </tr>
        <tr>
            <td>Tempat</td>
            <td>: {{ $rat->tempat }}</td>
        </tr>
        <tr>
            <td>Dicatat Oleh</td>
            <td>: {{ $rat->pembuat->name }}</td>
        </tr>
    </table>

    <div class="section">
        <h3>Agenda Rapat</h3>
        <p>{{ $rat->agenda }}</p>
    </div>

    @if($rat->notulensi)
        <div class="section">
            <h3>Notulensi / Jalannya Rapat</h3>
            <p>{{ $rat->notulensi }}</p>
        </div>
    @endif

    @if($rat->hasil_keputusan)
        <div class="section">
            <h3>Hasil Keputusan Rapat</h3>
            <p>{{ $rat->hasil_keputusan }}</p>
        </div>
    @endif

    <div class="ttd-grid">
        <div class="ttd-box">
            <p style="font-size:10px">Ketua Koperasi,</p>
            <div class="line">(_________________)</div>
        </div>
        <div class="ttd-box">
            <p style="font-size:10px">Sekretaris / Notulis,</p>
            <div class="line">{{ $rat->pembuat->name }}</div>
        </div>
    </div>
</body>

</html>