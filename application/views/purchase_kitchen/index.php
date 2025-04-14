<style>
    .form-control-sm {
        font-size: 10px;
        padding: 5px;
    }

.btn-sm {
    font-size: 10px; /* Ukuran font button kecil */
    padding: 2px 5px; /* Kurangi padding button */
    margin: 0 2px; /* Jarak antar button */
}

/* Pastikan tombol dalam kolom aksi tidak membungkus */
.table td {
    white-space: nowrap; /* Mencegah pembungkus teks dalam sel tabel */
}

/* Sesuaikan ukuran tombol agar terlihat rapi */
.table td .btn {
    padding: 3px 8px; /* Kurangi padding tombol */
    font-size: 10px; /* Ukuran font lebih kecil */
    margin: 0 2px; /* Jarak antar tombol */
}

/* Pastikan kolom aksi cukup untuk dua tombol */
.table td:last-child {
    width: 120px; /* Atur lebar kolom aksi agar tombol tidak saling menumpuk */
    text-align: center; /* Rata tengah tombol */
}

.table-sm th, .table-sm td {
    font-size: 10px; /* Ukuran font lebih kecil */
    padding: 2px;    /* Padding untuk memperkecil ukuran sel */
    vertical-align: middle; /* Vertikal rata tengah */
}
/* Ratakan tabel ke tengah dalam div */
.table-wrapper {
    display: flex;
    justify-content: center; /* Rata tengah secara horizontal */
}
.table th {
    font-weight: bold; /* Judul tabel lebih tebal */
    font-size: 11px; /* Ukuran font data */
    text-align: center; /* Judul tabel rata tengah */
}

.table tbody tr td {
    font-size: 11px; /* Ukuran font data */
    text-align: left; /* Data rata kiri secara default */
}

    .filter-row {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }

    .filter-row select,
    .filter-row input {
        font-size: 12px;
        padding: 5px;
        width: auto;
    }

    .btn-group-sm > .btn {
        padding: 5px 10px;
    }

    .form-inline .form-control {
        margin-right: 10px;
    }

    .form-inline .form-group {
        margin-bottom: 10px;
    }

    .form-inline .form-control-highlight {
        border-color:rgb(37, 123, 214); /* Warna biru */
        background-color: #e9f5ff; /* Warna biru muda */
        font-weight: bold;
    }

    .form-inline .form-control-quantity {
        border-color:rgb(16, 156, 49); /* Warna hijau */
        background-color: #e6f8e6; /* Warna hijau muda */
        font-weight: bold;
    }

    .form-inline .form-control-payment {
        border-color:rgb(255, 65, 7); /* Warna kuning */
        background-color: #fff9e6; /* Warna kuning muda */
        font-weight: bold;
    }

    .btn-primary {
        margin-top: 5px;
    }
    .table th, .table td {
        text-align: center;
        vertical-align: middle;
    }
</style>

<div class="container-fluid">
    <h2>Purchase Order Kitchen</h2>
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php elseif ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <form class="mb-3" method="post" action="<?= base_url('purchase_kitchen/add') ?>">
        <div class="row g-3">
            <div class="col-md-4">
                <label for="cari-nama-barang">Cari Nama Barang</label>
                <input type="text" id="cari-nama-barang" class="form-control form-control-sm" placeholder="Cari nama barang...">
                <ul id="list-nama-barang" class="list-group mt-1" style="max-height: 150px; overflow-y: auto; font-size: 12px;"></ul>
            </div>
            <div class="col-md-4">
                <label for="tanggal">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" class="form-control form-control-sm" value="<?= set_value('tanggal', date('Y-m-d')) ?>">
            </div>
            <div class="col-md-4">
                <label for="jenis_pengeluaran">Jenis Pengeluaran</label>
                <select id="jenis_pengeluaran" name="jenis_pengeluaran" class="form-control form-control-sm form-control-highlight" required>
                    <option value="">Pilih Jenis Pengeluaran</option>
                    <?php foreach ($jenis_pengeluaran_list as $jenis): ?>
                        <option value="<?= $jenis['id'] ?>" <?= ($jenis['id'] == $default_jenis_pengeluaran) ? 'selected' : '' ?>>
                            <?= $jenis['nama_jenis_pengeluaran'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="nama_barang">Nama Barang</label>
                <input type="text" id="nama_barang" name="nama_barang" class="form-control form-control-sm">
            </div>
            <div class="col-md-4">
                <label for="nama_bahan_baku">Nama Bahan Baku</label>
                <input type="text" id="nama_bahan_baku" name="nama_bahan_baku" class="form-control form-control-sm">
            </div>
            <div class="col-md-4">
                <label for="kategori">Kategori</label>
                <select id="kategori" name="kategori" class="form-control form-control-sm">
                        <?php foreach ($kategori_list as $kategori): ?>
                            <option value="<?= $kategori['id'] ?>"><?= $kategori['nama_kategori'] ?></option>
                        <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="tipe_produksi">Tipe Produksi</label>
                <select id="tipe_produksi" name="tipe_produksi" class="form-control form-control-sm">
                    <?php foreach ($tipe_produksi_list as $tipe): ?>
                        <option value="<?= $tipe['id'] ?>"><?= $tipe['nama_tipe_produksi'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="merk">Merk</label>
                <input type="text" id="merk" name="merk" class="form-control form-control-sm">
            </div>
            <div class="col-md-4">
                <label for="keterangan">Keterangan</label>
                <textarea id="keterangan" name="keterangan" class="form-control form-control-sm"></textarea>
            </div>
            <div class="col-md-2">
                <label for="ukuran">Ukuran</label>
                <input type="text" id="ukuran" name="ukuran" class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <label for="unit">Unit</label>
                <input type="text" id="unit" name="unit" class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <label for="pack">Pack</label>
                <input type="text" id="pack" name="pack" class="form-control form-control-sm">
            </div>
            <div class="col-md-4">
                <label for="harga_satuan">Harga Satuan</label>
                <input type="number" id="harga_satuan" name="harga_satuan" class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <label for="kuantitas">Kuantitas</label>
                <input type="number" id="kuantitas" name="kuantitas" class="form-control form-control-sm form-control-quantity" min="1" value="1">
            </div>
            <div class="col-md-4">
                <label for="metode_pembayaran">Metode Pembayaran</label>
                <select id="metode_pembayaran" name="metode_pembayaran" class="form-control form-control-sm form-control-payment" required>
                    <?php foreach ($metode_pembayaran as $metode): ?>
                        <option value="<?= $metode['id'] ?>" <?= ($metode['id'] == $default_metode_pembayaran) ? 'selected' : '' ?>>
                            <?= $metode['nama_rekening'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="catatan">Catatan</label>
                <textarea id="catatan" name="catatan" class="form-control form-control-sm"></textarea>
            </div>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
        </div>
    </form>
</div>

                    <!-- filter -->
    <div class="row mb-2">
        <div class="col-md-3">
            <input type="text" id="search-table" class="form-control form-control-sm" placeholder="Cari data di tabel...">
        </div>
    </div>

<form method="GET" action="<?= base_url('purchase_kitchen/index') ?>" class="form-inline mb-3">
    <label for="tanggal_awal" class="mr-2">Tanggal Awal</label>
    <input type="date" id="tanggal_awal" name="tanggal_awal" class="form-control mr-3" value="<?= $tanggal_awal ?>">

    <label for="tanggal_akhir" class="mr-2">Tanggal Akhir</label>
    <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="form-control mr-3" value="<?= $tanggal_akhir ?>">

    <label for="per_page" class="mr-2">Baris per Halaman</label>
    <select id="per_page" name="per_page" class="form-control mr-3">
        <option value="5" <?= $per_page == 5 ? 'selected' : '' ?>>5</option>
        <option value="10" <?= $per_page == 10 ? 'selected' : '' ?>>10</option>
        <option value="20" <?= $per_page == 20 ? 'selected' : '' ?>>20</option>
        <option value="50" <?= $per_page == 50 ? 'selected' : '' ?>>50</option>
    </select>

    <button type="submit" class="btn btn-primary">Terapkan</button>
</form>



        <!-- Tabel Data -->
        <div class="col-md-11">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="text-align: center;">No</th>
                        <th style="text-align: center;">Tanggal</th>
                        <th style="text-align: center;">Jenis Pengeluaran</th> <!-- Tambahkan kolom ini -->
                        <th style="text-align: center;">Nama Barang</th>
                        <th style="text-align: center;">Nama Bahan Baku</th>
                        <th style="text-align: center;">Kategori</th>
                        <th style="text-align: center;">Tipe Produksi</th>
                        <th style="text-align: center;">Merk</th>
                        <th style="text-align: center;">Keterangan</th>
                        <th style="text-align: center;">Ukuran</th>
                        <th style="text-align: center;">Unit</th>
                        <th style="text-align: center;">Pack</th>
                        <th style="text-align: center;">Harga Satuan</th>
                        <th style="text-align: center;">Kuantitas</th>
                        <th style="text-align: center;">Total Unit</th>
                        <th style="text-align: center;">Total Harga</th>
                        <th style="text-align: center;">HPP</th>
                        <th style="text-align: center;">Metode Pembayaran</th>
                        <th style="text-align: center;">catatan</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                     <?php $no = 1; foreach ($purchases as $purchase): ?>
                        <tr>
                            <td><?= $no++; ?></td>    
                            <td><?= $purchase['tanggal'] ?></td>
                            <td><?= $purchase['jenis_pengeluaran'] ?></td>
                            <td><?= $purchase['nama_barang'] ?></td>
                            <td><?= $purchase['nama_bahan_baku'] ?></td>
                            <td><?= $purchase['kategori'] ?></td>
                            <td><?= $purchase['tipe_produksi'] ?></td>
                            <td><?= $purchase['merk'] ?></td>
                            <td><?= $purchase['keterangan'] ?></td>
                            <td><?= $purchase['ukuran'] ?></td>
                            <td><?= $purchase['unit'] ?></td>
                            <td><?= $purchase['pack'] ?></td>
                            <td style="text-align: right;"><?= number_format($purchase['harga_satuan'], 2) ?></td>
                            <td style="text-align: right;"><?= number_format($purchase['kuantitas'], 2) ?></td>
                            <td style="text-align: right;"><?= number_format($purchase['total_unit'], 2) ?></td>
                            <td style="text-align: right;"><?= number_format($purchase['total_harga'], 2) ?></td>
                            <td style="text-align: right;"><?= number_format($purchase['hpp'], 2) ?></td>
                            <td><?= $purchase['metode_pembayaran'] ?></td>
                            <td><?= $purchase['catatan'] ?></td>
                            <td><?= $purchase['status'] ?></td>
                            <td>
                                <a href="<?= base_url('purchase_kitchen/edit/' . $purchase['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="<?= base_url('purchase_kitchen/delete/' . $purchase['id']) ?>" class="btn btn-danger btn-sm">Hapus</a>
                                <?php if ($purchase['status'] === 'pending'): ?>
                                    <a href="<?= base_url('purchase_kitchen/verify/' . $purchase['id']) ?>" class="btn btn-success btn-sm">Verifikasi</a>
                                    <a href="<?= base_url('purchase_kitchen/reject/' . $purchase['id']) ?>" class="btn btn-danger btn-sm">Tolak</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="11" style="text-align: right;"><strong>Total Harga:</strong></td>
                        <td style="text-align: right;"><?= number_format($total_harga, 2) ?></td>
                    </tr>


                </tbody>

            </table>

        <!-- Pagination -->
<div class="pagination">
    <?= $pagination; ?>
</div>


        </div>
    </div>




<script>
document.getElementById('cari-nama-barang').addEventListener('input', function () {
    const keyword = this.value.trim();

    if (keyword.length > 0) {
        fetch(`<?= base_url('purchase_kitchen/search_barang') ?>?keyword=${encodeURIComponent(keyword)}`)
            .then(response => response.json())
            .then(data => {
                const list = document.getElementById('list-nama-barang');
                list.innerHTML = ''; // Kosongkan list sebelum menampilkan hasil

                if (data.length > 0) {
                    data.forEach(item => {
                        const preview = `
                            ${item.nama_barang || ''} - ${item.merk || '-'} - ${item.keterangan || '-'} - ${item.ukuran || '-'} ${item.unit || '-'} - Rp ${item.harga_satuan || 0}
                        `;
                        list.innerHTML += `
                            <li class="list-group-item">
                                <span>${preview}</span>
                                <button class="btn btn-primary btn-sm float-right" 
                                    onclick="selectBarang(${JSON.stringify(item).replace(/"/g, '&quot;')})">Pilih</button>
                            </li>
                        `;
                    });
                } else {
                    list.innerHTML = '<li class="list-group-item">Tidak ada data ditemukan</li>';
                }
            })
            .catch(error => {
                console.error('Error fetching barang:', error);
            });
    } else {
        document.getElementById('list-nama-barang').innerHTML = ''; // Kosongkan list jika keyword terlalu pendek
    }
});

function selectBarang(item) {
    document.getElementById('nama_barang').value = item.nama_barang;
    document.getElementById('nama_bahan_baku').value = item.nama_bahan_baku;
    document.getElementById('kategori').value = item.id_kategori; // Set default kategori sesuai ID
    document.getElementById('tipe_produksi').value = item.id_tipe_produksi; // Set default tipe produksi sesuai ID
    document.getElementById('merk').value = item.merk || '';
    document.getElementById('keterangan').value = item.keterangan || '';
    document.getElementById('ukuran').value = item.ukuran || '';
    document.getElementById('unit').value = item.unit || '';
    document.getElementById('pack').value = item.pack || '';
    document.getElementById('harga_satuan').value = item.harga_satuan || 0;
    document.getElementById('list-nama-barang').innerHTML = ''; // Kosongkan list setelah memilih
}

document.querySelector('form').addEventListener('submit', function (e) {
    const kuantitas = parseFloat(document.getElementById('kuantitas').value);
    const unit = parseFloat(document.getElementById('unit').value);
    const hargaSatuan = parseFloat(document.getElementById('harga_satuan').value);

    if (isNaN(kuantitas) || isNaN(unit) || isNaN(hargaSatuan)) {
        alert('Harap masukkan angka yang valid untuk kuantitas, unit, dan harga satuan.');
        e.preventDefault();
    }
});

document.getElementById('search-table').addEventListener('input', function () {
    const keyword = this.value.toLowerCase();
    const rows = document.querySelectorAll('table tbody tr');

    rows.forEach(row => {
        const cells = Array.from(row.querySelectorAll('td')).map(td => td.textContent.toLowerCase());
        row.style.display = cells.some(cell => cell.includes(keyword)) ? '' : 'none';
    });
});





</script>
