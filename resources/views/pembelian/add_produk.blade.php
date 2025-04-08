@extends('layouts.app')

@section('title', 'Pembelian')

@section('content')
<style>
    .card {
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }

    .card:hover {
        transform: scale(1.05);
    }

    .card-img-top {
        border-radius: 8px;
    }

    .input-group {
        max-width: 150px;
        margin: 10px auto;
    }

    .input-group input {
        text-align: center;
        font-size: 18px;
    }

    .btn-outline-secondary {
        font-size: 18px;
        padding: 5px 12px;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        transition: 0.3s;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    .fixed-bottom-btn {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        width: 90%;
        max-width: 500px;
        z-index: 1000;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        @foreach($produks as $produk)
            <div class="col-md-4">
                <div class="card text-center mb-4 mt-4">
                    <div class="card-body">
                        <img src="{{ asset($produk->gambar) }}" class="card-img-top mx-auto d-block" alt="{{ $produk->nama }}" style="width: 150px; height: 210px; object-fit: cover;">
                        <h5 class="card-title mt-2">{{ $produk->name }}</h5>
                        <p class="card-text">Stock: <b id="stock-{{ $produk->id }}">{{ $produk->stock }}</b></p>
                        <p class="card-text text-success fw-bold">Harga: Rp {{ number_format($produk->harga_beli, 0, ',', '.') }}</p>
                        <p class="card-text text-danger fw-bold">Subtotal: Rp <span id="subtotal-{{ $produk->id }}">0</span></p>
                        <div class="input-group">
                            <button type="button" class="btn btn-outline-secondary" onclick="kurangiJumlah({{ $produk->id }}, {{ $produk->harga_beli }})">-</button>
                            <input type="number" class="form-control text-center" id="jumlah-{{ $produk->id }}" name="jumlah" value="0" min="0" max="{{ $produk->stock }}" readonly>
                            <button type="button" class="btn btn-outline-secondary" onclick="tambahJumlah({{ $produk->id }}, {{ $produk->stock }}, {{ $produk->harga_beli }})">+</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<form id="pesanForm" action="{{ url('/pembelian/simpan') }}" method="POST">
    @csrf
    <input type="hidden" id="produkData" name="produkData">
    <button type="submit" class="btn btn-primary fixed-bottom-btn" id="pesanBtn" onclick="submitPesanan()" disabled>Pesan</button>
</form>



<script>
function kurangiJumlah(id, harga) {
    let input = document.getElementById('jumlah-' + id);
    let subtotal = document.getElementById('subtotal-' + id);
    let stockElement = document.getElementById('stock-' + id);
    let jumlah = parseInt(input.value);
    let stock = parseInt(stockElement.innerText);
    if (jumlah > 0) {
        input.value = jumlah - 1;
        subtotal.innerText = formatRupiah((jumlah - 1) * harga);
        stockElement.innerText = stock + 1;
    }
    updateButtonState();
}

function tambahJumlah(id, maxStock, harga) {
    let input = document.getElementById('jumlah-' + id);
    let subtotal = document.getElementById('subtotal-' + id);
    let stockElement = document.getElementById('stock-' + id);
    let jumlah = parseInt(input.value);
    let stock = parseInt(stockElement.innerText);
    if (jumlah < maxStock) {
        input.value = jumlah + 1;
        subtotal.innerText = formatRupiah((jumlah + 1) * harga);
        stockElement.innerText = stock - 1;
    }
    updateButtonState();
}

function updateButtonState() {
    let jumlahProduk = 0;
    document.querySelectorAll('input[name="jumlah"]').forEach(input => {
        if (parseInt(input.value) > 0) {
            jumlahProduk++;
        }
    });

    let btnPesan = document.getElementById('pesanBtn');
    btnPesan.disabled = jumlahProduk === 0;
}

function formatRupiah(angka) {
    return angka.toLocaleString('id-ID').replace(/,/g, '.');
}

function submitPesanan() {
    let produkTerpilih = [];
    document.querySelectorAll('input[name="jumlah"]').forEach(input => {
        let id = input.id.split('-')[1];
        let jumlah = parseInt(input.value);
        let harga = document.querySelector(`#subtotal-${id}`).innerText.replace(/\./g, ''); // Ambil harga asli

        if (jumlah > 0) {
            produkTerpilih.push({
                id_produk: id,
                harga: harga,
                qty: jumlah
            });
        }
    });

    if (produkTerpilih.length > 0) {
        document.getElementById('produkData').value = JSON.stringify(produkTerpilih);
        document.getElementById('pesanForm').submit();
    } else {
        alert('Pilih minimal satu produk sebelum memesan!');
    }
}


function cekTombolPesan() {
    let tombolPesan = document.getElementById('pesanBtn');
    let adaProdukDipilih = false;

    document.querySelectorAll('input[name="jumlah"]').forEach(input => {
        if (parseInt(input.value) > 0) {
            adaProdukDipilih = true;
        }
    });

    tombolPesan.disabled = !adaProdukDipilih;
}

document.querySelectorAll('button').forEach(button => {
    button.addEventListener('click', cekTombolPesan);
});

</script>

@endsection
