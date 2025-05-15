<style>
th,
td {
    vertical-align: middle !important;
}

.table thead th {
    font-size: 12px;
    letter-spacing: 0.5px;
}

.table td {
    font-size: 13px;
}

.table td:first-child {
    font-weight: 500;
}
</style>

<h4 class="mb-4"><i class="fas fa-chart-bar text-primary"></i> <?= $title ?></h4>

<form method="get" class="form-inline mb-3 flex-wrap gap-2">
    <input type="date" name="tanggal_awal" value="<?= $tanggal_awal ?>" class="form-control mr-2">
    <input type="date" name="tanggal_akhir" value="<?= $tanggal_akhir ?>" class="form-control mr-2">

    <select name="divisi_id" class="form-control mr-2">
        <option value="">Semua Divisi</option>
        <?php foreach ($divisi as $d): ?>
        <option value="<?= $d['id'] ?>" <?= ($this->input->get('divisi_id') == $d['id']) ? 'selected' : '' ?>>
            <?= $d['nama_divisi'] ?></option>
        <?php endforeach; ?>
    </select>

    <select name="kategori_id" class="form-control mr-2">
        <option value="">Semua Kategori</option>
        <?php foreach ($kategori as $k): ?>
        <option value="<?= $k['id'] ?>" <?= ($this->input->get('kategori_id') == $k['id']) ? 'selected' : '' ?>>
            <?= $k['nama_kategori'] ?></option>
        <?php endforeach; ?>
    </select>

    <input type="text" name="search" class="form-control mr-2" placeholder="Cari produk..."
        value="<?= $this->input->get('search') ?>">

    <select name="limit" class="form-control mr-2">
        <?php foreach ([10, 30, 50, 100, 9999] as $opt): ?>
        <option value="<?= $opt ?>" <?= ($limit == $opt) ? 'selected' : '' ?>>
            <?= $opt == 9999 ? 'Semua' : $opt ?>
        </option>
        <?php endforeach; ?>
    </select>

    <button class="btn btn-primary">Tampilkan</button>
</form>


<!-- ✅ KARTU RINGKASAN -->
<div class="row mb-3">
    <div class="col-md-6">
        <div class="alert alert-info mb-2">
            <i class="fas fa-cash-register"></i> <strong>Total Penjualan:</strong><br>
            <span class="h5">Rp <?= number_format($ringkasan['total_penjualan'] ?? 0, 0, ',', '.') ?></span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="alert alert-success mb-2">
            <i class="fas fa-boxes"></i> <strong>Total Produk Terjual:</strong><br>
            <span class="h5"><?= $ringkasan['total_jumlah'] ?? 0 ?> Produk</span>
        </div>
    </div>
</div>


<div class="table-responsive">
    <table class="table table-hover table-striped table-bordered align-middle">
        <thead class="table-primary text-center text-uppercase small">
            <tr>
                <th>Produk</th>
                <th>SKU</th>
                <th>Kategori</th>
                <th>Divisi</th>
                <th>Jumlah</th>
                <th>Total Penjualan</th>
            </tr>
        </thead>
        <tbody class="small">
            <?php if (empty($produk)): ?>
            <tr>
                <td colspan="6" class="text-center text-muted py-3">Tidak ada data.</td>
            </tr>
            <?php else: ?>
            <?php foreach ($produk as $p): ?>
            <tr>
                <td class="font-weight-bold"><?= $p['nama_produk'] ?></td>
                <td><?= $p['sku'] ?></td>
                <td><?= $p['nama_kategori'] ?></td>
                <td><?= $p['nama_divisi'] ?></td>
                <td class="text-center"><?= $p['total_jumlah'] ?></td>
                <td class="text-right text-success font-weight-bold">
                    Rp <?= number_format($p['total_penjualan'], 0, ',', '.') ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- ✅ PAGINATION -->
<?php
$total_page = ceil($total / $limit);
if ($total_page > 1): ?>
<nav>
    <ul class="pagination">
        <?php for ($i = 1; $i <= $total_page; $i++): ?>
        <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
            <a class="page-link"
                href="<?= base_url("laporan/laporan_produk?tanggal_awal=$tanggal_awal&tanggal_akhir=$tanggal_akhir&kategori_id=$kategori_id&divisi_id=$divisi_id&search=$search&limit=$limit&page=$i") ?>">
                <?= $i ?>
            </a>
        </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>

<script>
$(document).ready(function() {
    // Auto search produk saat user mengetik
    $('input[name="search"]').on('input', function() {
        clearTimeout(window.delaySearch);
        window.delaySearch = setTimeout(() => {
            $('form').submit(); // submit form filter otomatis
        }, 500);
    });

    // Submit form saat limit berubah
    $('select[name="limit"]').on('change', function() {
        $('form').submit();
    });
});
</script>