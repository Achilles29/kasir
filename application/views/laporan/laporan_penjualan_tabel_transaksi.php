<?php $this->load->view('laporan/laporan_penjualan_ringkasan', ['transaksi_ringkasan' => $transaksi_ringkasan]); ?>


<!-- TABEL TRANSAKSI -->
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light text-center">
            <tr>
                <th>NO TRANSAKSI</th>
                <th>WAKTU ORDER</th>
                <th>WAKTU BAYAR</th>
                <th>JENIS ORDER</th>
                <th>TOTAL PENJUALAN</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transaksi as $t): ?>
            <tr class="text-center">
                <td><?= $t['no_transaksi'] ?></td>
                <td><?= $t['waktu_order'] ?: '-' ?></td>
                <td><?= $t['waktu_bayar'] ?: '-' ?></td>
                <td><?= $t['jenis_order'] ?></td>
                <td>Rp <?= number_format($t['total_penjualan'], 0, ',', '.') ?></td>
                <td>
                    <a href="<?= base_url('laporan/laporan_penjualan_detail/' . $t['id']) ?>"
                        class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye"></i> Detail
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($transaksi)): ?>
            <tr>
                <td colspan="7" class="text-center text-muted py-4">Tidak ada data transaksi.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- PAGINATION -->
<?php
$total_pages = ceil(($total_data ?? 1) / ($per_page ?? 10));
?>

<div class="d-flex justify-content-center my-3">
    <div class="pagination d-flex gap-1 flex-wrap">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <button class="btn btn-sm <?= ($page == $i ? 'btn-primary' : 'btn-outline-secondary') ?> page-btn"
            data-page="<?= $i ?>">
            <?= $i ?>
        </button>
        <?php endfor; ?>
    </div>
</div>