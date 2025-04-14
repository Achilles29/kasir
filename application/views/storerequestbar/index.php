<div class="container-fluid">
    <h2><?= $title ?></h2>

    <!-- Filter Tanggal -->
    <form method="GET" action="<?= base_url('storerequestbar/index') ?>" class="form-inline mb-3">
        <label for="tanggal_awal" class="mr-2">Tanggal Awal:</label>
        <input type="date" id="tanggal_awal" name="tanggal_awal" class="form-control mr-3" value="<?= $tanggal_awal ?>">

        <label for="tanggal_akhir" class="mr-2">Tanggal Akhir:</label>
        <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="form-control mr-3" value="<?= $tanggal_akhir ?>">

        <label for="per_page" class="mr-2">Baris per Halaman:</label>
        <select id="per_page" name="per_page" class="form-control mr-3">
            <option value="5" <?= $per_page == 5 ? 'selected' : '' ?>>5</option>
            <option value="10" <?= $per_page == 10 ? 'selected' : '' ?>>10</option>
            <option value="20" <?= $per_page == 20 ? 'selected' : '' ?>>20</option>
            <option value="50" <?= $per_page == 50 ? 'selected' : '' ?>>50</option>
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <!-- Form Input -->
    <form id="form-add-request" method="POST" action="<?= base_url('storerequestbar/add') ?>">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="tanggal">Tanggal:</label>
                    <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="form-group">
                    <label for="cari-barang">Cari Barang:</label>
                    <input type="text" id="cari-barang" class="form-control" placeholder="Cari nama barang...">
                    <ul id="list-barang" class="list-group" style="max-height: 200px; overflow-y: auto;"></ul>
                </div>
                <div class="form-group">
                    <label for="nama_barang">Nama Barang:</label>
                    <input type="text" id="nama_barang" name="nama_barang" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label for="merk">Merk:</label>
                    <input type="text" id="merk" name="merk" class="form-control" readonly>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="keterangan">Keterangan:</label>
                    <textarea id="keterangan" name="keterangan" class="form-control" readonly></textarea>
                </div>
                <div class="form-group">
                    <label for="ukuran_unit">Ukuran - Unit:</label>
                    <input type="text" id="ukuran_unit" name="ukuran_unit" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label for="harga_satuan">Harga Satuan:</label>
                    <input type="number" id="harga_satuan" name="harga_satuan" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label for="jenis_pengeluaran">Jenis Pengeluaran:</label>
                    <input type="hidden" id="jenis_pengeluaran" name="jenis_pengeluaran" value="2">
                    <input type="text" class="form-control" value="BAR" readonly>
                </div>

            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="stok_akhir">Sisa Stok</label>
                    <input type="number" id="stok_akhir" name="stok_akhir" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label for="kuantitas">Kuantitas</label>
                    <input type="number" id="kuantitas" name="kuantitas" class="form-control" min="1" required>
                </div>

                <div class="form-group">
                    <label for="catatan">Catatan:</label>
                    <textarea id="catatan" name="catatan" class="form-control"></textarea>
                </div>
                <input type="hidden" id="bl_db_purchase_id" name="bl_db_purchase_id">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </form>
    <!-- Kolom Pencarian -->
<div class="form-group">
    <label for="search-table" class="mr-2">Pencarian:</label>
    <input type="text" id="search-table" class="form-control" placeholder="Cari berdasarkan nama barang, merk, atau jenis pengeluaran...">
</div>


    <!-- Tabel Data -->
<table class="table table-bordered table-striped mt-3">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nama Barang</th>
            <th>Merk</th>
            <th>Keterangan</th>
            <th>Ukuran</th>
            <th>Unit</th>
            <th>Harga Satuan</th>
            <th>Kuantitas</th>
            <th>Total Harga</th>
       <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = isset($page) ? $page + 1 : 1; ?>
        <?php foreach ($store_requests as $request): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $request['tanggal'] ?></td>
                <td><?= $request['nama_barang'] ?></td>
                <td><?= $request['merk'] ?></td>
                <td><?= $request['keterangan'] ?></td>
                <td><?= $request['ukuran'] ?></td>
                <td><?= $request['unit'] ?></td>
                <td><?= number_format($request['harga_satuan'], 0, ',', '.') ?></td>
                <td><?= $request['kuantitas'] ?></td>
                <td><?= number_format($request['harga_satuan'] * $request['kuantitas'], 0, ',', '.') ?></td>
                <td><?= ucfirst($request['status']) ?></td>
                <td>
                    <a href="<?= base_url('storerequestbar/edit/' . $request['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="<?= base_url('storerequestbar/delete/' . $request['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                    <?php if ($request['status'] === 'pending'): ?>
                        <a href="<?= base_url('storerequestbar/verify/' . $request['id']) ?>" class="btn btn-success btn-sm">Verifikasi</a>
                        <a href="<?= base_url('storerequestbar/reject/' . $request['id']) ?>" class="btn btn-danger btn-sm">Tolak</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


    <!-- Pagination -->
    <div class="pagination">
        <?= $pagination ?>
    </div>
</div>

<script>
document.getElementById('cari-barang').addEventListener('input', function () {
    const keyword = this.value.trim();

    if (keyword.length > 0) {
        fetch('<?= base_url('StoreRequestBar/search_barang') ?>?keyword=' + encodeURIComponent(keyword))
            .then(response => response.json())
            .then(data => {
                const list = document.getElementById('list-barang');
                list.innerHTML = '';

                if (data.length > 0) {
                    data.forEach(item => {
                        const preview = `
                            ${item.nama_barang || ''} - ${item.merk || '-'} - ${item.keterangan || '-'} - ${item.ukuran || '-'} ${item.unit || '-'} - Rp ${item.harga_satuan || 0} - SISA STOK: ${item.stok_akhir || 0}
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
            .catch(error => console.error('Error fetching barang:', error));
    } else {
        document.getElementById('list-barang').innerHTML = '';
    }
});

function selectBarang(item) {
    document.getElementById('nama_barang').value = item.nama_barang;
    document.getElementById('merk').value = item.merk;
    document.getElementById('keterangan').value = item.keterangan;
    document.getElementById('ukuran_unit').value = `${item.ukuran} - ${item.unit}`;
    document.getElementById('harga_satuan').value = item.harga_satuan;
    document.getElementById('stok_akhir').value = item.stok_akhir; // Tambahkan stok akhir ke form
    document.getElementById('bl_db_purchase_id').value = item.bl_db_purchase_id;
    document.getElementById('list-barang').innerHTML = '';
}


document.getElementById('kuantitas').addEventListener('input', function () {
    const kuantitas = parseInt(this.value);
    const stokAkhir = parseInt(document.getElementById('stok_akhir').value);

    if (kuantitas > stokAkhir) {
        alert('Kuantitas melebihi Sisa Stok!');
        this.value = '';
    }
});
document.getElementById('search-table').addEventListener('input', function () {
    const query = this.value.trim();

    if (query.length > 0) {
        fetch('<?= base_url('StoreRequestBar/filter_table') ?>?query=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('table tbody');
                tableBody.innerHTML = ''; // Kosongkan tabel sebelum update

                if (data.length > 0) {
                    let no = 1;
                    data.forEach(item => {
                        tableBody.innerHTML += `
                            <tr>
                                <td>${no++}</td>
                                <td>${item.tanggal}</td>
                                <td>${item.nama_jenis_pengeluaran || '-'}</td>
                                <td>${item.nama_barang || '-'}</td>
                                <td>${item.merk || '-'}</td>
                                <td>${item.keterangan || '-'}</td>
                                <td>${item.ukuran || '-'}</td>
                                <td>${item.unit || '-'}</td>
                                <td>${parseFloat(item.harga_satuan).toLocaleString('id-ID')}</td>
                                <td>${item.kuantitas}</td>
                                <td>${(item.harga_satuan * item.kuantitas).toLocaleString('id-ID')}</td>
                                <td>${item.status.charAt(0).toUpperCase() + item.status.slice(1)}</td>
                                <td>
                                    <a href="<?= base_url('storerequestbar/edit/') ?>${item.id}" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="<?= base_url('storerequestbar/delete/') ?>${item.id}" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                                    ${item.status === 'pending' ? `
                                        <a href="<?= base_url('storerequestbar/verify/') ?>${item.id}" class="btn btn-success btn-sm">Verifikasi</a>
                                        <a href="<?= base_url('storerequestbar/reject/') ?>${item.id}" class="btn btn-danger btn-sm">Tolak</a>
                                    ` : ''}
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    tableBody.innerHTML = '<tr><td colspan="13" class="text-center">Tidak ada data ditemukan.</td></tr>';
                }
            })
            .catch(error => console.error('AJAX Error:', error));
    } else {
        location.reload(); // Reload halaman jika pencarian kosong
    }
});


</script>
