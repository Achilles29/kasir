<div class="container mt-4">
    <h4 class="mb-4"><i class="fas fa-stamp text-secondary"></i> Riwayat Stamp: <?= $customer['nama'] ?></h4>

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>Promo</th>
                    <th>Jumlah Stamp</th>
                    <th>Target</th>
                    <th>Hadiah</th>
                    <th>Masa Berlaku</th>
                    <th>Status</th>
                    <th>Diperoleh</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stamp as $s): ?>
                <tr>
                    <td class="text-start"><?= $s->nama_promo ?></td>
                    <td><?= $s->jumlah_stamp ?></td>
                    <td><?= $s->total_stamp_target ?></td>
                    <td><?= $s->hadiah ?></td>
                    <td><?= date('d/m/Y', strtotime($s->masa_berlaku)) ?></td>
                    <td>
                        <?php if ($s->status == 'aktif'): ?>
                        <span class="badge bg-success">Aktif</span>
                        <?php elseif ($s->status == 'kadaluarsa'): ?>
                        <span class="badge bg-danger">Kadaluarsa</span>
                        <?php else: ?>
                        <span class="badge bg-warning">Ditukar</span>
                        <?php endif; ?>
                    </td>
                    <td><?= date('d/m/Y H:i', strtotime($s->last_stamp_at)) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>