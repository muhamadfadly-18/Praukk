<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pembelian</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .container { width: 100%; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #007bff; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="text-align: center;">Detail Pembelian</h2>
        <table>
            <tr>
                <th>ID</th>
                <td>{{ $pembelian->id }}</td>
            </tr>
            <tr>
                <th>Nama Pelanggan</th>
                <td>{{ $pembelian->nama_pelanggan }}</td>
            </tr>
            <tr>
                <th>Tanggal Penjualan</th>
                <td>{{ $pembelian->tanggal_penjualan }}</td>
            </tr>
            <tr>
                <th>Total Harga</th>
                <td>Rp {{ number_format($pembelian->total_belanja, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Dibuat Oleh</th>
                <td>{{ $pembelian->user_id }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
