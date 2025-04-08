@foreach ($users as $user)
<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('user.update', $user->id) }}" method="POST" onsubmit="return confirmUpdate(event, this)">
                    @csrf
                    @method('PUT')
                    {{-- {{$user->id}} --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" class="form-control">
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }}>Kasir</option>
                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password (Opsional)</label>
                        <input type="password" name="password" class="form-control">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endforeach
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmUpdate(event, form) {
    event.preventDefault(); // Mencegah submit langsung

    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Produk akan diperbarui!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Update!",
        cancelButtonText: "Batal",
        reverseButtons: true,
        customClass: {
            confirmButton: "btn btn-primary swal-confirm",
            cancelButton: "btn btn-secondary swal-cancel"
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            setTimeout(() => form.submit(), 100); // Tambahkan sedikit delay sebelum submit
        }
    });

    return false;
}

// Cek jika ada session success setelah update
document.addEventListener("DOMContentLoaded", function () {
    @if(session('success'))
        Swal.fire({
            title: "Berhasil!",
            text: "{{ session('success') }}",
            icon: "success",
            confirmButtonText: "OK"
        });
    @endif

    @if(session('error'))
        Swal.fire({
            title: "Gagal!",
            text: "{{ session('error') }}",
            icon: "error",
            confirmButtonText: "OK"
        });
    @endif
});