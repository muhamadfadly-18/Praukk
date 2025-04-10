@extends('layouts.app')

@section('title', 'Pembelian')

@section('content')
<div class="container">
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <a href="{{ url('/export-pembelian') }}" class="btn btn-sm btn-success">Export Excel</a>

            @if(auth()->user()->role == 'kasir')
                <a href="{{ url('/add/produk') }}" class="btn btn-sm btn-primary ms-auto">Tambah Pembelian</a>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="pembelianTable">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Nama Pelanggan</th>
                            <th>Tanggal Penjualan</th>
                            <th>Total Harga</th>
                            <th>Total Bayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pembelian as $pro)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $pro->member ? $pro->member->name : 'NON Members' }}</td>
                                <td>{{ $pro->tanggal_penjualan }}</td>
                                <td>Rp. {{ number_format($pro->total_harga, 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($pro->total_bayar, 0, ',', '.') }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detailpembelianModal{{ $pro->id }}">
                                        Lihat Detail
                                    </button>
                                    <a href="{{ url('/export-pembelian-pdf/' . $pro->id) }}" class="btn btn-danger">
                                        Export PDF
                                    </a>
                                </td>
                            </tr>

                            <!-- Modal -->
                            
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@foreach ($pembelian as $pro)
<div class="modal fade" id="detailpembelianModal{{ $pro->id }}" tabindex="-1" aria-labelledby="detailpembelianModalLabel{{ $pro->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Penjualan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Status:</strong> {{ ($pro->member && $pro->member->name) ? 'Members' : 'No Members' }}</p>    
                <p><strong>No. HP:</strong> {{ $pro->member->no_hp ?? '-' }}</p>
                <p><strong>Poin Member:</strong> {{ $pro->member->point ?? '-' }}</p>
                <p><strong>Bergabung Sejak:</strong> 
                    {{ $pro->member && $pro->member->created_at ? date('d F Y', strtotime($pro->member->created_at)) : '-' }}
                </p>

                <hr>
                @if (isset($dataGabungan[$pro->id]))
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataGabungan[$pro->id] as $item)
                                <tr>
                                    <td>{{ $item['produk']->name ?? 'Tidak ditemukan' }}</td>
                                    <td>{{ $item['qty'] }}</td>
                                    <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item['qty'] * $item['harga'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>Data tidak ditemukan.</p>
                @endif

                <h5 class="text-end mt-3"><strong>Total:</strong> Rp. {{ number_format($pro->total_harga ?? 0, 0, ',', '.') }}</h5>

                <p class="mt-3"><small>Dibuat pada: {{ $pro->created_at }}</small></p>
                <p><small>Oleh: kasir</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
