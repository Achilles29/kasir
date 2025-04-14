<div class="container">
    <h2><?= $title ?></h2>

    <!-- Form Filter -->
    <form method="GET" action="<?= base_url('stokpenyesuaian') ?>" class="form-inline mb-3">
        <input type="date" name="tanggal_awal" class="form-control mr-2" value="<?= $tanggal_awal ?>">
        <input type="date" name="tanggal_akhir" class="form-control mr-2" value="<?= $tanggal_akhir ?>">
        <select name="limit" class="form-control mr-2">
            <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
            <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>20</option>
            <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <!-- Form Input -->
<form method="POST" action="<?= base_url('stokpenyesuaian/add') ?>">
    <input type="text" id="cari-barang" class="form-control mb-3" placeholder="Cari Barang">
    <ul id="list-barang" class="list-group mb-3" style="max-height: 200px; overflow-y: auto;"></ul>

    <div class="form-group">
        <label for="tanggal">Tanggal:</label>
        <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
    </div>
    <div class="form-group">
        <label for="nama_barang">Nama Barang:</label>
        <input type="text" id="nama_barang" name="nama_barang" class="form-control" readonly>
    </div>

    <div class="form-group">
        <label for="merk">Merk:</label>
        <input type="text" id="merk" name="merk" class="form-control" readonly>
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
        <label for="kuantitas">Kuantitas:</label>
        <input type="number" id="kuantitas" name="kuantitas" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="alasan">Alasan:</label>
        <input type="text" id="alasan" name="alasan" class="form-control">
    </div>
    <input type="hidden" id="bl_db_purchase_id" name="bl_db_purchase_id">

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>


    <!-- Tabel Data -->
<!-- Tabel Data -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nama Barang</th>
            <th>Merk</th>
            <th>Ukuran-Unit</th>
            <th>Harga</th>
            <th>Kuantitas</th>
            <th>Total Harga</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; ?>
        <?php foreach ($stok_penyesuaian as $row): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['tanggal'] ?></td>
                <td><?= $row['nama_barang'] ?></td>
                <td><?= $row['merk'] ?></td>
                <td><?= $row['ukuran'] . ' - ' . $row['unit'] ?></td>
                <td><?= number_format($row['harga'], 2, ',', '.') ?></td>
                <td><?= $row['kuantitas'] ?></td>
                <td><?= number_format($row['harga'] * $row['kuantitas'], 2, ',', '.') ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" 
                            data-toggle="modal" 
                            data-target="#editModal" 
                            onclick="loadEditData(<?= htmlspecialchars(json_encode($row)) ?>)">
                        Edit
                    </button>
                    <button class="btn btn-danger btn-sm" 
                            data-toggle="modal" 
                            data-target="#deleteModal" 
                            onclick="setDeleteId(<?= $row['id'] ?>)">
                        Hapus
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="editForm" method="post" action="<?= base_url('stokpenyesuaian/update') ?>">
            <input type="hidden" name="id" id="editId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Stok Penyesuaian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editTanggal">Tanggal</label>
                        <input type="date" id="editTanggal" name="tanggal" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="editNamaBarang">Nama Barang</label>
                        <input type="text" id="editNamaBarang" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="editMerk">Merk</label>
                        <input type="text" id="editMerk" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="editKuantitas">Kuantitas</label>
                        <input type="number" id="editKuantitas" name="kuantitas" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="editAlasan">Alasan</label>
                        <textarea id="editAlasan" name="alasan" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Modal Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="deleteForm" method="post" action="<?= base_url('stokpenyesuaian/delete') ?>">
            <input type="hidden" name="id" id="deleteId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>


    <!-- Pagination -->
    <div class="pagination">
        <?= $pagination ?>
    </div>
</div>

<script>
    document.getElementById('cari-barang').addEventListener('input', function () {
        const keyword = this.value.trim();

        if (keyword.length > 0) {
            fetch('<?= base_url('StokPenyesuaian/search_barang') ?>?keyword=' + encodeURIComponent(keyword))
                .then(response => response.json())
                .then(data => {
                    const list = document.getElementById('list-barang');
                    list.innerHTML = '';

                    if (data.length > 0) {
                        data.forEach(item => {
                            list.innerHTML += `
                                <li class="list-group-item">
                                    ${item.preview}
                                    <button class="btn btn-primary btn-sm float-right" 
                                        onclick="selectBarang(${JSON.stringify(item).replace(/"/g, '&quot;')})">Pilih</button>
                                </li>
                            `;
                        });
                    } else {
                        list.innerHTML = '<li class="list-group-item">Tidak ada data ditemukan.</li>';
                    }
                })
                .catch(error => console.error('Error fetching barang:', error));
        } else {
            document.getElementById('list-barang').innerHTML = '';
        }
    });

    function selectBarang(item) {
        // Tampilkan data pada form input
        document.getElementById('bl_db_purchase_id').value = item.bl_db_purchase_id;
        document.getElementById('nama_barang').value = item.nama_barang || '-';
        document.getElementById('merk').value = item.merk || '-';
        document.getElementById('ukuran_unit').value = `${item.ukuran || '-'} ${item.unit || '-'}`;
        document.getElementById('harga_satuan').value = item.harga_satuan || 0;

        // Kosongkan list pencarian
        document.getElementById('list-barang').innerHTML = '';
    }
    // Load data untuk modal edit
function loadEditData(data) {
    document.getElementById('editId').value = data.id;
    document.getElementById('editTanggal').value = data.tanggal;
    document.getElementById('editNamaBarang').value = data.nama_barang;
    document.getElementById('editMerk').value = data.merk;
    document.getElementById('editKuantitas').value = data.kuantitas;
    document.getElementById('editAlasan').value = data.alasan;
}

// Set ID untuk modal hapus
function setDeleteId(id) {
    document.getElementById('deleteId').value = id;
}

</script>
