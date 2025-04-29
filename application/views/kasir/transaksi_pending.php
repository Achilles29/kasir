<div class="container-fluid">
    <h3 class="mb-4"><?= $title ?></h3>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi Belum Dibayar</h6>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover table-sm" id="tabelPending">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>No. Transaksi</th>
                        <th>Customer</th>
                        <th>Total Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pending)) : ?>
                    <?php $no = 1;
                        foreach ($pending as $row) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><strong><?= $row['no_transaksi'] ?></strong></td>
                        <td><?= $row['customer'] ?: '-' ?></td>
                        <td>Rp <?= number_format($row['total_pembayaran'], 0, ',', '.') ?></td>
                        <td>
                            <a href="<?= site_url('kasir/detail/' . $row['id']) ?>" class="btn btn-sm btn-info"><i
                                    class="fas fa-eye"></i> Detail</a>
                            <a href="<?= site_url('kasir/ubah_transaksi/' . $row['id']) ?>"
                                class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Ubah</a>
                            <a href="<?= site_url('kasir/void_semua/' . $row['id']) ?>" class="btn btn-sm btn-danger"
                                onclick="return confirm('Yakin ingin void seluruh transaksi ini?')"><i
                                    class="fas fa-times"></i> Void</a>
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
        </div>
    </div>
</div>