<form method="get" class="form-inline mb-3">
    <label class="mr-2">Filter Tanggal:</label>
    <input type="date" name="tanggal_awal" value="<?= date('Y-m-d') ?>" class="form-control form-control-sm mr-2">
    <input type="date" name="tanggal_akhir" value="<?= date('Y-m-d') ?>" class="form-control form-control-sm mr-2">
    <button type="submit" class="btn btn-sm btn-primary">Tampilkan</button>
</form>

<table class="table table-bordered table-hover table-sm" id="tabelPending">
    <thead style="background-color: maroon; color: white; text-align: center;">
        <tr>
            <th><b>No</b></th>
            <th><b>No. Transaksi</b></th>
            <th><b>Customer</b></th>
            <th><b>Total Bayar</b></th>
            <th><b>Aksi</b></th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($pending)) : ?>
        <?php $no = 1; foreach ($pending as $row) : ?>
        <tr>
            <td class="text-center"><?= $no++ ?></td>
            <td><strong><?= $row['no_transaksi'] ?></strong></td>
            <td><?= $row['customer'] ?: '-' ?></td>
            <td class="text-right">Rp <?= number_format($row['total_pembayaran'], 0, ',', '.') ?></td>
            <td class="text-center">
                <a href="<?= site_url('kasir/detail/' . $row['id']) ?>" class="btn btn-sm btn-info"><i
                        class="fas fa-eye"></i> Detail</a>
                <a href="<?= site_url('kasir/ubah_transaksi/' . $row['id']) ?>" class="btn btn-sm btn-warning"><i
                        class="fas fa-edit"></i> Ubah</a>
                <a href="<?= site_url('kasir/void_semua/' . $row['id']) ?>" class="btn btn-sm btn-danger"
                    onclick="return confirm('Yakin ingin void seluruh transaksi ini?')"><i class="fas fa-times"></i>
                    Void</a>
                <a href="<?= site_url('kasir/bayar/' . $row['id']) ?>" class="btn btn-sm btn-success"><i
                        class="fas fa-money-bill"></i> Bayar</a>
            </td>
        </tr>
        <?php endforeach ?>
        <?php else : ?>
        <tr>
            <td colspan="5" class="text-center">Tidak ada transaksi pending.</td>
        </tr>
        <?php endif ?>
    </tbody>
</table>
<!-- Include ini sekali di bawah halaman -->
<script>
$(document).ready(function() {
    $('#tabelPending').DataTable({
        pageLength: 10,
        lengthMenu: [
            [10, 30, 50, 100, -1],
            [10, 30, 50, 100, "Semua"]
        ],
        responsive: true,
        ordering: false,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data tersedia",
            infoFiltered: "(difilter dari _MAX_ total data)",
            paginate: {
                previous: "Sebelumnya",
                next: "Berikutnya"
            }
        }
    });
});
</script>