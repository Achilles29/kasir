<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="fas fa-star text-warning"></i> Daftar Promo Stamp</h4>
        <a href="<?= site_url('stamp/form') ?>" class="btn btn-primary">Tambah Promo</a>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="table-dark text-center">
            <tr>
                <th>Promo</th>
                <th>Deskripsi</th>
                <th>Minimal</th>
                <th>Kelipatan</th>
                <th>Produk</th>
                <th>Target</th>
                <th>Hadiah</th>
                <th>Masa Berlaku</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($promo as $p): ?>
            <tr>
                <td><strong><?= $p['nama_promo'] ?></strong></td>
                <td><?= nl2br($p['deskripsi']) ?></td>
                <td class="text-center">Rp <?= number_format($p['minimal_pembelian'], 0, ',', '.') ?></td>
                <td class="text-center">
                    <span class="badge <?= $p['berlaku_kelipatan'] ? 'bg-success' : 'bg-secondary' ?>">
                        <?= $p['berlaku_kelipatan'] ? 'Ya' : 'Tidak' ?>
                    </span>
                </td>
                <td>
                    <?php
                        $produk_id = $p['produk_berlaku'];

                        if (!empty($produk_id) && is_numeric($produk_id)) {
                            $produk = $this->db->get_where('pr_produk', ['id' => $produk_id])->row_array();
                            echo $produk ? $produk['nama_produk'] : '<em class="text-muted">Produk tidak ditemukan</em>';
                        } else {
                            echo '<em>Semua produk</em>';
                        }
                    ?>
                </td>
                <td class="text-center"><?= $p['total_stamp_target'] ?> stamp</td>
                <td><?= nl2br($p['hadiah']) ?></td>
                <td class="text-center"><?= $p['masa_berlaku_hari'] ?> hari</td>
                <td class="text-center">
                    <span class="badge <?= $p['aktif'] ? 'bg-success' : 'bg-secondary' ?>">
                        <?= $p['aktif'] ? 'Aktif' : 'Nonaktif' ?>
                    </span>
                </td>
                <td class="text-center">
                    <a href="<?= site_url('stamp/form/'.$p['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="<?= site_url('stamp/delete/'.$p['id']) ?>" class="btn btn-sm btn-danger"
                        onclick="return confirm('Yakin ingin menghapus promo ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>