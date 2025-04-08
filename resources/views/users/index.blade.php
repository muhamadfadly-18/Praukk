@extends('layouts.app')

@section('title', 'User')

@section('content')
<style>
          .swal-confirm {
        margin-left: 10px !important; /* Menambah jarak antara tombol */
    }
</style>

    <div class="container">
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">User Table</h3>
                <a href="#" class="btn btn-sm btn-primary ml-auto" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    Add User
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="userTable">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 25%;">Name</th>
                                <th style="width: 25%;">Email</th>
                                <th style="width: 25%;">role</th>
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
                                        <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                            Edit
                                        </a>
                                        
                                    </td>
                                    <td>
                                <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $user->id }}">
                                    Hapus
                                </button>

                                <!-- Form Hapus userduk (Disembunyikan) -->
                                <form id="delete-form-{{ $user->id }}" action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-none">
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
        document.addEventListener("DOMContentLoaded", function () {
            // Event delegation agar event tetap berfungsi setelah DOM berubah
            document.body.addEventListener("click", function (event) {
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
        });
        
    </script>
   
@endsection

