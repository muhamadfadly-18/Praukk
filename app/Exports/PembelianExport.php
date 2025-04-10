<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PembelianExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $data = DB::table('pembelians')
            ->leftJoin('members', 'pembelians.id_member', '=', 'members.id')
            ->select(
                'pembelians.id',
                'members.name as name',
                'members.no_hp as no_hp',
                'members.point as point',
                DB::raw('COALESCE(pembelians.total_harga, 0) as total_harga'),
                DB::raw('COALESCE(pembelians.kembalian, 0) as kembalian'),
                'pembelians.tanggal_penjualan',
                'pembelians.id_detail_pembelian'
            )
            ->get();

        return $data->map(function ($item) {
            // Ambil detail pembelian
            $detail = DB::table('detail_pembelians')
                ->where('id', $item->id_detail_pembelian)
                ->first();

            // Decode JSON
            $produkIds = json_decode($detail->id_produk ?? '[]', true);
            $qtys = json_decode($detail->qty ?? '[]', true);

            // Gabungkan id produk dengan qty
            $produkQty = collect($produkIds)->mapWithKeys(function ($id, $index) use ($qtys) {
                return [$id => $qtys[$index] ?? 0];
            });

            // Ambil nama dan harga produk
            $produkData = DB::table('produks')
                ->whereIn('id', $produkIds)
                ->get(['id', 'name', 'harga_beli']);

            // Format nama + harga + qty
            $produkList = $produkData->map(function ($produk) use ($produkQty) {
                $qty = $produkQty[$produk->id] ?? 0;
                return $produk->name . ' (Rp. ' . number_format($produk->harga_beli, 0, ',', '.') . ' x ' . $qty . ')';
            })->implode(', ');

            return [
                $item->id,
                $item->name,
                $item->no_hp,
                $item->point,
                $produkList,
                'Rp. ' . number_format((int) $item->total_harga, 0, ',', '.'),
                'Rp. ' . number_format((int) $item->kembalian, 0, ',', '.'),
                $item->tanggal_penjualan,
            ];
        });
    }

    public function headings(): array
    {
        return [
            "ID", 
            "Nama Pelanggan",
            "Nomor HP Pelanggan",
            "Poin Pelanggan",
            "Produk (dengan Harga x Qty)",
            "Total Belanja", 
            "Kembalian",
            "Tanggal Penjualan",
        ];
    }
}
