<?php

namespace App\Exports;

use App\Models\Anggota;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AnggotaExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Anggota::with('user')->get();
    }

    public function headings(): array
    {
        return [
            'No. Anggota', 'Nama Lengkap', 'Email',
            'No. Telp', 'Alamat', 'Tanggal Bergabung',
            'Simpanan Pokok', 'Simpanan Wajib',
        ];
    }

    public function map($anggota): array
    {
        return [
            $anggota->nomor_anggota,
            $anggota->nama_lengkap,
            $anggota->user->email ?? '-',
            $anggota->no_telp ?? '-',
            $anggota->alamat ?? '-',
            $anggota->tanggal_bergabung,
            $anggota->simpanan_pokok,
            $anggota->simpanan_wajib,
        ];
    }
}