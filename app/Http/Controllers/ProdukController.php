<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    

    


    /**
     * Display the specified resource.
     */
    public function show(produk $produk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(produk $produk)
    {
        //
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
        $data['harga_beli'] = str_replace('.', '', $data['harga_beli']);
        $data['stock'] = str_replace('.', '', $data['stock']);
    
        // Cek apakah ada file gambar yang diunggah
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($produk->gambar && Storage::exists('public/' . $produk->gambar)) {
                Storage::delete('public/' . $produk->gambar);
            }
    
            // Simpan gambar baru
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
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
public function updateStock(Request $request, $id)
{
    $request->validate([
        'stock' => 'required|numeric',
    ]);

    $produk = Produk::findOrFail($id);
    $produk->update(['stock' => $request->stock]);

    return redirect()->route('produk.index')->with('success', 'Stok produk berhasil diperbarui');
}

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, produk $produk)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        try {
            // Hapus gambar dari storage jika ada
            if ($produk->gambar) {
                \Storage::delete('public/' . $produk->gambar);
            }
    
            // Hapus produk dari database
            $produk->delete();
    
            return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('produk.index')->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
    
}
