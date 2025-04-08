<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class DetailPembelian extends Model
{
    use HasFactory;

    protected $table = 'detail_pembelians'; // Sesuaikan dengan tabel detail pembelian

    protected $fillable = [
        'id_produk', 'harga', 'qty'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }    
    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id');
    }
}

