<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produk = Produk::all();
        return view('produk.index', compact('produk'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif',
            'harga_beli' => 'required',
            'stock' => 'required|numeric',
        ]);

        // Hapus titik pemisah ribuan dari harga sebelum disimpan ke database
        $data['harga_beli'] = str_replace('.', '', $data['harga_beli']);
        $data['stock'] = str_replace('.', '', $data['stock']);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName(); // Nama unik
            $file->move(public_path('image/produk'), $namaFile); // Simpan ke public/image/produk
            $data['gambar'] = 'image/produk/' . $namaFile; // Simpan path ke database
        }

        try {
            Produk::create($data);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan');
    }



    public function update(Request $request, Produk $produk)
    {
        Log::info('Data sebelum update:', $produk->toArray());

        // Validasi input
        $data = $request->validate([
            'name' => 'required',
            'harga_beli' => 'required|numeric',
            'stock' => 'nullable|numeric', // Stock tetap nullable karena di form disabled
        ]);
        Log::info('Stock value: ' . ($data['stock'] ?? 'null'));

        $data['harga_beli'] = str_replace('.', '', $data['harga_beli']);

        if (isset($data['stock'])) {
            $data['stock'] = str_replace('.', '', $data['stock']);
        }
        

        // Cek apakah ada file gambar yang diunggah
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            $oldPath = public_path($produk->gambar); // gambar disimpan sebagai 'image/produk/namafile.jpg'
            if ($produk->gambar && file_exists($oldPath)) {
                unlink($oldPath); // hapus file lama
            }
        
            // Simpan gambar baru ke public/image/produk
            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName(); // nama unik
            $file->move(public_path('image/produk'), $namaFile);
        
            // Simpan path gambar ke database
            $data['gambar'] = 'image/produk/' . $namaFile;
        }
        

        // Update data produk
        $produk->update($data);

        Log::info('Data setelah update:', $produk->toArray());

        // Cek apakah ada perubahan dalam data
        if ($produk->wasChanged()) {
            return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui');
        } else {
            return back()->with('error', 'Tidak ada perubahan pada produk');
        }
    }
    //update stock
    public function updateStock(Request $request, $id)
    {
        // Hilangkan titik dulu agar bisa divalidasi sebagai angka
        $stock = str_replace('.', '', $request->stock);
    
        $request->merge(['stock' => $stock]);
    
        $request->validate([
            'stock' => 'required|numeric',
        ]);
    
        $produk = Produk::findOrFail($id);
        $produk->update(['stock' => $request->stock]);
    
        return redirect()->route('produk.index')->with('success', 'Stok produk berhasil diperbarui');
    }
    


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        try {
            if ($produk->gambar) {
                $oldPath = public_path($produk->gambar); // contoh: public/image/produk/nama.jpg
                if (file_exists($oldPath)) {
                    unlink($oldPath); // hapus file
                }
            }            

            // Hapus produk dari database
            $produk->delete();

            return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('produk.index')->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

}
