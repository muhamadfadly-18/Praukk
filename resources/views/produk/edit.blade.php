<div class="modal fade" id="editProdukModal{{ $pro->id }}" tabindex="-1" aria-labelledby="editProdukModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('produk.update', $pro->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return confirmUpdate(event, this)">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="name" class="form-control" value="{{ $pro->name }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Gambar</label>
                        <input type="file" name="gambar" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" id="hargaBeli{{ $pro->id }}" name="harga_beli" class="form-control" value="{{ number_format($pro->harga_beli, 0, ',', '.') }}" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Stock</label>
                        <input type="number" name="stock" class="form-control" value="{{ $pro->stock }}" disabled>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

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

    // Format input harga ke Rupiah saat user mengetik
    document.getElementById("hargaBeli{{ $pro->id }}").addEventListener("input", function (e) {
        let value = e.target.value.replace(/\D/g, ""); // Hanya angka
        e.target.value = formatRupiah(value);
    });

    function formatRupiah(angka) {
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
});
</script>
