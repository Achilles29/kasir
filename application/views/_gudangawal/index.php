<div class="container-fluid">
    <h2><?= $title ?></h2>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php elseif ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <!-- Form Input -->
    <form method="post" action="<?= base_url('gudangawal/add') ?>" class="mb-3">
        <div class="form-row">
            <div class="col-md-3">
                <label for="tanggal">Tanggal</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="col-md-3">
                <label for="cari-barang">Cari Barang</label>
                <input type="text" id="cari-barang" class="form-control" placeholder="Ketik nama barang...">
                <ul id="list-barang" class="list-group" style="max-height: 200px; overflow-y: auto;"></ul>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-2">
                <label for="bl_db_belanja_id">ID Belanja</label>
                <input type="text" class="form-control" id="bl_db_belanja_id" name="bl_db_belanja_id" readonly>
            </div>
            <div class="col-md-2">
                <label for="bl_db_purchase_id">ID Purchase</label>
                <input type="text" class="form-control" id="bl_db_purchase_id" name="bl_db_purchase_id" readonly>
            </div>
            <div class="col-md-3">
                <label for="nama_barang">Nama Barang</label>
                <input type="text" class="form-control" id="nama_barang" name="nama_barang" readonly>
            </div>
            <div class="col-md-3">
                <label for="nama_bahan_baku">Nama Bahan Baku</label>
                <input type="text" class="form-control" id="nama_bahan_baku" name="nama_bahan_baku" readonly>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-2">
                <label for="merk">Merk</label>
                <input type="text" class="form-control" id="merk" name="merk" readonly>
            </div>
            <div class="col-md-2">
                <label for="ukuran">Ukuran</label>
                <input type="text" class="form-control" id="ukuran" name="ukuran" readonly>
            </div>
            <div class="col-md-2">
                <label for="harga_satuan">Harga Satuan</label>
                <input type="text" class="form-control" id="harga_satuan" name="harga_satuan" readonly>
            </div>
            <div class="col-md-2">
                <label for="kuantitas">Kuantitas</label>
                <input type="number" class="form-control" id="kuantitas" name="kuantitas" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
    </form>

<div class="row">
    <div class="col-md-3">
        <form method="get" action="<?= base_url('gudangawal/index') ?>" class="form-inline">
            <label for="per_page" class="mr-2">Baris per Halaman:</label>
            <select id="per_page" name="per_page" class="form-control" onchange="this.form.submit()">
                <option value="10" <?= isset($per_page) && $per_page == 10 ? 'selected' : '' ?>>10</option>
                <option value="20" <?= isset($per_page) && $per_page == 20 ? 'selected' : '' ?>>20</option>
                <option value="50" <?= isset($per_page) && $per_page == 50 ? 'selected' : '' ?>>50</option>
                <option value="100" <?= isset($per_page) && $per_page == 100 ? 'selected' : '' ?>>100</option>
            </select>
        </form>
    </div>
</div>

        <!-- Tabel Data -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>Nama Bahan Baku</th>
                <th>Merk</th>
                <th>Ukuran</th>
                <th>Harga Satuan</th>
                <th>Kuantitas</th>
                <th>Total Unit</th>
                <th>Total Harga</th>
                <th>HPP</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($gudang_awal as $item): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= date('d-m-Y', strtotime($item['tanggal'])) ?></td> <!-- Kolom Tanggal -->
                    <td><?= $item['nama_barang'] ?></td>
                    <td><?= $item['nama_bahan_baku'] ?></td>
                    <td><?= $item['merk'] ?></td>
                    <td><?= $item['ukuran'] ?></td>
                    <td><?= number_format($item['harga_satuan'], 2) ?></td>
                    <td><?= $item['kuantitas'] ?></td>
                    <td><?= $item['total_unit'] ?></td>
                    <td><?= number_format($item['total_harga'], 2) ?></td>
                    <td><?= number_format($item['hpp'], 2) ?></td>
        <td>
                                <a href="<?= base_url('gudangawal/edit/' . $item['id']) ?>" class="btn btn-warning btn-sm">Edit</a> <!-- Tombol Edit -->
                                <a href="<?= base_url('gudangawal/delete/' . $item['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                            </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

  <div class="row">
      <div class="col-md-12">
          <?= $pagination ?>
      </div>
  </div>
</div>

<script>
document.getElementById('cari-barang').addEventListener('input', function () {
    const keyword = this.value.trim();

    if (keyword.length > 0) {
        fetch('<?= base_url('gudangawal/search') ?>?keyword=' + encodeURIComponent(keyword))
            .then(response => response.json())
            .then(data => {
                const list = document.getElementById('list-barang');
                list.innerHTML = ''; // Kosongkan list sebelum menampilkan hasil

                if (data.length > 0) {
                    data.forEach(item => {
                        const preview = `
                            ${item.nama_barang || ''} - ${item.merk || '-'} - ${item.ukuran || '-'} - Rp ${item.harga_satuan || 0}
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
        document.getElementById('list-barang').innerHTML = ''; // Kosongkan list jika keyword terlalu pendek
    }
});

function selectBarang(item) {
    document.getElementById('bl_db_belanja_id').value = item.bl_db_belanja_id;
    document.getElementById('bl_db_purchase_id').value = item.purchase_id;
    document.getElementById('nama_barang').value = item.nama_barang;
    document.getElementById('nama_bahan_baku').value = item.nama_bahan_baku;
    document.getElementById('merk').value = item.merk || '';
    document.getElementById('ukuran').value = item.ukuran || '';
    document.getElementById('harga_satuan').value = item.harga_satuan || 0;
    document.getElementById('list-barang').innerHTML = ''; // Kosongkan list setelah memilih
}

</script>
