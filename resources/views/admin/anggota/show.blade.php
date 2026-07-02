@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Detail Anggota</h2>
        <a href="{{ route('admin.anggota.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th width="30%">Nomor Anggota</th>
                    <td>{{ $anggota->nomor_anggota }}</td>
                </tr>
                <tr>
                    <th>Nama Lengkap</th>
                    <td>{{ $anggota->nama_lengkap }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    {{-- Mengambil email dari relasi tabel user --}}
                    <td>{{ $anggota->user->email ?? 'Email tidak ditemukan' }}</td>
                </tr>
                <tr>
                    <th>No. Telepon</th>
                    <td>{{ $anggota->no_telp }}</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>{{ $anggota->alamat }}</td>
                </tr>
                <tr>
                    <th>Simpanan Pokok</th>
                    <td>Rp {{ number_format($anggota->simpanan_pokok, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Simpanan Wajib</th>
                    <td>Rp {{ number_format($anggota->simpanan_wajib, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
