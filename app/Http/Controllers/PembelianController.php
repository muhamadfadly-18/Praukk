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
         $pembelian = Pembelian::with('member')->get();
         $detailIds = $pembelian->pluck('id_detail_pembelian');
        //  $pembelian1 = Pembelian::latest()->first(['id']);
         // Ambil semua detail pembelian
         $detailPembelian = DetailPembelian::whereIn('id', $detailIds)->get()->keyBy('id');
     
         // Kumpulkan semua ID produk dari semua detail
         $allProdukIds = [];
         foreach ($detailPembelian as $detail) {
             $allProdukIds = array_merge($allProdukIds, $detail->id_produk);
         }
     
         // Ambil data produk dari database
         $produkList = Produk::whereIn('id', $allProdukIds)->get()->keyBy('id');
     
         // Gabungkan data untuk setiap pembelian
         $dataGabungan = [];
     
         foreach ($pembelian as $p) {
             $detail = $detailPembelian[$p->id_detail_pembelian] ?? null;
     
             if ($detail) {
                 $ids = $detail->id_produk;
                 $qty = $detail->qty;
                 $harga = $detail->harga;
     
                 $gabung = [];
                 for ($i = 0; $i < count($ids); $i++) {
                     $gabung[] = [
                         'produk' => $produkList[$ids[$i]] ?? null,
                         'qty'    => $qty[$i] ?? 0,
                         'harga'  => $harga[$i] ?? 0,
                     ];
                 }
     
                 $dataGabungan[$p->id] = $gabung;
             }
         }
     
        //  dd($pembelian1);
         return view('pembelian.index', compact('pembelian', 'dataGabungan'));
     }
     


    /**
     * Mengekspor data pembelian ke PDF.
     */
    public function exportPDF($id)
    {
      // Ambil data pembelian beserta relasi member
    $pembelian = Pembelian::with('member')->findOrFail($id);

    // Ambil data detail pembelian berdasarkan id_detail_pembelian
    $detail = DetailPembelian::findOrFail($pembelian->id_detail_pembelian);
    
        // dd($pembelian);
        $ids = $detail->id_produk;
        $qty = $detail->qty;
        $harga = is_array($detail->harga) ? $detail->harga : json_decode($detail->harga, true);
        
        
        // dd($ids, $qty, $harga);

        $ids = is_array($detail->id_produk) ? $detail->id_produk : (is_numeric($detail->id_produk) ? [$detail->id_produk] : json_decode($detail->id_produk, true));

        // dd($ids); // pastikan ini array

        $produkList = Produk::whereIn('id', $ids)->get()->keyBy('id');
        
    
        $dataGabungan = [];

        $dataGabungan = [];

    foreach ($ids as $index => $id_produk) {
        $dataGabungan[] = [
            'produk' => $produkList[$id_produk] ?? null,
            'qty'    => $qty[$index] ?? 0,
            'harga'  => $harga[$index] ?? 0,
        ];
    }

        
    

        // dd($dataGabungan);
        $pdf = PDF::loadView('pembelian.pembelian-pdf', compact('pembelian', 'dataGabungan'));
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
        // dd($produkData);

        if (empty($produkData)) {
            return redirect()->back()->with('error', 'Tidak ada produk yang dipilih!');
        }

        $produkIds = array_column($produkData, 'id_produk');
        $produks = Produk::whereIn('id', $produkIds)->get();

        foreach ($produks as $produk) {
            $produk->jumlah = collect($produkData)->firstWhere('id_produk', $produk->id)['qty'];
        }
     

        $pembelian = Pembelian::latest()->first(['id']);

        // dd($pembelian);
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
    
            $totalBeli = 0;
            $idProduks = $request->input('id_produk', []);
            $jumlahs = $request->input('qty', []);
            $hargas = $request->input('harga', []);
    
            $totalBayar = (float) preg_replace('/[^\d]/', '', $request->input('total_bayar'));
            $tanggalOrder = now();
    
            $idProdukArray = [];
            $hargaArray = [];
            $qtyArray = [];
    
            foreach ($idProduks as $index => $produkId) {
                $jumlah = (int) $jumlahs[$index];  
                $harga = (float) $hargas[$index]; 
    
                $produk = Produk::find($produkId);
                if (!$produk) {
                    return response()->json(['message' => 'Produk tidak ditemukan!'], 404);
                }
    
                $subtotal = $harga * $jumlah;
                $totalBeli += $subtotal;
    
                // Menambahkan data ke dalam array yang akan disimpan
                $idProdukArray[] = $produkId;
                $hargaArray[] = $harga;
                $qtyArray[] = $jumlah;
    
                // Kurangi stok produk
                $produk->decrement('stock', $jumlah);
            }
    
            // Simpan DetailPembelian dengan kolom jsonb
            $detailPembelian = DetailPembelian::create([
                'id_produk' => $idProdukArray,  // Menyimpan array produk ke kolom jsonb
                'harga'     => $hargaArray,     // Menyimpan array harga ke kolom jsonb
                'qty'       => $qtyArray,       // Menyimpan array qty ke kolom jsonb
            ]);
    
            // Simpan Pembelian
            $totalBayar = (float) preg_replace('/[^\d]/', '', $request->input('total_bayar'));
            $tanggalPenjualan = $request->input('tanggal_penjualan', now());
    
            $kembalian = $totalBayar - $totalBeli;
    
            Pembelian::create([
                'id_detail_pembelian' => json_encode([$detailPembelian->id]),
                'total_harga' => $totalBeli,
                'total_bayar' => $totalBayar,
                'kembalian' => $kembalian,
                'tanggal_penjualan' => $tanggalPenjualan,
            ]);
    
            DB::commit();
            return response()->json(['message' => 'Data pembelian berhasil disimpan!'], 200);
    
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
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
