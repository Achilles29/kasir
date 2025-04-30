<style>
.table td {
    vertical-align: middle;
}

.table td.text-center,
.table th.text-center {
    text-align: center;
}

.table td.text-right {
    text-align: right;
}

.btn i {
    margin-right: 4px;
}

.badge-status {
    font-size: 12px;
    padding: 5px 10px;
}

.filter-bar {
    background-color: maroon;
    color: white;
    padding: 10px 15px;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
}

.filter-bar label {
    margin-right: 10px;
    font-weight: 500;
}

.filter-bar .form-control-sm {
    max-width: 140px;
}

.badge-kosong {
    background-color: #ffc107;
    color: #000;
}

.table tbody tr:hover {
    background-color: #f2f2f2;
}

.highlight {
    animation: highlightAnim 0.5s ease-in-out;
}

@keyframes highlightAnim {
    0% {
        background-color: #ffefc5;
    }

    100% {
        background-color: inherit;
    }
}

.table thead th {
    vertical-align: middle;
    text-align: center !important;
    font-weight: bold;
    background-color: maroon;
    color: white;
}
</style>

<div class="container-fluid">
    <h3 class="mb-4 text-center"><?= $title ?></h3>

    <div class="card shadow">
        <div class="filter-bar">
            <form method="get" class="form-inline">
                <label class="mr-2">Filter Tanggal:</label>
                <input type="date" name="tanggal_awal" value="<?= $tanggal_awal ?>"
                    class="form-control form-control-sm mr-2">
                <input type="date" name="tanggal_akhir" value="<?= $tanggal_akhir ?>"
                    class="form-control form-control-sm mr-2">
                <button type="submit" class="btn btn-sm btn-light text-dark"><i class="fas fa-filter"></i>
                    Tampilkan</button>
            </form>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover table-sm" id="tabelPending"
                <?= empty($pending) ? 'data-empty="true"' : '' ?>>
                <thead>
                    <tr class="text-center">
                        <th>No</th>
                        <th>No. Transaksi</th>
                        <th>Tanggal</th>
                        <th>Customer</th>
                        <th>Meja</th>
                        <th>Total Belum Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pending)) : ?>
                    <?php $no = 1; foreach ($pending as $row) : ?>
                    <tr class="highlight">
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="text-center"><strong><?= $row['no_transaksi'] ?></strong></td>
                        <td class="text-center">
                            <?= !empty($row['tanggal']) ? date('d/m/Y', strtotime($row['tanggal'])) : '-' ?></td>
                        <td><?= $row['customer'] ?: '<span class="badge badge-kosong">Tidak Ada</span>' ?></td>
                        <td class="text-center"><?= $row['nomor_meja'] ?: '-' ?></td>
                        <td class="text-right">Rp <?= number_format($row['sisa_pembayaran'] ?? 0, 0, ',', '.') ?></td>
                        <td class="text-center">
                            <a href="<?= site_url('kasir/detail/' . $row['id']) ?>" class="btn btn-sm btn-info mb-1"><i
                                    class="fas fa-eye"></i> Detail</a>
                            <a href="<?= site_url('kasir/ubah_transaksi/' . $row['id']) ?>"
                                class="btn btn-sm btn-warning mb-1"><i class="fas fa-edit"></i> Ubah</a>
                            <a href="<?= site_url('kasir/void_semua/' . $row['id']) ?>"
                                class="btn btn-sm btn-danger mb-1"
                                onclick="return confirm('Yakin ingin void seluruh transaksi ini?')"><i
                                    class="fas fa-times"></i> Void</a>
                            <a href="<?= site_url('kasir/bayar/' . $row['id']) ?>"
                                class="btn btn-sm btn-success mb-1"><i class="fas fa-money-bill-wave"></i> Bayar</a>
                        </td>
                    </tr>
                    <?php endforeach ?>
                    <?php else : ?>
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada transaksi pending.</td>
                    </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    const tablePending = $('#tabelPending');

    if (tablePending.attr('data-empty') !== 'true') {
        tablePending.DataTable({
            pageLength: 10,
            lengthMenu: [
                [10, 30, 50, 100, -1],
                [10, 30, 50, 100, "Semua"]
            ],
            responsive: true,
            ordering: false,
            language: {
                search: "🔍 Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data tersedia",
                infoFiltered: "(difilter dari _MAX_ total data)",
                paginate: {
                    previous: "⬅️ Sebelumnya",
                    next: "➡️ Berikutnya"
                }
            }
        });
    }
});
</script>