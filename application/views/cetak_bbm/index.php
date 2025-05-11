<div class="container mt-4">
    <h4><i class="fas fa-gas-pump me-2"></i> Daftar SPBU</h4>

    <div class="table-responsive mt-3">
        <table class="table table-bordered text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Kode SPBU</th>
                    <th>Nama SPBU</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($spbu as $s): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $s['kode'] ?></td>
                    <td class="text-start"><?= $s['nama'] ?></td>
                    <td class="text-start"><?= $s['alamat'] ?></td>
                    <td>
                        <button class="btn btn-sm btn-primary btn-cetak-bbm" data-id="<?= $s['id'] ?>">
                            <i class="fas fa-print"></i> Cetak
                        </button>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).on("click", ".btn-cetak-bbm", function() {
    const id = $(this).data("id");
    const btn = $(this);
    const original = btn.html();

    btn.html('<i class="fas fa-spinner fa-spin"></i> Mencetak...').prop("disabled", true);

    $.post("<?= site_url('cetak_bbm/print_struk') ?>", {
        id: id
    }, function(res) {
        btn.html(original).prop("disabled", false);
        if (res.status === 'success') {
            Swal.fire('Berhasil', res.message, 'success');
        } else {
            Swal.fire('Gagal', res.message, 'error');
        }
    }, 'json').fail(function() {
        btn.html(original).prop("disabled", false);
        Swal.fire('Error', 'Terjadi kesalahan saat menghubungi printer.', 'error');
    });
});
</script>