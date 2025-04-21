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
    text-align: right;  /* Right-align text for nominal values */
}

</style>
<?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
        <?= $this->session->flashdata('success') ?>
    </div>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
    <div class="alert alert-danger">
        <?= $this->session->flashdata('error') ?>
    </div>
<?php endif; ?>


<div class="container-fluid">
    <h2><?= $title ?></h2>
<div class="row mb-3">
    <div class="col-md-12">
        <!-- View: Form Pemilihan Tanggal -->
        <div class="col-md-3">
            <label for="tanggal_generate">Pilih Tanggal untuk Generate Stok Awal</label>
            <input type="date" id="tanggal_generate" name="tanggal_generate" class="form-control" value="<?= date('Y-m-d'); ?>">
        <br>
        <button class="btn btn-primary" id="generate-stok-awal" onclick="generateStokAwal()">Generate Stok Awal</button>
        <a href="<?= base_url('gudang/generate_stok_opname?month=' . date('m') . '&year=' . date('Y')) ?>" 
           class="btn btn-primary" 
           onclick="return confirm('Apakah Anda yakin ingin menggenerate stok opname untuk bulan ini?')">
            Generate Stok Opname
        </a>
        </div>

    </div>
</div>

<form method="get" action="<?= base_url('gudang') ?>" class="mb-3">
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

        <div class="col-md-2">
            <label>&nbsp;</label>
            <select name="limit" class="form-control" onchange="this.form.submit()">
                <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>20</option>
                <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
                <option value="all" <?= $limit == 'all' ? 'selected' : '' ?>>Semua</option>
            </select>
        </div>
    </div>
</form>

<div class="row mb-3">
    <div class="col-md-12">
        <form method="get" action="<?= base_url('gudang') ?>" class="form-inline">
            <!-- Sortir Data -->
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

        </form>
    </div>
</div>
   <!-- Form Pencarian -->
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
<div class="table-responsive">
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
               <th><a href="<?= base_url('gudang?sort_kategori=asc') ?>">Kategori</a></th>            
            <th>Nama Barang</th>
            <th>Nama Bahan Baku</th>
            <th>Tipe</th>
            <th>Merk</th>
            <th>Keterangan</th>
            <th>Ukuran</th>
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
    <?php 
    $no = $start + 1; // Mulai nomor berdasarkan halaman (offset)
    foreach ($gudang as $item): ?>
        <tr>
            <td><?= $no++ ?></td> <!-- Nomor urut -->
            <td><?= $item['kategori'] ?></td>
            <td><?= $item['nama_barang'] ?></td>
            <td><?= $item['nama_bahan_baku'] ?></td>
            <td><?= $item['tipe'] ?></td>
            <td><?= $item['merk'] ?></td>
            <td><?= $item['keterangan'] ?></td>
            <td><?= $item['ukuran'] ?></td>
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
    <div class="row">
        <div class="col-md-12">
            <?= $pagination ?>
        </div>
    </div>

</div>
<script>
function generateStokAwal() {
    const tanggalGenerate = document.getElementById('tanggal_generate').value; // Ambil tanggal dari input

    if (confirm('Apakah Anda yakin ingin generate stok awal untuk tanggal ' + tanggalGenerate + '?')) {
        fetch('<?= base_url('gudang/generate_stok_awal') ?>?tanggal_generate=' + tanggalGenerate, {
            method: 'POST',
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Stok awal berhasil di-generate.');
                location.reload();
            } else {
                alert('Gagal generate stok awal: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat generate stok awal.');
        });
    }
}

$(document).ready(function() {
    // Ketika pengguna mengetik di input pencarian
    $('#searchQuery').on('keyup', function() {
        var query = $(this).val();
        var month = $('#month').val();
        var year = $('#year').val();

        $.ajax({
            url: '<?= base_url("gudang/search") ?>',
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
