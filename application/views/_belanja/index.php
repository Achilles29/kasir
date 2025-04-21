<style>
.table tbody td .btn-group .btn {
    margin-right: 5px;
}
.text-center .btn-group {
    display: inline-flex;
    justify-content: center;
}
</style>

<div class="container" style="padding-left: 10px;">
    <h2 class="mb-3"><?= $title ?></h2>
    <div class="row">
        <!-- Form Input -->
        <div class="col-md-3">
            <form id="form-add-belanja" style="padding: 0 10px;">
                <div class="form-group mb-2">
                    <label for="nama_barang">Nama Barang</label>
                    <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
                </div>
                <div class="form-group mb-2">
                    <label for="nama_bahan_baku">Nama Bahan Baku</label>
                    <input type="text" class="form-control" id="nama_bahan_baku" name="nama_bahan_baku">
                </div>
                <div class="form-group mb-2">
                    <label for="id_kategori">Kategori</label>
                    <select class="form-control" id="id_kategori" name="id_kategori">
                        <?php foreach ($this->db->get('bl_kategori')->result() as $kategori): ?>
                            <option value="<?= $kategori->id ?>"><?= $kategori->nama_kategori ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group mb-2">
                    <label for="id_tipe_produksi">Tipe Produksi</label>
                    <select class="form-control" id="id_tipe_produksi" name="id_tipe_produksi">
                        <?php foreach ($this->db->get('bl_tipe_produksi')->result() as $tipe): ?>
                            <option value="<?= $tipe->id ?>"><?= $tipe->nama_tipe_produksi ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group mb-2">
                    <label for="is_gudang">Gudang</label>
                    <input type="checkbox" id="is_gudang" name="is_gudang" value="1"> Tampilkan di Gudang
                </div>
                <button type="submit" class="btn btn-primary w-100">Tambah</button>
            </form>
        </div>

        <!-- Tabel -->
        <div class="col-md-9">
            <div class="form-group">
                <label for="limit">Rows per page:</label>
                <select id="limit" name="limit" class="form-control" onchange="window.location.href='?limit='+this.value">
                    <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                    <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>20</option>
                    <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                    <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
                    <option value="all" <?= $limit == 'all' ? 'selected' : '' ?>>All</option>
                </select>
            </div>

        <input type="text" id="search-bar" class="form-control mb-3" placeholder="Cari barang...">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Nama Bahan Baku</th>
                        <th>Kategori</th>
                        <th>Tipe Produksi</th>
                        <th>Gudang</th>
                        <th>Tanggal Update</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="belanja-list">
                    <?php $nomor = $page + 1; ?>
                    <?php foreach ($belanja as $item): ?>
                    <tr>
                        <td><?= $nomor++ ?></td>
                        <td><?= $item['nama_barang'] ?></td>
                        <td><?= $item['nama_bahan_baku'] ?></td>
                        <td><?= $item['nama_kategori'] ?></td>
                        <td><?= $item['nama_tipe_produksi'] ?></td>
                        <td><?= $item['is_gudang'] == 1 ? 'Gudang' : '' ?></td>
                        <td><?= $item['tanggal_update'] ?></td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button class="btn btn-warning btn-sm btn-edit" 
                                        data-id="<?= $item['id'] ?>" 
                                        data-nama_barang="<?= $item['nama_barang'] ?>" 
                                        data-nama_bahan_baku="<?= $item['nama_bahan_baku'] ?>" 
                                        data-id_kategori="<?= $item['id_kategori'] ?>" 
                                        data-id_tipe_produksi="<?= $item['id_tipe_produksi'] ?>">
                                    Edit
                                </button>
                                <button class="btn btn-danger btn-sm btn-delete" data-id="<?= $item['id'] ?>">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?= $pagination ?>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div id="modal-edit" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-edit-belanja">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="form-group">
                        <label for="edit-nama_barang">Nama Barang</label>
                        <input type="text" class="form-control" id="edit-nama_barang" name="nama_barang" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-nama_bahan_baku">Nama Bahan Baku</label>
                        <input type="text" class="form-control" id="edit-nama_bahan_baku" name="nama_bahan_baku">
                    </div>
                    <div class="form-group">
                        <label for="edit-id_kategori">Kategori</label>
                        <select class="form-control" id="edit-id_kategori" name="id_kategori">
                            <?php foreach ($this->db->get('bl_kategori')->result() as $kategori): ?>
                                <option value="<?= $kategori->id ?>"><?= $kategori->nama_kategori ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-id_tipe_produksi">Tipe Produksi</label>
                        <select class="form-control" id="edit-id_tipe_produksi" name="id_tipe_produksi">
                            <?php foreach ($this->db->get('bl_tipe_produksi')->result() as $tipe): ?>
                                <option value="<?= $tipe->id ?>"><?= $tipe->nama_tipe_produksi ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-is_gudang">Gudang</label>
                        <input type="checkbox" id="edit-is_gudang" name="is_gudang" value="1"> Tampilkan di Gudang
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // AJAX untuk Form Tambah
    document.getElementById('form-add-belanja').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('<?= base_url('belanja/add') ?>', {
            method: 'POST',
            body: formData,
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Data berhasil ditambahkan!');
                location.reload();
            } else {
                alert('Gagal menambahkan data.');
            }
        });
    });

    // AJAX untuk Pencarian
 document.getElementById('search-bar').addEventListener('input', function() {
    const keyword = this.value;
    fetch('<?= base_url('belanja/search') ?>?keyword=' + keyword)
    .then(res => res.json())
    .then(data => {
        const list = document.getElementById('belanja-list');
        list.innerHTML = '';
        let nomor = 1; // Reset nomor
        data.forEach(item => {
            list.innerHTML += `
                <tr>
                    <td>${nomor++}</td>
                    <td>${item.nama_barang}</td>
                    <td>${item.nama_bahan_baku}</td>
                    <td>${item.nama_kategori || 'Tidak Diketahui'}</td>
                    <td>${item.nama_tipe_produksi || 'Tidak Diketahui'}</td>
                    <td>${item.is_gudang || 'Tidak Diketahui'}</td>
                    <td>${item.tanggal_update}</td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <button class="btn btn-warning btn-sm btn-edit" 
                                    data-id="${item.id}" 
                                    data-nama_barang="${item.nama_barang}" 
                                    data-nama_bahan_baku="${item.nama_bahan_baku}" 
                                    data-id_kategori="${item.id_kategori}" 
                                    data-id_tipe_produksi="${item.id_tipe_produksi}"
                                    data-is_gudang="${item.is_gudang}">
                                Edit
                            </button>
                            <button class="btn btn-danger btn-sm btn-delete" data-id="${item.id}">Hapus</button>
                        </div>
                    </td>
                </tr>
            `;
        });

        // Re-attach event listeners for edit and delete
        attachEventListeners();
    });
});

function attachEventListeners() {
    // Handle Edit Modal
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const nama_barang = this.getAttribute('data-nama_barang');
            const nama_bahan_baku = this.getAttribute('data-nama_bahan_baku');
            const id_kategori = this.getAttribute('data-id_kategori');
            const id_tipe_produksi = this.getAttribute('data-id_tipe_produksi');

            document.getElementById('edit-id').value = id;
            document.getElementById('edit-nama_barang').value = nama_barang;
            document.getElementById('edit-nama_bahan_baku').value = nama_bahan_baku;
            document.getElementById('edit-id_kategori').value = id_kategori;
            document.getElementById('edit-id_tipe_produksi').value = id_tipe_produksi;
            document.getElementById('edit-is_gudang').checked = is_gudang;
            
            const modal = new bootstrap.Modal(document.getElementById('modal-edit'));
            modal.show();
        });
    });

    // Handle Delete
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            if (confirm('Yakin ingin menghapus data ini?')) {
                fetch('<?= base_url('belanja/delete') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${id}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Data berhasil dihapus!');
                        location.reload();
                    } else {
                        alert('Gagal menghapus data.');
                    }
                });
            }
        });
    });
}

// Attach initial event listeners
attachEventListeners();

    // Handle Form Edit Submit
    document.getElementById('form-edit-belanja').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('<?= base_url('belanja/edit') ?>', {
            method: 'POST',
            body: formData,
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Data berhasil diupdate!');
                location.reload();
            } else {
                alert('Gagal mengupdate data.');
            }
        });
    });
</script>
