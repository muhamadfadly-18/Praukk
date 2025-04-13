@extends('layouts.app')

@section('title', 'Produk')

@section('content')

    <style>
        .swal-confirm {
            margin-left: 10px !important;
            /* Menambah jarak antara tombol */
        }

        .table img {
            width: 80px;
            /* Ukuran gambar */
            height: 80px;
            /* Sesuaikan tinggi */
            object-fit: cover;
            /* Agar tidak terdistorsi */
            border-radius: 5px;
            /* Membuat gambar lebih rapi */
        }

        .search-input {
            width: 200px;
            margin-bottom: 10px;
        }
    </style>

    <div class="container">
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Daftar Produk</h3>
                @if (Auth::user()->role == 'admin')
                    <a href="#" class="btn btn-sm btn-primary ms-auto" data-bs-toggle="modal"
                        data-bs-target="#addProdukModal">
                        Tambah Produk
                    </a>
                @endif
            </div>

            <div class="card-body">
                <input type="text" id="searchInput" class="form-control search-input" placeholder="Cari produk...">

                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered text-center" id="produkTable">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 20%;">Nama</th>
                                <th style="width: 15%;">Gambar</th>
                                <th style="width: 15%;">Harga</th>
                                <th style="width: 10%;">Stock</th>
                                @if (Auth::user()->role == 'admin')
                                    <th style="width: 30%;">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id="produkTableBody">
                            @foreach ($produk as $pro)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-start">{{ $pro->name }}</td>
                                    <td>
                                        <img src="{{ asset($pro->gambar) }}" class="img-fluid">
                                    </td>
                                    <td>Rp {{ number_format($pro->harga_beli, 0, ',', '.') }}</td>
                                    <td>{{ $pro->stock }}</td>
                                    @if (Auth::user()->role == 'admin')
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#editProdukModal{{ $pro->id }}">
                                                Edit
                                            </a>
                                            <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editStockModal{{ $pro->id }}">
                                                Edit Stok
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                data-id="{{ $pro->id }}">
                                                Hapus
                                            </button>
                                            <form id="delete-form-{{ $pro->id }}"
                                                action="{{ route('produk.destroy', $pro->id) }}" method="POST"
                                                class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    @endif
                                </tr>

                                @include('produk.edit', ['pro' => $pro]) {{-- Modal Edit Produk --}}
                                @include('produk.edit_stock', ['pro' => $pro]) {{-- Modal Edit Stok --}}
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('produk.created') {{-- Modal Tambah Produk --}}

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Event delegation agar event tetap berfungsi setelah DOM berubah
            document.body.addEventListener("click", function(event) {
                if (event.target.classList.contains("delete-btn")) {
                    let produkId = event.target.getAttribute("data-id");

                    Swal.fire({
                        title: "Apakah Anda yakin?",
                        text: "Data yang dihapus tidak bisa dikembalikan!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Ya, Hapus!",
                        cancelButtonText: "Batal",
                        reverseButtons: true,
                        customClass: {
                            confirmButton: "btn btn-danger swal-confirm",
                            cancelButton: "btn btn-secondary swal-cancel"
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById("delete-form-" + produkId).submit();
                        }
                    });
                }
            });

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const produkTableBody = document.getElementById('produkTableBody');

            searchInput.addEventListener('input', function() {
                const filter = searchInput.value.toLowerCase();
                const rows = produkTableBody.getElementsByTagName('tr');

                for (let row of rows) {
                    const nameCell = row.cells[1]; // Nama produk
                    if (nameCell) {
                        const name = nameCell.textContent || nameCell.innerText;
                        row.style.display = name.toLowerCase().includes(filter) ? '' : 'none';
                    }
                }
            });
        });
    </script>

@endsection
