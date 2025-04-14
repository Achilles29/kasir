<style>
.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}
th {
    text-align: center;
    font-weight: bold;
}
td.nominal {
    text-align: right;

.nominal {
    text-align: right;
}
.red {
    background-color: #ffcccc; /* Merah muda untuk stok akhir 0 */
}
.yellow {
    background-color: #ffff99; /* Kuning untuk stok akhir 1 */
}
}
</style>
<div class="container-fluid">
    <h2><?= $title ?></h2>

    <!-- Filters for Month and Year -->
    <form method="get" action="<?= base_url('gudang/index_v2') ?>" class="mb-3">
        <div class="form-row">
            <div class="col-md-2">
                <label for="month">Bulan</label>
                <select name="month" id="month" class="form-control">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= $i ?>" <?= $i == $month ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $i, 10)) ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label for="year">Tahun</label>
                <select name="year" id="year" class="form-control">
                    <?php for ($i = 2020; $i <= date('Y'); $i++): ?>
                        <option value="<?= $i ?>" <?= $i == $year ? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="col-md-2">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary btn-block">Filter</button>
            </div>
        </div>
    </form>
    <!-- Sorting Options -->
    <form method="get" action="<?= base_url('gudang/index_v2') ?>" class="form-inline mb-3">
        <label for="sort_1" class="mr-2">Sortir 1:</label>
        <select name="sort_1" id="sort_1" class="form-control mr-3">
            <option value="kategori" <?= $sort_1 == 'kategori' ? 'selected' : '' ?>>Kategori</option>
            <option value="nama_barang" <?= $sort_1 == 'nama_barang' ? 'selected' : '' ?>>Nama Barang</option>
            <option value="nama_bahan_baku" <?= $sort_1 == 'nama_bahan_baku' ? 'selected' : '' ?>>Nama Bahan Baku</option>
            <option value="tipe" <?= $sort_1 == 'tipe' ? 'selected' : '' ?>>Tipe</option>
        </select>

        <label for="sort_2" class="mr-2">Sortir 2:</label>
        <select name="sort_2" id="sort_2" class="form-control mr-3">
            <option value="kategori" <?= $sort_2 == 'kategori' ? 'selected' : '' ?>>Kategori</option>
            <option value="nama_barang" <?= $sort_2 == 'nama_barang' ? 'selected' : '' ?>>Nama Barang</option>
            <option value="nama_bahan_baku" <?= $sort_2 == 'nama_bahan_baku' ? 'selected' : '' ?>>Nama Bahan Baku</option>
            <option value="tipe" <?= $sort_2 == 'tipe' ? 'selected' : '' ?>>Tipe</option>
        </select>

        <label for="sort_3" class="mr-2">Sortir 3:</label>
        <select name="sort_3" id="sort_3" class="form-control mr-3">
            <option value="kategori" <?= $sort_3 == 'kategori' ? 'selected' : '' ?>>Kategori</option>
            <option value="nama_barang" <?= $sort_3 == 'nama_barang' ? 'selected' : '' ?>>Nama Barang</option>
            <option value="nama_bahan_baku" <?= $sort_3 == 'nama_bahan_baku' ? 'selected' : '' ?>>Nama Bahan Baku</option>
            <option value="tipe" <?= $sort_3 == 'tipe' ? 'selected' : '' ?>>Tipe</option>
        </select>

        <label for="sort_4" class="mr-2">Sortir 4:</label>
        <select name="sort_4" id="sort_4" class="form-control mr-3">
            <option value="kategori" <?= $sort_4 == 'kategori' ? 'selected' : '' ?>>Kategori</option>
            <option value="nama_barang" <?= $sort_4 == 'nama_barang' ? 'selected' : '' ?>>Nama Barang</option>
            <option value="nama_bahan_baku" <?= $sort_4 == 'nama_bahan_baku' ? 'selected' : '' ?>>Nama Bahan Baku</option>
            <option value="tipe" <?= $sort_4 == 'tipe' ? 'selected' : '' ?>>Tipe</option>
        </select>

        <button type="submit" class="btn btn-primary">Filter & Sortir</button>

        <!-- Pagination Limit -->
        <label for="limit" class="mr-2">Baris per Halaman:</label>
        <select id="limit" name="limit" class="form-control" onchange="this.form.submit()">
            <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>20</option>
            <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
            <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
            <option value="all" <?= $limit == 'all' ? 'selected' : '' ?>>Semua</option>
        </select>
    </form>

    <!-- Search Form -->
    <form id="searchForm" method="get" class="mb-3">
        <div class="form-row">
            <div class="col-md-6">
                <input type="text" id="searchQuery" name="searchQuery" class="form-control" placeholder="Cari berdasarkan nama barang, nama bahan baku, merk, dll...">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-block">Cari</button>
            </div>
        </div>
    </form>
    <!-- Table showing filtered and grouped data -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Nama Barang</th>
                    <th>Nama Bahan Baku</th>
                    <th>Tipe</th>
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
                <?php foreach ($gudang as $item): ?>
                <tr 
                    style="background-color: <?= ($item['stok_akhir'] == 0) ? 'red' : (($item['stok_akhir'] == 1) ? 'yellow' : 'transparent') ?>;">
                    <td><?= $no++ ?></td>
                    <td><?= $item['kategori'] ?></td>
                    <td><?= $item['nama_barang'] ?></td>
                    <td><?= $item['nama_bahan_baku'] ?></td>
                    <td><?= $item['tipe'] ?></td>
                    <td class="nominal"><?= $item['stok_awal'] ?></td>
                    <td class="nominal"><?= $item['stok_masuk'] ?></td>
                    <td class="nominal"><?= $item['stok_keluar'] ?></td>
                    <td class="nominal"><?= $item['stok_terbuang'] ?></td>
                    <td class="nominal"><?= $item['stok_penyesuaian'] ?></td>
                    <td class="nominal"><?= $item['stok_akhir'] ?></td>
                    <td class="nominal"><?= number_format($item['unit_total'], 2) ?></td>
                    <td class="nominal"><?= number_format($item['nilai_total'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="12" class="text-center">Total Nilai Total</th>
                    <th class="nominal"><?= number_format($total_nilai_total ?? 0, 2) ?></th>
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
$(document).ready(function() {
    // Search functionality
    $('#searchQuery').on('keyup', function() {
        var query = $(this).val();
        var month = $('#month').val();
        var year = $('#year').val();

        $.ajax({
            url: '<?= base_url("gudang/searchv2") ?>',
            type: 'GET',
            data: {
                searchQuery: query,
                month: month,
                year: year
            },
            dataType: 'html',
            success: function(data) {
                $('#gudangTableBody').html(data);
            },
            error: function(xhr, status, error) {
                console.error('Error: ' + error);
            }
        });
    });
});

</script>