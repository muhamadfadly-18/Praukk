<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;
use App\Models\Pembelian;
use App\Models\DetailPembelian;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
    
        // 1. Ambil semua pembelian hari ini
        $pembeliansToday = Pembelian::whereDate('created_at', $today)->get();
    
        $produkKeluar = [];
    
        foreach ($pembeliansToday as $pembelian) {
            $detailIds = $pembelian->id_detail_pembelian;

if (is_string($detailIds)) {
    $detailIds = json_decode($detailIds, true);
}

if (!is_array($detailIds)) {
    $detailIds = [$detailIds];
}
    
            foreach ($detailIds as $idDetail) {
                $detail = DetailPembelian::find($idDetail);
    
                if (!$detail) continue;
    
                $produkIds = is_string($detail->id_produk) ? json_decode($detail->id_produk, true) : $detail->id_produk;
                $qtys = is_string($detail->qty) ? json_decode($detail->qty, true) : $detail->qty;
                
                if (!is_array($produkIds) || !is_array($qtys)) continue;
    
                foreach ($produkIds as $index => $produkId) {
                    $qty = isset($qtys[$index]) ? $qtys[$index] : 0;
    
                    if (!isset($produkKeluar[$produkId])) {
                        $produkKeluar[$produkId] = 0;
                    }
    
                    $produkKeluar[$produkId] += $qty;
                }
            }
        }
    
        // Ambil nama produk
        $namaProduk = [];
        $jumlahKeluar = [];
    
        foreach ($produkKeluar as $produkId => $totalQty) {
            $produk = Produk::find($produkId);
            if ($produk) {
                $namaProduk[] = $produk->name;
                $jumlahKeluar[] = $totalQty;
            }
        }
    
        return view('dashboard', [
            'namaProduk' => $namaProduk,
            'jumlahKeluar' => $jumlahKeluar,
        ]);
    }
    
}
