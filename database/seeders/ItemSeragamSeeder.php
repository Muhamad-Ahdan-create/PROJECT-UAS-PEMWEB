<?php

namespace Database\Seeders;

use App\Models\ItemSeragam;
use Illuminate\Database\Seeder;

class ItemSeragamSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['nama_item' => 'Baju Putih Lengan Panjang', 'harga' => 85000, 'ukuran' => 'S', 'stok' => 50],
            ['nama_item' => 'Baju Putih Lengan Panjang', 'harga' => 85000, 'ukuran' => 'M', 'stok' => 50],
            ['nama_item' => 'Baju Putih Lengan Panjang', 'harga' => 85000, 'ukuran' => 'L', 'stok' => 50],
            ['nama_item' => 'Celana Abu-abu',            'harga' => 95000, 'ukuran' => 'S', 'stok' => 50],
            ['nama_item' => 'Celana Abu-abu',            'harga' => 95000, 'ukuran' => 'M', 'stok' => 50],
            ['nama_item' => 'Rok Abu-abu',               'harga' => 90000, 'ukuran' => 'S', 'stok' => 30],
            ['nama_item' => 'Rok Abu-abu',               'harga' => 90000, 'ukuran' => 'M', 'stok' => 30],
            ['nama_item' => 'Seragam Pramuka Atas',      'harga' => 80000, 'ukuran' => 'M', 'stok' => 40],
            ['nama_item' => 'Seragam Pramuka Bawah',     'harga' => 75000, 'ukuran' => 'M', 'stok' => 40],
            ['nama_item' => 'Sepatu Hitam',              'harga' => 120000,'ukuran' => '38','stok' => 20],
            ['nama_item' => 'Dasi',                      'harga' => 25000, 'ukuran' => null,'stok' => 100],
            ['nama_item' => 'Ikat Pinggang',             'harga' => 20000, 'ukuran' => null,'stok' => 100],
        ];

        foreach ($items as $item) {
            ItemSeragam::create($item);
        }
    }
}