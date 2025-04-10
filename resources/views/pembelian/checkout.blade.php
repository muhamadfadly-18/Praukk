    @extends('layouts.app')

    @section('title', 'Checkout')

    @section('content')
    <div class="container">
        <div class="mt-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/add/produk">Penjualan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                </ol>   
            </nav>
            <h2 class="fw-bold">Penjualan</h2>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card shadow-sm p-4">
                    <h5 class="fw-bold">Produk yang dipilih</h5>
                    
                    @foreach ($produks as $produk)
                    <input type="hidden" name="id_produk[]" value="{{ $produk->id }}">
                    <input type="hidden" name="harga[]" value="{{ $produk->harga_beli }}">
                    <input type="hidden" name="qty[]" value="{{ $produk->jumlah }}">
                    <div class="d-flex justify-content-between">
                        <p>{{ $produk->name }}</p>
                        <p class="fw-bold">Rp {{ number_format($produk->harga_beli, 0, ',', '.') }}</p>
                    </div>
                    <p>Rp {{ number_format($produk->harga_beli, 0, ',', '.') }} X {{ $produk->jumlah }}</p>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between">
                        <h5 class="fw-bold">Total</h5>
                        <h5 class="fw-bold">Rp {{ number_format(collect($produks)->sum(fn($p) => $p->harga_beli * $p->jumlah), 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card shadow-sm p-4">
                    <div class="mb-3">
                        <label class="fw-bold">Member Status</label>
                        <select class="form-control" id="memberStatus">
                            <option value="non">Bukan Member</option>
                            <option value="member">Member</option>
                        </select>
                    </div>
                    
                    <div id="no_hpInput" class="mb-3" style="display: none;">
                        <label class="fw-bold">Nomor Telepon</label>
                        <input type="text" class="form-control" id="no_hpNumber" maxlength="13">

                        <small id="no_hpStatus" class="text-muted"></small>
                    </div>
                    
                    <form id="checkoutForm" method="POST" action="">
                        @csrf
                        @foreach ($produks as $produk)
                            <input type="hidden" name="id_produk[]" value="{{ $produk->id }}">
                            <input type="hidden" name="harga[]" value="{{ $produk->harga_beli }}">
                            <input type="hidden" name="qty[]" value="{{ $produk->jumlah }}">
                        @endforeach
                        <input type="hidden" name="id" value="{{ $pembelian->id ?? 0 }}">
                        <input type="hidden" name="total_harga" value="{{ collect($produks)->sum(fn($p) => $p->harga_beli * $p->jumlah) }}">
                        <input type="hidden" name="no_hp" id="hidden_no_hp">
                        <input type="hidden" name="member_status" id="hidden_member_status">
                        <div class="mb-3">
                            <label class="fw-bold">Total Bayar</label>
                            <input type="text" class="form-control" id="total_bayar" name="total_bayar" min="0">
                        </div>
                        <small id="total_bayar_error" class="text-danger" style="display: none;">Total Bayar harus lebih dari Total Harga</small>
                        
                        <button type="submit" id="pesanBtn" class="btn btn-primary w-100">Pesan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function () {
        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function unformatRupiah(angka) {
            return angka.replace(/[^\d]/g, ''); // Hanya mengambil angka
        }

        function cekTotal() {
            let total_harga = parseFloat($('input[name="total_harga"]').val());
            let total_bayar = parseFloat(unformatRupiah($('#total_bayar').val()));

            if (total_harga <= 0 || isNaN(total_bayar) || total_bayar < total_harga) {
                $('#pesanBtn').prop('disabled', true);
                $('#total_bayar_error').show();
            } else {
                $('#pesanBtn').prop('disabled', false);
                $('#total_bayar_error').hide();
            }
        }

        $('#memberStatus').change(function () {
            if ($(this).val() === 'member') {
                $('#no_hpInput').show();
            } else {
                $('#no_hpInput').hide();
                $('#no_hpNumber').val('');
            }
        });

        $('#total_bayar').on('input', function () {
            let angka = unformatRupiah($(this).val());
            $(this).val(formatRupiah(angka));
            cekTotal();
        });

        cekTotal(); // Panggil saat awal halaman dimuat

        $('#checkoutForm').submit(function (e) {
        e.preventDefault();

        let memberStatus = $('#memberStatus').val();
        let no_hp = $('#no_hpNumber').val().trim();
        let total_harga = parseFloat($('input[name="total_harga"]').val());
        let total_bayar = parseFloat(unformatRupiah($('#total_bayar').val()));

        $('#hidden_member_status').val(memberStatus);
        $('#hidden_no_hp').val(no_hp);

        console.log("Member Status:", memberStatus);
        console.log("No HP:", no_hp);
        console.log("Total Harga:", total_harga);
        console.log("Total Bayar:", total_bayar);

        let queryString = $('#checkoutForm').serialize();

        if (no_hp.length >= 10) {
        window.location.href = '/members?' + queryString;
    } else {
    let totalBayar = $('#totalBayarInput').val(); // ambil dari input user bayar

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let formData = $('#checkoutForm').serialize(); // ambil semua data form
    console.log('Data yang akan dikirim:', formData); // tampilkan data ke console

    $.ajax({
        url: '{{ route("checkout.simpanDetail") }}',
        type: 'POST',
        data: formData,
        success: function(response) {
            console.log('Detail pembelian berhasil disimpan:', response);
            window.location.href = '/struk?' + queryString;
        },
        error: function(xhr) {
            console.error('Gagal menyimpan detail pembelian:', xhr.responseJSON);
            alert('Gagal menyimpan detail pembelian: ' + xhr.responseJSON.message);
        }
    });
}

            
    });


    $('#no_hpNumber').on('input', function () {
        let no_hp = $(this).val().trim();

        // Validasi nomor HP harus diawali dengan 08 atau 62
        if (!/^((08|62)[0-9]*)$/.test(no_hp)) {
            $('#no_hpStatus').text('Nomor HP harus dimulai dengan 08 atau 62').addClass('text-danger');
            $('#pesanBtn').prop('disabled', true);
        } else if (no_hp.length < 10 || no_hp.length > 13) { 
            // Minimal 10 digit dan maksimal 13 digit
            $('#no_hpStatus').text('Nomor HP harus antara 10-13 digit').addClass('text-danger');
            $('#pesanBtn').prop('disabled', true);
        } else {
            $('#no_hpStatus').text('').removeClass('text-danger');
            $('#pesanBtn').prop('disabled', false);
        }
    });

    });
    </script>

    @endsection
