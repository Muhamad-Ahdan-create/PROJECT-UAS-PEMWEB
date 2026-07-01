<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailSeragam extends Model
{
    protected $table = 'detail_seragam';

    protected $fillable = [
        'pembayaran_id',
        'item_seragam_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    public function pembayaran()
    {
        return $this->belongsTo(PembayaranSeragam::class);
    }

    public function item()
    {
        return $this->belongsTo(ItemSeragam::class, 'item_seragam_id');
    }
}
