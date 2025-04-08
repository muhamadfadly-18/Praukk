<div class="modal fade" id="detailpembelianModal{{ $pro->id }}" tabindex="-1" aria-labelledby="detailpembelianModalLabel{{ $pro->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Penjualan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Member Status:</strong> Member</p>
                <p><strong>No. HP:</strong> {{ $pro->no_hp }}</p>
                <p><strong>Poin Member:</strong> {{ $pro->poin_member }}</p>
                <p><strong>Bergabung Sejak:</strong> {{ date('d F Y', strtotime($pro->bergabung_sejak)) }}</p>

                <hr>

                @if ($pro->detail) 
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pro->detail as $item)
                                <tr>
                                    <td>{{ $item->nama_produk }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td>Rp. {{ number_format($item->harga * $item->qty, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <h5 class="text-end"><strong>Total:</strong> Rp. {{ number_format($pro->total_belanja, 0, ',', '.') }}</h5>
                @else
                    <p class="text-center text-danger">Tidak ada detail pembelian.</p>
                @endif

                <p><small>Dibuat pada: {{ $pro->created_at }}</small></p>
                <p><small>Oleh: {{ $pro->user_id }}</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
