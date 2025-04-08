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
                                    <button type="button" href="{{ url('/export-pembelian-pdf/' . $pro->id) }}" class="btn btn-danger">
                                        Export PDF
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                                @if ($pembelian->isNotEmpty())
                    @include('pembelian.detail', ['pro' => $pembelian->first()])
                @endif
            </div>
        </div>
    </div>
</div>

@stack('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var myModal = new bootstrap.Modal(document.getElementById('detailpembelianModal'));
    });
</script>
@endsection
