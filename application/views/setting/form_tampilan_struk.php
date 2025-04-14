<style>
.form-switch .form-check-input {
    width: 3em;
    height: 1.5em;
    background-color: #dee2e6;
    border-radius: 1.5em;
    transition: all 0.3s;
}

.form-switch .form-check-input:checked {
    background-color: #0dd39b;
}
</style>
<!-- Bootstrap 5.1+ already supports form-switch -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<div class="row">
    <!-- Kolom Form -->
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Tampilan Struk Lokasi: <strong><?= $printer['lokasi_printer'] ?></strong></h5>
                <form method="post" action="<?= base_url('setting/simpan_tampilan_struk') ?>" id="formTampilan">
                    <!-- Ubah nama field hidden ini -->
                    <input type="hidden" name="printer_id" value="<?= $printer['id'] ?>">

                    <div class="row">
                        <?php
            $fields = [
              'show_logo' => 'Logo',
              'show_outlet' => 'Outlet',
              'show_alamat' => 'Alamat',
              'show_no_telepon' => 'No Telepon',
              'show_custom_header' => 'Header',
              'show_no_transaksi' => 'No Transaksi',
              'show_kasir_order' => 'Kasir Order',
              'show_kasir_bayar' => 'Kasir Bayar',
              'show_customer' => 'Customer',
              'show_nomor_meja' => 'Nomor Meja',
              'show_waktu_order' => 'Waktu Order',
              'show_waktu_bayar' => 'Waktu Bayar',
              'show_custom_footer' => 'Footer'
            ];

            foreach ($fields as $key => $label): ?>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="<?= $key ?>" value="1"
                                    <?= isset($tampilan[$key]) && $tampilan[$key] ? 'checked' : '' ?>
                                    onchange="updatePreview()">
                                <label class="form-check-label"><?= $label ?></label>
                            </div>

                        </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="submit" class="btn btn-success mt-3">Simpan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Kolom Preview -->
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-body bg-light" id="previewArea">
                <div id="previewContent" style="min-height: 400px;">Memuat preview...</div>
            </div>
        </div>
    </div>
</div>

<script>
function updatePreview() {
    const formData = $("#formTampilan").serialize();
    $.ajax({
        url: "<?= base_url('setting/preview_ajax') ?>",
        type: "POST",
        data: formData,
        success: function(res) {
            $("#previewContent").html(res);
        },
        error: function() {
            $("#previewContent").html("<div class='alert alert-danger'>Gagal memuat preview</div>");
        }
    });
}


$(document).ready(function() {
    updatePreview(); // Load pertama
});
</script>