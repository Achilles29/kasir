<div class="container-fluid">
    <h2>Stock Opname</h2>

    <!-- Filters for Month and Year -->
    <form method="get" action="<?= base_url('stok_opname/index') ?>" class="mb-3">
        <div class="form-row">
            <div class="col-md-2">
                <label for="month">Bulan</label>
                <select name="month" id="month" class="form-control">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= $i ?>" <?= ($i == $month) ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $i, 10)) ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="col-md-2">
                <label for="year">Tahun</label>
                <select name="year" id="year" class="form-control">
                    <?php for ($i = 2020; $i <= date('Y'); $i++): ?>
                        <option value="<?= $i ?>" <?= ($i == $year) ? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="col-md-2">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary btn-block">Filter</button>
            </div>
        </div>
    </form>

    <!-- Pagination & Row Limit Selection -->
    <form method="get" action="<?= base_url('stok_opname/index') ?>" class="mb-3">
        <div class="form-row">
            <div class="col-md-2">
                <label for="limit">Baris per Halaman</label>
                <select id="limit" name="limit" class="form-control">
                    <option value="20" <?= ($limit == 20) ? 'selected' : '' ?>>20</option>
                    <option value="50" <?= ($limit == 50) ? 'selected' : '' ?>>50</option>
                    <option value="100" <?= ($limit == 100) ? 'selected' : '' ?>>100</option>
                    <option value="all" <?= ($limit == 'all') ? 'selected' : '' ?>>Semua</option>
                </select>
            </div>
        </div>
    </form>

    <!-- Search Form -->
    <form id="searchForm" method="get" class="mb-3">
        <div class="form-row">
            <div class="mb-3">
                <input type="text" id="search-bar" class="form-control" placeholder="Cari berdasarkan kategori, nama barang, merk, atau tipe..." />
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-block">Cari</button>
            </div>
        </div>
    </form>

    <!-- Stock Opname Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Nama Barang</th>
                    <th>Nama Bahan Baku</th>
                    <th>Tipe</th>
                    <th>Merk</th>
                    <th>Ukuran</th>
                    <th>Keterangan</th>
                    <th>Unit</th>
                    <th>Pack</th>
                    <th>Harga</th>
                    <th>Stok Awal</th>
                    <th>Stok Masuk</th>
                    <th>Stok Keluar</th>
                    <th>Stok Terbuang</th>
                    <th>Penyesuaian</th>
                    <th>Stok Akhir</th>
                    <th>Unit Total</th>
                    <th>Nilai Total</th>
                </tr>
            </thead>
            <tbody id="gudangTableBody">
                <?php $no = $start + 1; ?>
                <?php foreach ($stok_opname_data as $item): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $item['kategori'] ?></td>
                        <td><?= $item['nama_barang'] ?></td>
                        <td><?= $item['nama_bahan_baku'] ?></td>
                        <td><?= $item['tipe'] ?></td>
                        <td><?= $item['merk'] ?></td>
                        <td><?= $item['ukuran'] ?></td>
                        <td><?= $item['keterangan'] ?></td>
                        <td><?= $item['unit'] ?></td>
                        <td><?= $item['pack'] ?></td>
                        <td class="nominal"><?= number_format($item['harga'], 2) ?></td>
                        <td class="nominal"><?= $item['stok_awal'] ?? 0 ?></td>
                        <td class="nominal"><?= $item['stok_masuk'] ?? 0 ?></td>
                        <td class="nominal"><?= $item['stok_keluar'] ?? 0 ?></td>
                        <td class="nominal"><?= $item['stok_terbuang'] ?? 0 ?></td>
                        <td class="nominal"><?= $item['stok_penyesuaian'] ?? 0 ?></td>
                        <td class="nominal"><?= $item['stok_akhir'] ?? 0 ?></td>
                        <td class="nominal"><?= $item['stok_akhir'] * $item['ukuran'] ?></td>
                        <td class="nominal"><?= number_format($item['stok_akhir'] * $item['harga'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="18" class="text-center">Total Nilai Total</th>
                    <th class="nominal"><?= number_format($total_nilai_total, 2) ?></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Pagination links -->
    <div class="row">
        <div class="col-md-12">
            <?= $pagination ?>
        </div>
    </div>
</div>

<script>
document.getElementById('limit').addEventListener('change', function() {
    const selectedMonth = document.getElementById('month').value;
    const selectedYear = document.getElementById('year').value;
    
    // Simpan bulan dan tahun yang dipilih ke sessionStorage
    sessionStorage.setItem('selectedMonth', selectedMonth);
    sessionStorage.setItem('selectedYear', selectedYear);

    // Reload halaman dengan nilai filter yang tetap
    window.location.href = "<?= base_url('stok_opname/index') ?>?month=" + selectedMonth + "&year=" + selectedYear + "&limit=" + this.value;
});

// Saat halaman dimuat, kembalikan filter ke bulan & tahun sebelumnya jika ada
window.onload = function() {
    const savedMonth = sessionStorage.getItem('selectedMonth');
    const savedYear = sessionStorage.getItem('selectedYear');

    if (savedMonth && savedYear) {
        document.getElementById('month').value = savedMonth;
        document.getElementById('year').value = savedYear;
    }
};

// Pencarian menggunakan AJAX
document.getElementById('search-bar').addEventListener('input', function() {
    const keyword = this.value;
    const month = document.getElementById('month').value;
    const year = document.getElementById('year').value;
    const limit = document.getElementById('limit').value;

    fetch('<?= base_url('StokOpname/search') ?>?keyword=' + keyword + '&month=' + month + '&year=' + year + '&limit=' + limit)
    .then(res => res.json())
    .then(data => {
        const list = document.getElementById('gudangTableBody');
        list.innerHTML = '';
        let nomor = 1;
        data.forEach(item => {
            list.innerHTML += `


                <tr>
                    <td>${nomor++}</td>
                    <td>${item.kategori}</td>
                    <td>${item.nama_barang}</td>
                    <td>${item.nama_bahan_baku}</td>
                    <td>${item.tipe}</td>
                    <td>${item.merk}</td>
                    <td>${item.ukuran}</td>
                    <td>${item.keterangan}</td>
                    <td>${item.unit}</td>
                    <td>${item.pack}</td>
                    <td class="nominal">${item.harga}</td>
                    <td class="nominal">${item.stok_awal}</td>
                    <td class="nominal"><?= number_format($item['harga'], 2) ?></td>
                    <td class="nominal"><?= $item['stok_awal'] ?? 0 ?></td>
                    <td class="nominal"><?= $item['stok_masuk'] ?? 0 ?></td>
                    <td class="nominal"><?= $item['stok_keluar'] ?? 0 ?></td>
                    <td class="nominal"><?= $item['stok_terbuang'] ?? 0 ?></td>
                    <td class="nominal"><?= $item['stok_penyesuaian'] ?? 0 ?></td>
                    <td class="nominal"><?= $item['stok_akhir'] ?? 0 ?></td>
                    <td class="nominal"><?= $item['stok_akhir'] * $item['ukuran'] ?></td>
                    <td class="nominal"><?= number_format($item['stok_akhir'] * $item['harga'], 2) ?></td>

                    </tr>
            `;
        });
    });
});
</script>
