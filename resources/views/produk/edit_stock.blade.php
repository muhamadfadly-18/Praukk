<div class="modal fade" id="editStockModal{{ $pro->id }}" tabindex="-1" aria-labelledby="editStockModalLabel{{ $pro->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStockModalLabel{{ $pro->id }}">Edit Stok Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('produk.updateStock', $pro->id) }}" method="POST" onsubmit="return confirmUpdate(event, this)">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stok</label>
                        <input type="text" id="editStock{{ $pro->id }}" name="stock" class="form-control" value="{{ number_format($pro->stock, 0, ',', '.') }}" required>
                    </div>                    
                    <button type="submit" class="btn btn-primary">Simpan</button>
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
});

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("input[id^='editStock']").forEach(function (input) {
        input.addEventListener("input", function (e) {
            let value = e.target.value.replace(/\D/g, ""); // Hanya angka
            e.target.value = formatRupiah(value);
        });
    });

    function formatRupiah(angka) {
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
});

</script>

