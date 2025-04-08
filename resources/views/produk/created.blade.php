<!-- Modal Tambah Produk -->
<div class="modal fade" id="addProdukModal" tabindex="-1" aria-labelledby="addProdukModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addProdukForm" action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar</label>
                        <input type="file" name="gambar" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" id="hargaBeli" name="harga_beli" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stock</label>
                        <input type="text" id="stock" name="stock" class="form-control" required>
                    </div>                    
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("addProdukForm").addEventListener("submit", function (e) {
        e.preventDefault(); // Mencegah submit form langsung

        Swal.fire({
            title: "Apakah Anda yakin ingin menyimpan?",
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Simpan",
            denyButtonText: `Jangan Simpan`
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire("Tersimpan!", "", "success").then(() => {
                    e.target.submit(); // Submit form setelah user mengonfirmasi
                });
            } else if (result.isDenied) {
                Swal.fire("Perubahan tidak disimpan", "", "info");
            }
        });
    });

    // Format input harga ke Rupiah
    document.getElementById("hargaBeli").addEventListener("input", function (e) {
        let value = e.target.value.replace(/\D/g, ""); // Hanya angka
        e.target.value = formatRupiah(value);
    });

    function formatRupiah(angka) {
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
});
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("stock").addEventListener("input", function (e) {
        let value = e.target.value.replace(/\D/g, ""); // Hanya angka
        e.target.value = formatRupiah(value);
    });

    function formatRupiah(angka) {
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
});

</script>
