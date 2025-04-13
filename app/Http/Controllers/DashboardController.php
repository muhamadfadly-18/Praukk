<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;
use App\Models\Pembelian;
use App\Models\DetailPembelian;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $pembelians = Pembelian::selectRaw('DATE(tanggal_penjualan) as tanggal, COUNT(*) as total_pembelian')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')  // Urutkan berdasarkan tanggal dari yang lebih lama
            ->take(15)  // Ambil hanya 15 tanggal terakhir
            ->get();

        // Mengambil detail pembelian dan menghitung total qty berdasarkan id_produk yang sama
        $detailPembelains = DetailPembelian::all()->map(function ($detail) {
            // Asumsikan id_produk dan qty sudah berbentuk array
            $idProduk = $detail->id_produk;  // Array id_produk
            $qty = $detail->qty;  // Array qty

            // Gabungkan id_produk dan qty dalam sebuah array untuk pemrosesan lebih lanjut
            return collect($idProduk)->map(function ($id, $index) use ($qty) {
                return [
                    'id_produk' => $id,
                    'qty' => $qty[$index] ?? 0  // Mengambil qty yang sesuai dengan id_produk, pastikan ada index yang valid
                ];
            });
        })->flatten(1) // Menggabungkan hasil menjadi satu koleksi datanya
            ->groupBy('id_produk')  // Kelompokkan berdasarkan id_produk
            ->map(function ($items) {
                // Hitung total qty untuk setiap id_produk yang sama
                $totalQty = $items->sum('qty');
                $idProduk = $items->first()['id_produk'];

                // Ambil nama produk berdasarkan id_produk
                $produkName = \App\Models\Produk::where('id', $idProduk)->value('name');  // Mengambil nama produk
    
                return [
                    'id_produk' => $idProduk,
                    'produk_name' => $produkName,  // Menambahkan nama produk
                    'total_qty' => $totalQty
                ];
            });

        // dd($detailPembelains);

        $user = Auth::user();
        $today = Carbon::today();
        $jumlahPembelian = 0;

        if ($user->role == 'kasir') {
            // Hitung total pembelian hari ini (atau bisa juga total semua tergantung kebutuhan)
            $jumlahPembelian = Pembelian::whereDate('created_at', $today)->count();
        }

        // Mengirim data ke view
        return view('dashboard', compact('pembelians', 'detailPembelains', 'jumlahPembelian'));
    }





}
