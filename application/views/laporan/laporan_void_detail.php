<style>
.detail-void-wrapper {
    background-color: #fefefc;
    padding: 40px;
    border-radius: 12px;
    font-family: 'Segoe UI', sans-serif;
}

.detail-void-wrapper h3 {
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 10px;
}

.detail-void-wrapper .meta-info {
    background: #e8f4ff;
    border: 1px solid #cce1ff;
    border-left: 5px solid #3399ff;
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
}

.detail-void-wrapper .meta-info strong {
    color: #2c3e50;
    margin-right: 5px;
}

.detail-void-wrapper .table thead {
    background: #f0f0f0;
    color: #444;
    font-weight: 600;
    font-size: 14px;
    border-bottom: 2px solid #ddd;
}

.detail-void-wrapper .table td,
.detail-void-wrapper .table th {
    vertical-align: middle;
    font-size: 14px;
    padding: 10px;
}

.detail-void-wrapper .table tbody tr td:first-child {
    font-weight: 500;
}

.detail-void-wrapper .extra-row td {
    font-style: italic;
    color: #777;
}

.detail-void-wrapper .total-row td {
    font-weight: 700;
    font-size: 16px;
    background: #f7f7f7;
    color: #000;
}

.text-end {
    text-align: right !important;
}
</style>

<div class="container-fluid detail-void-wrapper">
    <h3 class="text-center mb-4">Detail Void: <?= $voids['meta']->kode_void ?? '' ?></h3>

    <div class="meta-info mb-4">
        <div class="row">
            <div class="col-md-6">
                <div><strong>No Void:</strong> <?= $voids['meta']->kode_void ?? '-' ?></div>
                <div><strong>No Transaksi:</strong> <?= $voids['meta']->no_transaksi ?? '-' ?></div>
            </div>
            <div class="col-md-6">
                <div><strong>Void oleh:</strong> <?= $voids['meta']->nama_pegawai ?? '-' ?></div>
                <div><strong>Alasan:</strong> <?= strtoupper($voids['meta']->alasan) ?></div>
                <div><strong>Tanggal Void:</strong> <?= date('d/m/Y H:i', strtotime($voids['meta']->created_at)) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered mb-0 text-center">
                <thead>
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
                    <tr class="extra-row">
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
                    <tr class="total-row">
                        <td colspan="4" class="text-end">Total Void</td>
                        <td colspan="2">Rp <?= number_format($total_void, 0, ',', '.') ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>