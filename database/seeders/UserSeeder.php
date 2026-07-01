<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Anggota;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name'     => 'Admin Koperasi',
            'email'    => 'admin@koperasi.com',
            'password' => Hash::make('password'),
            'is_active'=> true,
        ]);
        $admin->assignRole('admin');

        // Anggota contoh
        $user = User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'budi@koperasi.com',
            'password' => Hash::make('password'),
            'is_active'=> true,
        ]);
        $user->assignRole('anggota');

        Anggota::create([
            'user_id'         => $user->id,
            'nomor_anggota'   => 'KOP-001',
            'nama_lengkap'    => 'Budi Santoso',
            'alamat'          => 'Jl. Merdeka No. 1',
            'no_telp'         => '081234567890',
            'tanggal_bergabung' => now(),
            'simpanan_pokok'  => 100000,
            'simpanan_wajib'  => 50000,
        ]);
    }
}