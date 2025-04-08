<?php

namespace App\Exports;

use App\Models\Pembelian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PembelianExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Pembelian::select('id', 'nama_pelanggan', 'tanggal_penjualan', 'total_belanja')->get();
    }

    public function headings(): array
    {
        return ["ID", "Nama Pelanggan", "Tanggal Penjualan", "Total Belanja"];
    }
}
