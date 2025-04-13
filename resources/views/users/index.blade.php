@extends('layouts.app')

@section('title', 'User')

@section('content')
    <style>
        .swal-confirm {
            margin-left: 10px !important;
            /* Menambah jarak antara tombol */
        }
        .search-input {
            width: 250px;
            /* margin-right: 10px; */
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-header .input-container {
            display: flex;
            align-items: center;
        }
    </style>

    <div class="container">
        <div class="card mt-4">
            <div class="card-header">
                <h3 class="card-title mb-0">User Table</h3>
                <div class="input-container d-flex justify-content-between w-100">
                    <input type="text" id="searchInput" class="form-control search-input" placeholder="Search by Name or Email">
                    <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal" style="margin-left: auto;">
                        Add User
                    </a>
                </div>
                
            </div>
          
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="userTable">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 25%;">Name</th>
                                <th style="width: 25%;">Email</th>
                                <th style="width: 25%;">Role</th>
                                <th style="width: 20%;">Tanggal</th>
                                <th style="width: 10%;">Action</th>
                                <th style="width: 15%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>{{ $user->created_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#editUserModal{{ $user->id }}">
                                            Edit
                                        </a>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger delete-btn"
                                            data-id="{{ $user->id }}">
                                            Hapus
                                        </button>

                                        <!-- Form Hapus userduk (Disembunyikan) -->
                                        <form id="delete-form-{{ $user->id }}"
                                            action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('users.create')

    @include('users.edit', ['users' => $users])

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

            const searchInput = document.getElementById('searchInput');
            const userTable = document.getElementById('userTable');
            const tableRows = userTable.getElementsByTagName('tr');

            searchInput.addEventListener('input', function() {
                const filter = searchInput.value.toLowerCase();

                for (let i = 1; i < tableRows.length; i++) {
                    const cells = tableRows[i].getElementsByTagName('td');
                    let isMatch = false;

                    // Cek apakah ada kecocokan pada kolom 'Name' (index 1) atau 'Email' (index 2)
                    if (cells[1].textContent.toLowerCase().includes(filter) || cells[2].textContent.toLowerCase().includes(filter)) {
                        isMatch = true;
                    }

                    // Menampilkan atau menyembunyikan baris berdasarkan kecocokan
                    tableRows[i].style.display = isMatch ? '' : 'none';
                }
            });
        });
    </script>

@endsection
