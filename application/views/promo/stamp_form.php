<!-- jQuery HARUS DULUAN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- jQuery UI Autocomplete -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<style>
.select2-container .select2-selection--single {
    height: 38px;
    padding: 4px;
    font-size: 14px;
}
</style>

<div class="container mt-4">
    <h4><?= $title ?></h4>
    <form method="POST" action="<?= site_url('stamp/save') ?>">
        <input type="hidden" name="id" value="<?= $promo['id'] ?? '' ?>">

        <div class="mb-3">
            <label>Nama Promo</label>
            <input type="text" class="form-control" name="nama_promo" value="<?= $promo['nama_promo'] ?? '' ?>"
                required>
        </div>
        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea class="form-control" name="deskripsi"><?= $promo['deskripsi'] ?? '' ?></textarea>
        </div>
        <div class="mb-3">
            <label>Minimal Pembelian (Rp)</label>
            <input type="number" class="form-control" name="minimal_pembelian"
                value="<?= $promo['minimal_pembelian'] ?? 0 ?>">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" name="berlaku_kelipatan" value="1"
                <?= isset($promo) && $promo['berlaku_kelipatan'] ? 'checked' : '' ?>>
            <label class="form-check-label">Berlaku Kelipatan</label>
        </div>
        <div class="mb-3 position-relative">
            <label for="search_produk">Pilih Produk Berlaku (Opsional)</label>
            <input type="text" id="search_produk" class="form-control" placeholder="🔍 Ketik nama produk...">
            <input type="hidden" name="produk_berlaku" id="produk_berlaku">
            <ul id="produk-list" class="list-group position-absolute w-100"
                style="z-index: 9999; display: none; max-height: 200px; overflow-y: auto;"></ul>
        </div>
        <div class="mb-3">
            <label>Total Target Stamp</label>
            <input type="number" class="form-control" name="total_stamp_target"
                value="<?= $promo['total_stamp_target'] ?? 0 ?>">
        </div>
        <div class="mb-3">
            <label>Hadiah</label>
            <textarea class="form-control" name="hadiah"><?= $promo['hadiah'] ?? '' ?></textarea>
        </div>
        <div class="mb-3">
            <label>Masa Berlaku Stamp (dalam hari)</label>
            <input type="number" class="form-control" name="masa_berlaku_hari"
                value="<?= $promo['masa_berlaku_hari'] ?? 30 ?>">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" name="aktif" value="1"
                <?= isset($promo) && $promo['aktif'] ? 'checked' : '' ?>>
            <label class="form-check-label">Aktif</label>
        </div>
        <button class="btn btn-primary">Simpan</button>
        <a href="<?= site_url('stamp') ?>" class="btn btn-secondary">Kembali</a>
    </form>
</div>


<script>
$(document).ready(function() {
    $("#search_produk").on("keyup", function() {
        let keyword = $(this).val().trim();

        if (keyword.length > 1) {
            $.ajax({
                url: "<?= site_url('produk/ajax_search'); ?>",
                type: "GET",
                dataType: "json",
                data: {
                    q: keyword
                },
                success: function(data) {
                    let produkHtml = "";

                    if (data.length > 0) {
                        data.forEach(function(item) {
                            produkHtml += `
                                <li class="list-group-item list-group-item-action select-produk"
                                    data-id="${item.id}"
                                    data-nama="${item.nama_produk}">
                                    ${item.nama_produk}
                                </li>`;
                        });
                    } else {
                        produkHtml =
                            `<li class="list-group-item text-muted">Produk tidak ditemukan</li>`;
                    }

                    $("#produk-list").html(produkHtml).show();
                }
            });
        } else {
            $("#produk-list").hide();
        }
    });

    // Pilih Produk dari List
    $(document).on("click", ".select-produk", function() {
        const nama = $(this).data("nama");
        const id = $(this).data("id");

        $("#search_produk").val(nama);
        $("#produk_berlaku").val(id);
        $("#produk-list").hide();
    });

    // Sembunyikan saat klik luar
    $(document).on("click", function(e) {
        if (!$(e.target).closest("#search_produk, #produk-list").length) {
            $("#produk-list").hide();
        }
    });

    <?php if (!empty($promo['produk_berlaku'])): ?>
    <?php
        $id_produk = $promo['produk_berlaku'];
        if (is_array($id_produk)) {
            $id_produk = reset($id_produk); // jika sempat disimpan dalam bentuk array
        }
    ?>
    $.ajax({
        url: "<?= site_url('produk/get_by_id_stamp/' . $id_produk) ?>",
        type: "GET",
        success: function(data) {
            try {
                const item = JSON.parse(data);
                $("#search_produk").val(item.nama_produk);
                $("#produk_berlaku").val(item.id);
            } catch (err) {
                console.warn("Gagal parsing produk:", err);
            }
        }
    });
    <?php endif; ?>

});
</script>