<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetailPembelian;
use App\Models\Member;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelians'; // Sesuaikan dengan nama tabel di database


    protected $fillable = ['id_detail_pembelian', 'total_harga', 'total_bayar', 'kembalian', 'tanggal_penjualan', 'id_member',];


    public function detail()
    {
        return $this->belongsTo(DetailPembelian::class, 'id_detail_pembelian', 'id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'id_member');
    }


}
