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

    protected $casts = [
        'id_produk' => 'array',  // Mengonversi kolom 'id_produk' menjadi array
        'harga'     => 'array',  // Mengonversi kolom 'harga' menjadi array
        'qty'       => 'array',  // Mengonversi kolom 'qty' menjadi array
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

