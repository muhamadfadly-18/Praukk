<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Member;
use App\Models\Produk;
use App\Models\DetailPembelian;
use Illuminate\Http\Request;
use PDF;
use DB;

class PembelianController extends Controller
{
    /**
     * Menampilkan daftar pembelian.
     */
    public function index()
    {
        $pembelian = Pembelian::with('detail','member')->get();

        // dd($pembelian);
        return view('pembelian.index', compact('pembelian'));
    }

    /**
     * Mengekspor data pembelian ke PDF.
     */
    public function exportPDF($id)
    {
        $pembelian = Pembelian::find($id);

        if (!$pembelian) {
            abort(404, 'Data tidak ditemukan');
        }

        $pdf = PDF::loadView('pembelian.pembelian-pdf', compact('pembelian'));
        return $pdf->download('pembelian_' . $pembelian->id . '.pdf');
    }

    /**
     * Menampilkan halaman tambah produk.
     */
    public function add_produk()
    {
        $produks = Produk::all();
        return view('pembelian.add_produk', compact('produks'));
    }

    /**
     * Menampilkan halaman checkout.
     */
    public function checkout(Request $request)
    {
        $produkData = session()->get('produkData', '[]');
        $produkData = json_decode($produkData, true);

        if (empty($produkData)) {
            return redirect()->back()->with('error', 'Tidak ada produk yang dipilih!');
        }

        $produkIds = array_column($produkData, 'id_produk');
        $produks = Produk::whereIn('id', $produkIds)->get();

        foreach ($produks as $produk) {
            $produk->jumlah = collect($produkData)->firstWhere('id_produk', $produk->id)['qty'];
        }

        $pembelian = Pembelian::latest()->first();
        return view('pembelian.checkout', compact('produks', 'pembelian'));
    }

    /**
     * Menyimpan data pembelian sementara.
     */
    public function simpanPembelian(Request $request)
    {
        $produkData = json_decode($request->produkData, true);

        if (empty($produkData)) {
            return redirect()->back()->with('error', 'Tidak ada produk yang dipilih!');
        }

        return redirect()->route('checkout')->with([
            'success' => 'Data produk berhasil dikirim!',
            'produkData' => $request->produkData
        ]);
    }

    /**
     * Mengecek keberadaan member berdasarkan nomor HP.
     */
    public function cekMember(Request $request)
    {
        $member = Member::where('no_hp', $request->no_hp)->first();
        return response()->json(['status' => $member ? 'ada' : 'tidak_ada']);
    }

    /**
     * Menyimpan detail pembelian.
     */
    public function simpanDetail(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $detailPembelianIds = [];
            $totalBeli = 0;
    
            $idProduks = $request->input('id_produk');
            $jumlahs = $request->input('qty');
            $hargas = $request->input('harga');
            
            // Hapus karakter non-numerik dari total_bayar sebelum dikonversi
            $totalBayar = (float) preg_replace('/[^\d]/', '', $request->input('total_bayar'));
            $totalBeli = (float) preg_replace('/[^\d]/', '', $request->input('total_harga'));
            $tanggalOrder = now();
    
            foreach ($idProduks as $index => $produkId) {
                $jumlah = (int) $jumlahs[$index];  
                $harga = (float) $hargas[$index]; 
    
                $dataProduk = Produk::find($produkId);
                if (!$dataProduk) {
                    return response()->json(['message' => 'Produk tidak ditemukan!'], 404);
                }
    
                $subtotal = $harga * $jumlah;
                $totalBeli += $subtotal;
    
                $detailPembelian = DetailPembelian::create([
                    'id_produk' => $produkId,
                    'qty' => $jumlah,
                    'harga' => $harga,
                ]);
    
                $detailPembelianIds[] = $detailPembelian->id;
    
                // Kurangi stok produk
                $dataProduk->decrement('stock', $jumlah);
            }
    
            $kembalian = $totalBayar - $totalBeli;
            $idDetailPembelianString = implode(',', $detailPembelianIds);
            $tanggalPenjualan = $request->input('tanggal_penjualan', now());
    
            Pembelian::create([
                'id_detail_pembelian' => $idDetailPembelianString, 
                'total_harga' => $totalBeli,
                'total_bayar' => $totalBayar,
                'kembalian' => $kembalian,
                'tanggal_order' => $tanggalOrder,
                'tanggal_penjualan' => $tanggalPenjualan,
            ]);
    
            DB::commit();
    
            return response()->json([
                'message' => 'Detail pembelian berhasil disimpan!',
                'total_harga' => $totalBeli,
                'total_bayar' => $totalBayar,
                'kembalian' => $kembalian
            ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal menyimpan data', 
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    
    
    
    

    /**
     * Menyimpan data pembelian ke database.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_member' => 'required|exists:members,id',
            'name' => 'required|exists:members,name',
            'tanggal_penjualan' => 'required|date',
            'total_harga' => 'required|numeric',
        ]);

        try {
            Pembelian::create($data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan pembelian: ' . $e->getMessage());
        }

        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil ditambahkan');
    }
}
