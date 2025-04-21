<style>
    .container-fluid {
    padding: 15px;
}

.table {
    font-size: 14px;
}

.table thead th {
    text-align: center;
}

.table tbody td .btn-group {
    display: flex;
    justify-content: center;
    gap: 5px;
}

.table tbody td .btn {
    width: auto;
}

.form-group {
    margin-bottom: 15px;
}

h2 {
    margin-bottom: 20px;
    font-size: 24px;
    font-weight: bold;
}

</style>

<div class="container-fluid">
    <h2><?= $title ?></h2>
    <div class="row">

        <!-- Form Input -->
            <div class="col-md-2">
                <form id="form-add-purchase" method="post" action="<?= base_url('dbpurchase/add') ?>">
                    <div class="form-group">
                        <label for="cari-nama-barang">Cari Nama Barang</label>
                        <input type="text" id="cari-nama-barang" class="form-control" placeholder="Cari nama barang...">
                        <ul id="list-nama-barang" class="list-group" style="max-height: 200px; overflow-y: auto;"></ul>
                    </div>

                    <div class="form-group">
                        <label for="nama_barang">Nama Barang</label>
                        <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
                    </div>
                    <div class="form-group">
                        <label for="nama_bahan_baku">Nama Bahan Baku</label>
                        <input type="text" class="form-control" id="nama_bahan_baku" name="nama_bahan_baku">
                    </div>

                    <div class="form-group">
                        <label for="id_kategori">Kategori</label>
                        <select class="form-control" id="id_kategori" name="id_kategori">
                            <?php foreach ($this->db->get('bl_kategori')->result() as $kategori): ?>
                                <option value="<?= $kategori->id ?>"><?= $kategori->nama_kategori ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="id_tipe_produksi">Tipe Produksi</label>
                        <select class="form-control" id="id_tipe_produksi" name="id_tipe_produksi">
                            <?php foreach ($this->db->get('bl_tipe_produksi')->result() as $tipe): ?>
                                <option value="<?= $tipe->id ?>"><?= $tipe->nama_tipe_produksi ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="merk">Merk</label>
                        <input type="text" class="form-control" id="merk" name="merk">
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="ukuran">Ukuran</label>
                        <input type="number" class="form-control" id="ukuran" name="ukuran" required>
                    </div>
                    <div class="form-group">
                        <label for="unit">Unit</label>
                        <input type="text" class="form-control" id="unit" name="unit">
                    </div>
                    <div class="form-group">
                        <label for="pack">Pack</label>
                        <input type="text" class="form-control" id="pack" name="pack">
                    </div>
                    <div class="form-group">
                        <label for="harga_satuan">Harga Satuan</label>
                        <input type="number" class="form-control" id="harga_satuan" name="harga_satuan" required>
                    </div>
                    <div class="form-group">
                        <label for="hpp">HPP</label>
                        <input type="number" class="form-control" id="hpp" name="hpp" readonly>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>

            <!-- Tabel -->
        <div class="col-md-9">


                <!-- Filter Jumlah Baris -->
            <form method="get" action="<?= base_url('dbpurchase/index') ?>" class="form-inline mb-3">
                <label for="per_page" class="mr-2">Baris per Halaman:</label>
                <select id="per_page" name="per_page" class="form-control form-control-sm mr-3" onchange="this.form.submit()">
                    <option value="20" <?= $per_page == 20 ? 'selected' : '' ?>>20</option>
                    <option value="50" <?= $per_page == 50 ? 'selected' : '' ?>>50</option>
                    <option value="100" <?= $per_page == 100 ? 'selected' : '' ?>>100</option>
                    <option value="200" <?= $per_page == 200 ? 'selected' : '' ?>>200</option>
                    <option value="500" <?= $per_page == 500 ? 'selected' : '' ?>>500</option>
                </select>
            </form>
                <div class="form-group">
                    <label for="search-tabel">Cari Barang di Tabel:</label>
                    <input type="text" id="search-tabel" class="form-control" placeholder="Masukkan nama barang...">
                </div>
                <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
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
                                <th>HPP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="purchase-list">
                            <?php $nomor = isset($page) ? $page + 1 : 1; ?>
                            <?php foreach ($db_purchase as $item): ?>
                                <tr>
                                    <td><?= $nomor++ ?></td>
                                    <td><?= $item['nama_barang'] ?></td>
                                    <td><?= $item['nama_bahan_baku'] ?></td>
                                    <td><?= $item['kategori'] ?></td>
                                    <td><?= $item['tipe_produksi'] ?></td>
                                    <td><?= $item['merk'] ?></td>
                                    <td><?= $item['keterangan'] ?></td>
                                    <td><?= $item['ukuran'] ?></td>
                                    <td><?= $item['unit'] ?></td>
                                    <td><?= $item['pack'] ?></td>
                                    <td><?= $item['harga_satuan'] ?></td>
                                    <td><?= $item['hpp'] ?></td>
                                    <td>
                                        <a href="<?= base_url('dbpurchase/edit/' . $item['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="<?= base_url('dbpurchase/delete/' . $item['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
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
    </div>
</div>



<script>
    // Pencarian Barang
// Pencarian Barang
document.getElementById('cari-nama-barang').addEventListener('input', function () {
    const keyword = this.value;
    fetch('<?= base_url('dbpurchase/search_barang') ?>?keyword=' + keyword)
        .then(res => res.json())
        .then(data => {
            const list = document.getElementById('list-nama-barang');
            list.innerHTML = '';
            data.forEach(item => {
                list.innerHTML += `
                    <li class="list-group-item">
                        ${item.nama_barang}
                        <button class="btn btn-primary btn-sm float-right" 
                            onclick="selectBarang('${item.nama_barang}', '${item.nama_bahan_baku}', '${item.id_kategori}', '${item.id_tipe_produksi}')">Pilih</button>
                    </li>`;
            });
        })
        .catch(error => console.error('Error fetching barang:', error));
});

    document.getElementById('search-tabel').addEventListener('input', function () {
        const keyword = this.value.toLowerCase();
        const rows = document.querySelectorAll('#purchase-list tr');

        rows.forEach(row => {
            const namaBarang = row.children[1].textContent.toLowerCase();
            if (namaBarang.includes(keyword)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
// Fungsi untuk memilih barang
function selectBarang(nama_barang, nama_bahan_baku, id_kategori, id_tipe_produksi) {
    document.getElementById('nama_barang').value = nama_barang;
    document.getElementById('nama_bahan_baku').value = nama_bahan_baku;
    document.getElementById('id_kategori').value = id_kategori;
    document.getElementById('id_tipe_produksi').value = id_tipe_produksi;
    document.getElementById('list-nama-barang').innerHTML = ''; // Bersihkan list
}

    // Simpan Data
    document.getElementById('btn-simpan').addEventListener('click', function () {
    const formData = new FormData(document.getElementById('form-add-purchase'));

    fetch('<?= base_url('dbpurchase/add') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data); // Debugging respons server
        if (data.status === 'success') {
            alert('Data berhasil disimpan!');
            location.reload();
        } else {
            alert('Gagal menyimpan data.');
        }
    })
    .catch(error => console.error('Error:', error)); // Debugging error
});

document.addEventListener('DOMContentLoaded', function () {
    // Tombol Edit
document.querySelectorAll('.btn-edit').forEach(button => {
    button.addEventListener('click', function () {
        const id = this.dataset.id;

        fetch('<?= base_url('dbpurchase/get_by_id') ?>?id=' + id)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit-id').value = data.id;
                document.getElementById('edit-nama_barang').value = data.nama_barang;
                document.getElementById('edit-nama_bahan_baku').value = data.nama_bahan_baku;
                document.getElementById('edit-id_kategori').value = data.id_kategori;
                document.getElementById('edit-id_tipe_produksi').value = data.id_tipe_produksi;
                document.getElementById('edit-merk').value = data.merk;
                document.getElementById('edit-keterangan').value = data.keterangan;
                document.getElementById('edit-ukuran').value = data.ukuran;
                document.getElementById('edit-unit').value = data.unit;
                document.getElementById('edit-pack').value = data.pack;
                document.getElementById('edit-harga_satuan').value = data.harga_satuan;

                const modalEdit = new bootstrap.Modal(document.getElementById('modalEdit'));
                modalEdit.show();
            });
    });
});
    // Tombol Hapus
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;

            if (confirm('Yakin ingin menghapus data ini?')) {
                fetch('<?= base_url('dbpurchase/delete') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${id}`,
                })
                    .then(response => response.json())
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
});

    // Submit form edit
    document.getElementById('form-edit-purchase').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('<?= base_url('dbpurchase/edit') ?>', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Data berhasil diperbarui!');
                    location.reload();
                } else {
                    alert('Gagal memperbarui data.');
                }
            });
    });

document.addEventListener('DOMContentLoaded', function () {
    // Edit Button
    document.querySelector('#purchase-list').addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-edit')) {
            const id = e.target.dataset.id;
            openEditModal(id);
        }
    });

    // Delete Button
    document.querySelector('#purchase-list').addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-delete')) {
            const id = e.target.dataset.id;
            confirmDelete(id);
        }
    });
});


</script>
