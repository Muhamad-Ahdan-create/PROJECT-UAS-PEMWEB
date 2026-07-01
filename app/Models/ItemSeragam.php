<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemSeragam extends Model
{

    protected $table = 'item_seragam';

    protected $fillable = ['nama_item', 'harga', 'ukuran', 'stok'];

    protected $casts = ['harga' => 'decimal:2'];

    public function details()
    {
        return $this->hasMany(DetailSeragam::class);
    }
}
