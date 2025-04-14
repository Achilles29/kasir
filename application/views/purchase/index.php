<style>
    @media (max-width: 576px) {
        .form-row {
            flex-direction: column;
        }

        .form-row .col-md-3,
        .form-inline .form-group {
            width: 100%;
        }

        .form-inline label,
        .form-inline input,
        .form-inline select,
        .form-inline button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }

        .table th,
        .table td {
            font-size: 12px;
            white-space: nowrap;
        }

        .btn-block {
            width: 100%;
        }
    }

    .form-control-sm {
        font-size: 14px;
        padding: 5px;
    }

    .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }

    .form-row .col-md-3 {
        flex: 1 1 calc(25% - 15px);
        min-width: 200px;
    }

    .list-group {
        position: absolute;
        z-index: 1000;
        background: white;
        width: calc(100% - 20px);
        border: 1px solid #ddd;
        margin-top: -5px;
        padding: 5px;
    }

    .list-group-item {
        padding: 5px;
        cursor: pointer;
    }

    .list-group-item:hover {
        background: #f8f9fa;
    }
</style>



<div class="container-fluid">
    <h2 class="mb-4">Purchase Management</h2>

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php elseif ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <!-- Form Input -->
    <form method="post" action="<?= base_url('purchase/add?tanggal_awal=' . $tanggal_awal . '&tanggal_akhir=' . $tanggal_akhir) ?>" class="mb-4">
        <input type="hidden" name="tanggal_awal" value="<?= $tanggal_awal ?>">
        <input type="hidden" name="tanggal_akhir" value="<?= $tanggal_akhir ?>">

        <div class="row g-3">
            <!-- Baris 1 -->
            <div class="col-md-3">
                <label for="tanggal">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" class="form-control form-control-sm" value="<?= set_value('tanggal', date('Y-m-d')) ?>" required>
            </div>
            <div class="col-md-3">
                <label for="jenis_pengeluaran">Jenis Pengeluaran</label>
                <select id="jenis_pengeluaran" name="jenis_pengeluaran" class="form-control form-control-sm" required>
                    <?php foreach ($jenis_pengeluaran_list as $jenis): ?>
                        <option value="<?= $jenis['id'] ?>" <?= $jenis['id'] == 1 ? 'selected' : '' ?>>
                            <?= $jenis['nama_jenis_pengeluaran'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="cari-nama-barang">Cari Nama Barang</label>
                <input type="text" id="cari-nama-barang" class="form-control form-control-sm" placeholder="Cari nama barang..." autocomplete="off">
                <ul id="list-nama-barang" class="list-group" style="max-height: 150px; overflow-y: auto; font-size: 12px;"></ul>
            </div>

            <!-- Baris 2 -->
            <div class="col-md-3">
                <label for="nama_barang">Nama Barang</label>
                <input type="text" id="nama_barang" name="nama_barang" class="form-control form-control-sm">
            </div>
            <div class="col-md-3">
                <label for="nama_bahan_baku">Nama Bahan Baku</label>
                <input type="text" id="nama_bahan_baku" name="nama_bahan_baku" class="form-control form-control-sm">
            </div>
            <div class="col-md-3">
                <label for="kategori">Kategori</label>
                <select id="kategori" name="kategori" class="form-control form-control-sm">
                    <?php foreach ($kategori_list as $kategori): ?>
                        <option value="<?= $kategori['id'] ?>"><?= $kategori['nama_kategori'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="tipe_produksi">Tipe Produksi</label>
                <select id="tipe_produksi" name="tipe_produksi" class="form-control form-control-sm">
                    <?php foreach ($tipe_produksi_list as $tipe): ?>
                        <option value="<?= $tipe['id'] ?>"><?= $tipe['nama_tipe_produksi'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Baris 3 -->
            <div class="col-md-3">
                <label for="merk">Merk</label>
                <input type="text" id="merk" name="merk" class="form-control form-control-sm">
            </div>
            <div class="col-md-3" id="form-keterangan">
                <label for="keterangan">Keterangan</label>
                <input type="text" id="keterangan" name="keterangan" class="form-control form-control-sm">
            </div>
            <div class="col-md-3 d-none" id="form-pegawai">
                <label for="pegawai_id">Pilih Pegawai</label>
                <select id="pegawai_id" name="pegawai_id" class="form-control form-control-sm">
                    <?php foreach ($pegawai_list as $pegawai): ?>
                        <option value="<?= $pegawai['id'] ?>"><?= $pegawai['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="ukuran">Ukuran</label>
                <input type="text" id="ukuran" name="ukuran" class="form-control form-control-sm">
            </div>

            <!-- Baris 4 -->
            <div class="col-md-3">
                <label for="unit">Unit</label>
                <input type="text" id="unit" name="unit" class="form-control form-control-sm">
            </div>
            <div class="col-md-3">
                <label for="pack">Pack</label>
                <input type="text" id="pack" name="pack" class="form-control form-control-sm">
            </div>
            <div class="col-md-3">
                <label for="harga_satuan">Harga Satuan</label>
                <input type="number" id="harga_satuan" name="harga_satuan" class="form-control form-control-sm">
            </div>
            <div class="col-md-3">
                <label for="kuantitas">Kuantitas</label>
                <input type="number" id="kuantitas" name="kuantitas" class="form-control form-control-sm" min="1" value="1">
            </div>

            <!-- Baris 5 -->
            <div class="col-md-3">
                <label for="metode_pembayaran">Metode Pembayaran</label>
                <select id="metode_pembayaran" name="metode_pembayaran" class="form-control form-control-sm" required>
                    <?php foreach ($metode_pembayaran as $metode): ?>
                        <option value="<?= $metode['id'] ?>" <?= $metode['id'] == 1 ? 'selected' : '' ?>>
                            <?= $metode['nama_rekening'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary btn-sm btn-block">Simpan</button>
            </div>
        </div>
    </form>


<!-- <form method="GET" action="<?= base_url('purchase/index') ?>" class="row mb-3">
    <div class="col-12 col-md-3">
        <label for="tanggal_awal">Tanggal Awal</label>
        <input type="date" id="tanggal_awal" name="tanggal_awal" class="form-control" value="<?= $tanggal_awal ?>">
    </div>
    <div class="col-12 col-md-3">
        <label for="tanggal_akhir">Tanggal Akhir</label>
        <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="form-control" value="<?= $tanggal_akhir ?>">
    </div>
    <div class="col-12 col-md-3">
        <label for="jenis_pengeluaran">Jenis Pengeluaran</label>
        <select id="jenis_pengeluaran" name="jenis_pengeluaran" class="form-control">
            <option value="">Semua</option>
            <?php foreach ($jenis_pengeluaran_list as $jenis): ?>
                <option value="<?= $jenis['id'] ?>" <?= $jenis['id'] == $jenis_pengeluaran ? 'selected' : '' ?>>
                    <?= $jenis['nama_jenis_pengeluaran'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-12 col-md-2">
        <label for="per_page">Baris / Halaman</label>
        <select id="per_page" name="per_page" class="form-control">
            <option value="5" <?= $per_page == 5 ? 'selected' : '' ?>>5</option>
            <option value="10" <?= $per_page == 10 ? 'selected' : '' ?>>10</option>
            <option value="20" <?= $per_page == 20 ? 'selected' : '' ?>>20</option>
            <option value="50" <?= $per_page == 50 ? 'selected' : '' ?>>50</option>
            <option value="100" <?= $per_page == 100 ? 'selected' : '' ?>>100</option>
            <option value="all" <?= $per_page == 'all' ? 'selected' : '' ?>>Semua</option>
        </select>
    </div>
    <div class="col-12 col-md-1 d-flex align-items-end">
        <button type="submit" class="btn btn-primary btn-block">Terapkan</button>
    </div>
</form> -->




    <!-- Filter -->
    <form method="GET" action="<?= base_url('purchase/index') ?>" class="row g-3 mb-3">
        <div class="col-md-3">
            <label for="tanggal_awal">Tanggal Awal</label>
            <input type="date" id="tanggal_awal" name="tanggal_awal" class="form-control" value="<?= $tanggal_awal ?>">
        </div>
        <div class="col-md-3">
            <label for="tanggal_akhir">Tanggal Akhir</label>
            <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="form-control" value="<?= $tanggal_akhir ?>">
        </div>
        <div class="col-md-3">
            <label for="jenis_pengeluaran">Jenis Pengeluaran</label>
            <select id="jenis_pengeluaran" name="jenis_pengeluaran" class="form-control">
                <option value="">Semua</option>
                <?php foreach ($jenis_pengeluaran_list as $jenis): ?>
                    <option value="<?= $jenis['id'] ?>" <?= $jenis['id'] == $jenis_pengeluaran ? 'selected' : '' ?>>
                        <?= $jenis['nama_jenis_pengeluaran'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label for="per_page">Baris / Halaman</label>
            <select id="per_page" name="per_page" class="form-control">
                <option value="5" <?= $per_page == 5 ? 'selected' : '' ?>>5</option>
                <option value="10" <?= $per_page == 10 ? 'selected' : '' ?>>10</option>
                <option value="20" <?= $per_page == 20 ? 'selected' : '' ?>>20</option>
                <option value="50" <?= $per_page == 50 ? 'selected' : '' ?>>50</option>
                <option value="100" <?= $per_page == 100 ? 'selected' : '' ?>>100</option>
                <option value="all" <?= $per_page == 'all' ? 'selected' : '' ?>>Semua</option>
            </select>
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button type="submit" class="btn btn-primary btn-block">Terapkan</button>
        </div>
    </form>

    <!-- Tabel -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-sm" style="font-size: 14px;">
            <thead class="table-light text-center">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jenis Pengeluaran</th>
                    <th>Nama Barang</th>
                    <th>Nama Bahan Baku</th>
                    <th>Kategori</th>
                    <th>Tipe Produksi</th>
                    <th>Merk</th>
                    <th>Keterangan</th>
                    <th>Ukuran</th>
                    <th>Unit</th>
                    <th>Pack</th>
                    <th>Harga Satuan</th>
                    <th>Kuantitas</th>
                    <th>Total Unit</th>
                    <th>Total Harga</th>
                    <th>HPP</th>
                    <th>Metode Pembayaran</th>
                    <th>Pengusul</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($purchases as $purchase): ?>
                <tr>
                    <td><?= $no++ ?></td>
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
                    <td class="text-end"><?= number_format($purchase['harga_satuan'], 2) ?></td>
                    <td class="text-end"><?= number_format($purchase['kuantitas'], 2) ?></td>
                    <td class="text-end"><?= number_format($purchase['total_unit'], 2) ?></td>
                    <td class="text-end"><?= number_format($purchase['total_harga'], 2) ?></td>
                    <td class="text-end"><?= number_format($purchase['hpp'], 2) ?></td>
                    <td><?= $purchase['metode_pembayaran'] ?></td>
                    <td><?= $purchase['pengusul'] ?></td>
                    <td><?= $purchase['status'] ?></td>
                    <td>
                        <a href="<?= base_url('purchase/delete/' . $purchase['id'] . '?tanggal_awal=' . $tanggal_awal . '&tanggal_akhir=' . $tanggal_akhir) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="15" class="text-end"><strong>Total Harga:</strong></td>
                    <td class="text-end"><strong><?= number_format($total_harga, 2) ?></strong></td>
                    <td colspan="5"></td>
                </tr>
            </tbody>
        </table>
        <div class="pagination mt-2"><?= $pagination; ?></div>
    </div>
</div>


<script>
document.getElementById('cari-nama-barang').addEventListener('input', function () {
    const keyword = this.value.trim();

    if (keyword.length > 0) {
        fetch(`<?= base_url('purchase/search_barang') ?>?keyword=${encodeURIComponent(keyword)}`)
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
document.getElementById('jenis_pengeluaran').addEventListener('change', function () {
    const isKasbon = this.value == 17; // ID KASBON
    document.getElementById('form-keterangan').classList.toggle('d-none', isKasbon);
    document.getElementById('form-pegawai').classList.toggle('d-none', !isKasbon);
});
</script>
