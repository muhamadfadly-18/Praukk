<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Produk;
use App\Models\pembelian;
use App\Models\DetailPembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class MembersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $no_hp = $request->query('no_hp');

        // dd($data);
        // ✅ Cek apakah member sudah ada berdasarkan no_hp
        $member = Member::where('no_hp', $no_hp)->first();

        if ($member) {
            $memberId = $member->id;
            $name = $member->name ?? '';
            $point = $member->point ?? 0;
        } else {
            // ✅ Jika belum ada, buat member baru
            $newMember = Member::create([
                'no_hp' => $no_hp,
                'name' => $request->query('name', ''), // Bisa dikosongkan atau ambil dari query
                'point' => 0, // Default poin
            ]);

            $memberId = $newMember->id;
            $name = $newMember->name;
            $point = 0;
        }

        // dd($memberId);
        // Ambil data produk berdasarkan id_produk yang dikirimkan
        $ids = is_array($data['id_produk'] ?? null) ? $data['id_produk'] : [];
        $produk = Produk::whereIn('id', $ids)->pluck('name', 'id');
        $produks = Produk::whereIn('id', $ids)->get();
        $pembelian = Pembelian::latest()->first(['id']);


        // dd($pembelian);

        return view('pembelian.members', [
            'data' => $data,
            'no_hp' => $no_hp,
            'name' => $name,
            'point' => $point,
            'produk' => $produk,
            'produks' => $produks,
            'member_id' => $memberId,
            'pembelian' => $pembelian
        ]);
    }


    public function struk(Request $request)
    {
        $data = $request->all();
        $no_hp = $request->query('no_hp');
        $point_digunakan = 1500;

        // dd($data);
        // Validasi array input
        $id_produk = $data['id_produk'];
        $qty = $data['qty'];
        $harga = $data['harga'];

        // Cek kalau data dalam bentuk string (misalnya via query string), convert jadi array
        if (!is_array($id_produk)) {
            $id_produk = explode(',', $id_produk);
            $qty = explode(',', $qty);
            $harga = explode(',', $harga);
        }

        // Ambil nama produk dari database
        $produkFromDB = DB::table('produks')
            ->whereIn('id', $id_produk)
            ->pluck('name', 'id');

        // Buat array produk lengkap
        $produkData = [];
        foreach ($id_produk as $index => $id) {
            $produkData[] = [
                'name' => $produkFromDB[$id] ?? "Produk Tidak Ditemukan",
                'harga' => (int) $harga[$index],
                'qty' => (int) $qty[$index],
                'sub_total' => (int) $harga[$index] * (int) $qty[$index],
            ];
        }

        // Hitung total dan setelah dikurangi poin
        $totalHarga = array_sum(array_column($produkData, 'sub_total'));
        $totalSetelahPoin = max($totalHarga - $point_digunakan, 0);

        return view('pembelian.struk', compact(
            'data',
            'produkData',
            'totalSetelahPoin',
            'point_digunakan'
        ));
    }



    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validasi input
            $request->validate([
                'id_produk' => 'required|array',
                'id_produk.*' => 'exists:produks,id',
                'qty' => 'required|array',
                'qty.*' => 'integer|min:1',
                'harga' => 'required|array',
                'harga.*' => 'numeric|min:0',
                'total_bayar' => 'required|regex:/^\d+$/',
                // Tambahkan validasi untuk flag penggunaan point, jika dikirim
                'gunakan_poin' => 'nullable|in:0,1',

            ]);

            $noHp = $request->input('no_hp');
            $id_member = $request->input('id_member');
            $namaMember = $request->input('name');
            $usePoin = $request->input('gunakan_poin', 0); // 1 jika ingin pakai point
            $totalBayar = $request->input('total_bayar');
            // Cari member berdasarkan no_hp
            $member = Member::where('no_hp', $noHp)->first();
            $currentPoin = $member ? $member->point : 0;

            $poinTerpakai = 0;
            if ($request->input('gunakan_poin')) {
                $poinYangDigunakan = 150;
                $poinTerpakai = 150;
                $currentPoin -= 150;
            }
            // Jika member sudah ada, perbarui data, jika tidak, buat member baru
            if ($member) {
                $member->name = $namaMember;
                $member->point = $currentPoin; // Sudah dikurangi jika memakai point
                $member->save();
            } else {
                $member = Member::create([
                    'no_hp' => $noHp,
                    'name' => $namaMember,
                    'point' => $currentPoin,
                ]);
            }

            $totalBeli = 0;
            // $detailPembelianIds = [];

            $idProduks = $request->input('id_produk', []);
            $jumlahs = $request->input('qty', []);
            $hargas = $request->input('harga', []);
            $totalBayar = (float) preg_replace('/[^\d]/', '', $request->input('total_bayar'));

            $idProdukArray = [];
            $qtyArray = [];
            $hargaArray = [];
            $totalBeli = 0;

            // Loop untuk isi array saja (tanpa create DetailPembelian di dalam loop)
            foreach ($idProduks as $index => $produkId) {
                $jumlah = (int) $jumlahs[$index];
                $harga = (float) $hargas[$index];

                $dataProduk = Produk::find($produkId);
                if (!$dataProduk) {
                    return response()->json(['message' => 'Produk tidak ditemukan!'], 404);
                }

                $subtotal = $harga * $jumlah;
                $totalBeli += $subtotal;

                $idProdukArray[] = (string) $produkId;
                $qtyArray[] = $jumlah;
                $hargaArray[] = $harga;

                // Update stok
                $dataProduk->decrement('stock', $jumlah);
            }

            // Sekarang buat satu baris DetailPembelian
            $detailPembelian = DetailPembelian::create([
                'id_produk' => $idProdukArray,
                'qty' => $qtyArray,
                'harga' => $hargaArray,
            ]);

            // Simpan ID-nya saja
            $detailPembelianId = $detailPembelian->id;

            // Hitung kembalian
            // Hitung kembalian
            $kembalian = $totalBayar - $totalBeli;

            // Jika menggunakan poin, kurangi kembalian sebesar 1.500
            if ($usePoin) {
                $poinYangDigunakan = 150;
                $poinTerpakai = 150;
                $currentPoin -= 150;
                $kembalian -= 1500;

                if ($kembalian < 0) {
                    $kembalian = 0; // Supaya tidak negatif
                }
            }


            // Logika tambahan: jika total bayar lebih dari 100 ribu, tambahkan 100 point
            if ($totalBayar > 100000) {
                $currentPoin += 100;
            }

            // Update kembali point member di database
            $member->point = $currentPoin;
            $member->save();

            // Log::info('Menyimpan pembelian', [
            //     'id_member_dari_request' => $id_member,
            //     'id_member_yang_dipakai' => $id_member ?? $member->id,
            // ]);

            $idMemberFinal = $id_member ?? ($member ? $member->id : null);
            // Log::info('Final id_member untuk create pembelian: ' . $idMemberFinal);
//         // Simpan data pembelian
            $pembelian = Pembelian::create([
                'id_member' => $idMemberFinal,
                'id_detail_pembelian' => $detailPembelianId, // ⬅ hanya satu id, bukan array
                'total_harga' => $totalBeli,
                'total_bayar' => $totalBayar,
                'kembalian' => $kembalian,
                'tanggal_penjualan' => now(),
            ]);

            Log::info('Pembelian berhasil dibuat', [
                'id_pembelian' => $pembelian->id,
                'id_member' => $idMemberFinal,
                'id_detail_pembelian' => $detailPembelianId,
                'total_harga' => $totalBeli,
                'total_bayar' => $totalBayar,
                'kembalian' => $kembalian,
            ]);


            DB::commit();
            if ($pembelian && $pembelian->id) {

                return response()->json([
                    'success' => true,
                    'message' => 'Pembelian berhasil disimpan!',
                    'total_harga' => $totalBeli,
                    'total_bayar' => $totalBayar,
                    'kembalian' => $kembalian,
                    // Bisa juga mengirimkan data point yang terpakai dan saldo point saat ini
                    'poin_terpakai' => $poinTerpakai,
                    'poin_saat_ini' => $member->point,
                    'id_pembelian' => $pembelian->id, // ⬅ kirim id pembelian
                ]);

            } else {
                // Gagal membuat pembelian
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan data pembelian.',
                ], 500);
            }
            // return redirect()->route('struk', ['id' => $pembelian->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error('Gagal menyimpan pembelian: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal menyimpan data', 'error' => $e->getMessage()], 500);
        }
    }


}
