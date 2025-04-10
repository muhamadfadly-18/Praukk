<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pembayaran</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        .total { text-align: right; font-weight: bold; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; }
    </style>
</head>
<body>

    <h2>Bukti Pembayaran</h2>

    <p>Nama Produk: {{ $pembelian->detail->produk->name ?? '-' }}</p>
    <p>Member Status: 
        @if($pembelian->member && $pembelian->member->nam3)
            Members
        @else
            No Members
        @endif
    </p>
    <p>No. HP: {{ $pembelian->member->no_hp ?? '-' }}</p>
    <p>Bergabung Sejak: {{ $pembelian->member->created_at ?? '-' }}</p>
    <p>Poin Member: {{ $pembelian->member->point ?? '-' }}</p>

    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>QTY</th>
                <th>Harga</th>
                <th>Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dataGabungan as $item)
            <tr>
                <td>{{ $item['produk']->name ?? '-' }}</td>
                <td>{{ $item['qty'] }}</td>
                <td>Rp. {{ number_format($item['harga'], 0, ',', '.') }}</td>
                <td>Rp. {{ number_format($item['qty'] * $item['harga'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    

    <table style="margin-top: 20px;">
        <tr>
            <td>Poin Digunakan</td>
            <td class="total">{{ $pembelian->poin_digunakan ?? '0' }}</td>
        </tr>
        <tr>
            <td>Total Harga</td>
            <td class="total">Rp. {{ number_format($pembelian->total_harga, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Jumlah Dibayar</td>
            <td class="total">Rp. {{ number_format($pembelian->total_bayar, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="footer">
        Terima kasih telah berbelanja bersama kami.<br>
        Bukti ini dicetak secara otomatis dan tidak memerlukan tanda tangan.
    </div>
</body>
</html>
