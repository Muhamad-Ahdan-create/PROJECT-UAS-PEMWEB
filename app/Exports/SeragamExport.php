<?php

namespace App\Exports;

use App\Models\PembayaranSeragam;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SeragamExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return PembayaranSeragam::with(['siswaBaru', 'validator'])->get();
    }

    public function headings(): array
    {
        return [
            'Kode Tagihan',
            'Nama Siswa',
            'NISN',
            'Kelas',
            'Jurusan',
            'Total Tagihan',
            'Jumlah Bayar',
            'Sisa',
            'Status',
            'Divalidasi Oleh',
            'Tanggal Validasi',
        ];
    }

    public function map($p): array
    {
        return [
            $p->kode_tagihan,
            $p->siswaBaru->nama_lengkap,
            $p->siswaBaru->nisn,
            $p->siswaBaru->kelas ?? '-',
            $p->siswaBaru->jurusan ?? '-',
            $p->total_tagihan,
            $p->jumlah_bayar,
            $p->sisa_tagihan,
            ucfirst($p->status),
            $p->validator?->name ?? '-',
            $p->tanggal_validasi?->format('d/m/Y') ?? '-',
        ];
    }
}
