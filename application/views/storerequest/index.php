
<style>
    /* Pagination Styling */
.pagination {
    margin-top: 20px;
}

.pagination .page-item {
    display: inline-block;
    margin: 0 2px;
}

.pagination .page-item a, 
.pagination .page-item span {
    padding: 8px 15px;
    color: #007bff;
    text-decoration: none;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.pagination .page-item.active a,
.pagination .page-item.active span {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

.pagination .page-item:hover a,
.pagination .page-item:hover span {
    background-color: #f0f0f0;
}

</style>
<div class="container-fluid">
    <h2><?= $title ?></h2>

    <!-- Filter Bulan & Tahun -->
<form method="get" action="<?= base_url('storerequest') ?>" class="mb-3">
    <div class="form-row">
        <div class="col-md-3">
            <label for="bulan">Bulan</label>
            <select name="bulan" id="bulan" class="form-control">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>" <?= $i == $bulan ? 'selected' : '' ?>>
                        <?= date('F', mktime(0, 0, 0, $i, 10)) ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>

        <div class="col-md-3">
            <label for="tahun">Tahun</label>
            <select name="tahun" id="tahun" class="form-control">
                <?php foreach ($tahun_list as $t): ?>
                    <option value="<?= $t['tahun'] ?>" <?= $t['tahun'] == $tahun ? 'selected' : '' ?>>
                        <?= $t['tahun'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-2">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-primary btn-block">Filter</button>
        </div>
    </div>
</form>


    <!-- Form Input Store Request -->
    <form id="storeRequestForm" method="post" class="mb-3">
        <div class="form-row">
            <div class="col-md-3">
                <label>Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="col-md-3">
                <label for="search_barang">Cari Barang</label>
                <input type="text" id="search_barang" name="search_barang" class="form-control" placeholder="Cari barang berdasarkan nama..." autocomplete="off">
                <ul id="search_result" class="list-group"></ul>
            </div>
                <div class="col-md-3">
                    <label>Jenis Pengeluaran</label>
                    <select id="jenis_pengeluaran" name="jenis_pengeluaran" class="form-control" required>
                        <option value="2" selected>BAR</option>
                        <option value="3">KITCHEN</option>
                    </select>
                </div>

        </div>
        <input type="hidden" id="bl_db_purchase_id" name="bl_db_purchase_id">

        <div class="form-row mt-3">
            <div class="col-md-3">
                <label>Nama Barang</label>
                <input type="text" id="nama_barang" class="form-control" readonly>
            </div>
            <div class="col-md-3">
                <label>Merk</label>
                <input type="text" id="merk" class="form-control" readonly>
            </div>
            <div class="col-md-3">
                <label>Keterangan</label>
                <input type="text" id="keterangan" class="form-control" readonly>
            </div>
            <div class="col-md-3">
                <label>Ukuran</label>
                <input type="text" id="ukuran" class="form-control" readonly>
            </div>
        </div>

        <div class="form-row mt-3">
            <div class="col-md-2">
                <label>Unit</label>
                <input type="text" id="unit" class="form-control" readonly>
            </div>
            <div class="col-md-2">
                <label>Harga Satuan</label>
                <input type="text" id="harga" class="form-control" readonly>
            </div>
            <div class="col-md-2">
                <label>Sisa Stok</label>
                <input type="text" id="sisa_stok" class="form-control" readonly>
            </div>
            <div class="col-md-2">
                <label>Kuantitas</label>
                <input type="number" id="kuantitas" name="kuantitas" class="form-control" required>
            </div>

            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary btn-block">Simpan</button>
            </div>

        </div>

        <!-- <div class="form-row mt-3">
            <div class="col-md-3">
                <label>Kuantitas</label>
                <input type="number" id="kuantitas" name="kuantitas" class="form-control" required>
            </div>

            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary btn-block">Simpan</button>
            </div>
        </div> -->
    </form>

<!-- Form Filter Tanggal dan Jenis Pengeluaran -->
<form method="get" action="<?= base_url('storerequest') ?>" class="mb-3">
    <div class="form-row">
        <div class="col-md-3">
            <label for="tanggal_awal">Tanggal Awal</label>
            <input type="date" id="tanggal_awal" name="tanggal_awal" value="<?= $tanggal_awal ?>" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="tanggal_akhir">Tanggal Akhir</label>
            <input type="date" id="tanggal_akhir" name="tanggal_akhir" value="<?= $tanggal_akhir ?>" class="form-control">
        </div>
        <div class="col-md-2">
            <label for="jenis_pengeluaran">Jenis Pengeluaran</label>
            <select id="jenis_pengeluaran" name="jenis_pengeluaran" class="form-control">
                <option value="">Semua</option>
                <option value="2" <?= $jenis_pengeluaran == '2' ? 'selected' : '' ?>>BAR</option>
                <option value="3" <?= $jenis_pengeluaran == '3' ? 'selected' : '' ?>>KITCHEN</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="limit">Baris per Halaman</label>
            <select id="limit" name="limit" class="form-control" onchange="this.form.submit()">
                <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>20</option>
                <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
                <option value="all" <?= $limit == 'all' ? 'selected' : '' ?>>Semua</option>
            </select>
        </div>
        <div class="col-md-2 align-self-end">
            <button type="submit" class="btn btn-primary btn-block">Filter</button>
        </div>
    </div>
</form>


<!-- kolom pencarian -->
<div class="row mb-3">
    <div class="col-md-4">
        <label for="search_table">Cari Barang</label>
        <input type="text" id="search_table" class="form-control" placeholder="Cari nama barang, merk, keterangan...">
    </div>
</div>

    <!-- Tabel Store Request -->
<div class="table-responsive">
        <table class="table table-bordered table-striped">    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Jenis Pengeluaran</th>
            <th>Nama Barang</th>
            <th>Merk</th>
            <th>Keterangan</th>
            <th>Ukuran</th>
            <th>Unit</th>
            <th>Harga Satuan</th>
            <th>Kuantitas</th>
            <th>Total Unit</th>
            <th>Total Harga</th>
            <th>HPP</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = $start + 1; // Nomor urut dimulai dari offset + 1
        foreach ($store_request as $item): ?>
            <tr>
                <td><?= $no++ ?></td> <!-- Nomor urut -->
                <td><?= $item['tanggal'] ?></td>
                <td><?= $item['nama_jenis_pengeluaran'] ?></td>
                <td><?= $item['nama_barang'] ?></td>
                <td><?= $item['merk'] ?></td>
                <td><?= $item['keterangan'] ?></td>
                <td class="text-right"><?= $item['ukuran'] ?></td>
                <td><?= $item['unit'] ?></td>
                <td class="text-right"><?= number_format($item['harga'], 2) ?></td>
                <td class="text-right"><?= $item['kuantitas'] ?></td>
                <td class="text-right"><?= $item['kuantitas'] * $item['ukuran'] ?></td>
                <td class="text-right"><?= number_format($item['kuantitas'] * $item['harga'], 2) ?></td>
                <td class="text-right"><?= number_format($item['harga'] / $item['ukuran'], 2) ?></td>
                <td>
                    <!-- <a href="<?= base_url('storerequest/edit/' . $item['id']) ?>" class="btn btn-warning btn-sm">Edit</a> -->
                    <a href="<?= base_url('storerequest/delete/' . $item['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                </td>

            </tr>
        <?php endforeach; ?>
    </tbody>
        <tfoot>
        <tr>
            <th colspan="11" class="text-right">Total</th>
            <th><?= number_format($total_harga, 2) ?></th> <!-- Display Total Harga -->
            <th></th> <!-- Empty column for actions -->
        </tr>
    </tfoot>
</table>
</div>
<!-- Add this where you display the pagination links -->
<div class="pagination-container">
    <?= $pagination ?> <!-- This will display the pagination links -->
</div>
</div>

<script>
$(document).ready(function() {
    // Pencarian AJAX

    $('#search_barang').keyup(function() {
        let query = $(this).val();
        let bulan = $('#bulan').val();  // Ambil nilai bulan dari dropdown filter
        let tahun = $('#tahun').val();  // Ambil nilai tahun dari dropdown filter

        if (query.length > 2) {
            $.ajax({
                url: '<?= base_url('StoreRequest/search_barang') ?>',
                type: 'GET',
                data: { query: query, bulan: bulan, tahun: tahun },  // Kirim filter bulan & tahun
                dataType: 'json',
                success: function(data) {
                    let list = '';
                    data.forEach(item => {
                        list += `<li class="list-group-item search-item" data-id="${item.bl_db_purchase_id}" 
                                data-nama="${item.nama_barang}" data-merk="${item.merk}" 
                                data-keterangan="${item.keterangan}" data-ukuran="${item.ukuran}" 
                                data-unit="${item.unit}" data-harga="${item.harga}" 
                                data-sisa="${item.stok_akhir}">
                                ${item.nama_barang} | ${item.merk} | ${item.keterangan} | ${item.ukuran}-${item.unit} | Harga: ${item.harga} | Sisa Stok: ${item.stok_akhir}
                                <button class="btn btn-primary btn-sm float-right pilih-barang" data-id="${item.bl_db_purchase_id}">Pilih</button>
                                </li>`;
                    });
                    $('#search_result').html(list).show();
                }
            });
        } else {
            $('#search_result').hide();
        }
    });


    // Pilih barang dari hasil pencarian
    $(document).on('click', '.pilih-barang', function() {
        const parent = $(this).closest('.search-item');
        $('#nama_barang').val(parent.data('nama'));
        $('#merk').val(parent.data('merk'));
        $('#keterangan').val(parent.data('keterangan'));
        $('#ukuran').val(parent.data('ukuran'));
        $('#unit').val(parent.data('unit'));
        $('#harga').val(parent.data('harga'));
        $('#sisa_stok').val(parent.data('sisa'));
        $('#bl_db_purchase_id').val(parent.data('id')); // Simpan bl_db_purchase_id
        $('#search_result').hide();
    });

    // Simpan Store Request
    $('#storeRequestForm').submit(function (e) {
        e.preventDefault(); // Mencegah refresh halaman

        // Ambil data dari form
        let formData = {
            tanggal: $('#tanggal').val(),
            nama_barang: $('#nama_barang').val(),
            merk: $('#merk').val(),
            keterangan: $('#keterangan').val(),
            ukuran: $('#ukuran').val(),
            unit: $('#unit').val(),
            harga: $('#harga').val(),
            sisa_stok: $('#sisa_stok').val(),
            jenis_pengeluaran: $('#jenis_pengeluaran').val(),
            kuantitas: $('#kuantitas').val(),
            bl_db_purchase_id: $('#bl_db_purchase_id').val() // Tambahkan ID ke form
        };

        // Validasi data sebelum dikirim
        if (!formData.nama_barang || !formData.kuantitas || !formData.jenis_pengeluaran || !formData.bl_db_purchase_id) {
            alert('Harap lengkapi semua data sebelum menyimpan.');
            return;
        }

        // Kirim data ke server melalui AJAX
        $.ajax({
            url: '<?= base_url('StoreRequest/add') ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    alert('Data berhasil disimpan.');
                    location.reload(); // Refresh halaman
                } else {
                    alert('Gagal menyimpan data: ' + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', error);
                alert('Terjadi kesalahan saat menyimpan data.');
            }
        });
    });

});
$(document).ready(function() {
    // Hapus Barang
    $(document).on('click', '.btn-delete', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');

        if (confirm(`Apakah Anda yakin ingin menghapus barang "${name}"?`)) {
            $.ajax({
                url: '<?= base_url('StoreRequest/delete') ?>/' + id,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Data berhasil dihapus.');
                        location.reload();
                    } else {
                        alert('Gagal menghapus data: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    alert('Terjadi kesalahan saat menghapus data.');
                }
            });
        }
    });
});
$(document).ready(function () {
    // Pencarian barang di tabel
$('#search_barang').keyup(function() {
    let query = $(this).val();
    let bulan = $('#bulan').val();  // Ambil nilai bulan dari dropdown filter
    let tahun = $('#tahun').val();  // Ambil nilai tahun dari dropdown filter

    if (query.length > 2) {
        $.ajax({
            url: '<?= base_url('StoreRequest/search_barang') ?>',
            type: 'GET',
            data: { query: query, bulan: bulan, tahun: tahun },
            dataType: 'json',
            success: function(data) {
                let list = '';
                data.forEach(item => {
                    list += `<li class="list-group-item search-item" data-id="${item.bl_db_purchase_id}" 
                              data-nama="${item.nama_barang}" data-merk="${item.merk}" 
                              data-keterangan="${item.keterangan}" data-ukuran="${item.ukuran}" 
                              data-unit="${item.unit}" data-harga="${item.harga}" 
                              data-sisa="${item.stok_akhir}">
                              ${item.nama_barang} | ${item.merk} | ${item.keterangan} | ${item.ukuran}-${item.unit} | Harga: ${item.harga} | Sisa Stok: ${item.stok_akhir}
                              <button class="btn btn-primary btn-sm float-right pilih-barang" data-id="${item.bl_db_purchase_id}">Pilih</button>
                              </li>`;
                });
                $('#search_result').html(list).show();
            }
        });
    } else {
        $('#search_result').hide();
    }
});


    // Tambahkan konfirmasi untuk tombol hapus
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        if (confirm(`Apakah Anda yakin ingin menghapus ${name}?`)) {
            $.ajax({
                url: '<?= base_url('StoreRequest/delete') ?>/' + id,
                type: 'POST',
                success: function (response) {
                    alert('Data berhasil dihapus.');
                    location.reload(); // Refresh halaman setelah hapus
                },
                error: function (xhr, status, error) {
                    alert('Terjadi kesalahan saat menghapus data.');
                }
            });
        }
    });
});

</script>