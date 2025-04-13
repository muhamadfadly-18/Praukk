@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Pembayaran</h2>
        <div class="card p-4">
            <div class="d-flex justify-content-between">
                <div>
                    <h5>Invoice - #{{ uniqid() }}</h5>
                    <p>{{ $pembelian['tanggal'] }}</p>
                </div>
            </div>

            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Quantity</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pembelian['produk'] as $produk)
                        <tr>
                            <td>{{ $produk['name'] }}</td>
                            <td>Rp. {{ number_format($produk['harga_beli'], 0, ',', '.') }}</td>
                            <td>{{ $produk['jumlah'] }}</td>
                            <td>Rp. {{ number_format($produk['harga_beli'] * $produk['jumlah'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-between mt-3 p-3 bg-light">
                <div>
                    <p><strong>KASIR:</strong> {{ $pembelian['kasir'] }}</p>
                    <p><strong>KEMBALIAN:</strong> Rp. 0</p>
                </div>
                <div>
                    <h3 class="bg-dark text-white p-2">TOTAL: Rp. {{ number_format($pembelian['total_bayar'], 0, ',', '.') }}
                    </h3>
                </div>
            </div>

            <div class="mt-3">
                <button class="btn btn-primary">Unduh</button>
                <a href="{{ url('/') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
@endsection
