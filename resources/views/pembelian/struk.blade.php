@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="mb-3">
        <a href="#" class="text-decoration-none text-dark">&larr; Pembayaran</a>
    </div>

    <div class="card p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Pembayaran</h4>
            <div class="text-end">
                <p class="mb-1 fw-semibold">Invoice - #595</p>
                <p class="mb-0 text-muted">{{ date('d M Y') }}</p>
            </div>
        </div>

        <hr>

        <table class="table table-bordered">
            <thead class="table-light text-center">
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Quantity</th>
                    <th>Sub Total</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($produkData as $produk)
                <tr>
                    <td>{{ $produk['name'] }}</td>
                    <td>Rp. {{ number_format($produk['harga'], 0, ',', '.') }}</td>
                    <td>{{ $produk['qty'] }}</td>
                    <td>Rp. {{ number_format($produk['sub_total'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-between bg-light p-3 rounded mt-3">
            <div>
                <p class="mb-1 text-muted">POIN DIGUNAKAN</p>
                <h5 class="mb-0 fw-bold">{{ $point_digunakan }}</h5>
            </div>
            <div>
                <p class="mb-1 text-muted">{{ Auth::user()->name }}</p>
                <h5 class="mb-0 fw-bold">Petugas</h5>
            </div>
            <div>
                <p class="mb-1 text-muted">TOTAL</p>
                <h4 class="text-success mb-0 fw-bold">Rp. {{ number_format($totalSetelahPoin, 0, ',', '.') }}</h4>

            </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <a href="{{ url('/export-pembelian-pdf/' .$data['id']) }}" class="btn btn-danger">Unduh PDF</a>
            <a href="{{ url('/pembelian') }}" class="btn btn-secondary">Kembali</a>

        </div>
    </div>
</div>
@endsection
