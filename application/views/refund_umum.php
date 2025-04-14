<div class="container-fluid">
    <h2>Refund Management</h2>


    <!-- Tabel Refund -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr class="text-center">
                    <th>No</th>
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th>Nilai</th>
                    <th>Rekening</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($refunds as $refund): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= $refund['kode'] ?></td>
                        <td><?= $refund['tanggal'] ?></td>
                        <td class="text-right">Rp <?= number_format($refund['nilai'], 2) ?></td>
                        <td><?= $refund['rekening_name'] ?></td>
                        <td><?= $refund['keterangan'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
