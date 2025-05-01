<div class="container-fluid">
    <h3 class="mb-4 text-center"><?= $title ?>: <?= $voids[0]->kode_void ?? '' ?></h3>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($voids['items'] as $v): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $v->nama_produk ?></td>
                        <td><?= $v->total_jumlah ?></td>
                        <td>Rp <?= number_format($v->harga, 0, ',', '.') ?></td>
                        <td>Rp <?= number_format($v->total_subtotal, 0, ',', '.') ?></td>
                        <td><?= $v->catatan ?></td>
                    </tr>

                    <?php if (!empty($voids['extras'][$v->detail_unit_id])): ?>
                    <?php foreach ($voids['extras'][$v->detail_unit_id] as $x): ?>
                    <tr>
                        <td></td>
                        <td class="text-muted">↳ <?= $x->nama_extra ?> <small class="text-danger">(Extra)</small></td>
                        <td><?= $x->jumlah ?></td>
                        <td>Rp <?= number_format($x->harga, 0, ',', '.') ?></td>
                        <td>Rp <?= number_format($x->subtotal, 0, ',', '.') ?></td>
                        <td class="text-muted">Extra</td>
                    </tr>
                    <?php endforeach ?>
                    <?php endif ?>
                    <?php endforeach ?>

                </tbody>
            </table>
            <div class="mt-3">
                <strong>Void oleh:</strong> <?= $voids['meta']->nama_pegawai ?? '-' ?><br>
                <strong>Alasan:</strong> <?= strtoupper($voids['meta']->alasan) ?><br>
                <strong>Tanggal Void:</strong> <?= date('d/m/Y H:i', strtotime($voids['meta']->created_at)) ?>
            </div>
        </div>
    </div>
</div>