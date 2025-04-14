<style>
.container-fluid {
    padding: 15px;
}

.table {
    font-size: 14px;
}

.table thead th {
    text-align: center;
    vertical-align: middle;
}

.table tbody td {
    text-align: center;
    vertical-align: middle;
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

#list-nama-barang {
    position: absolute;
    z-index: 1000;
    width: 100%;
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 4px;
}

#list-nama-barang .list-group-item {
    cursor: pointer;
}
</style>

<div class="container-fluid">
    <h2><?= $title ?></h2>
    <div class="row">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
        <?php endif; ?>
        
        <!-- Form Input -->
        <div class="col-md-2">
            <form id="form-add-purchase" method="post" action="<?= base_url('persediaanawal/add') ?>" class="mb-3">
                <input type="hidden" id="bl_db_belanja_id" name="bl_db_belanja_id">
                <input type="hidden" id="bl_db_purchase_id" name="bl_db_purchase_id">
                <div class="form-group">
                    <label for="cari-nama-barang">Cari Nama Barang</label>
                    <input type="text" id="cari-nama-barang" class="form-control" placeholder="Cari nama barang...">
                    <ul id="list-nama-barang" class="list-group" style="max-height: 200px; overflow-y: auto;"></ul>
                </div>
                <div class="form-group">
                    <label for="nama_barang">Nama Barang</label>
                    <input type="text" id="nama_barang" name="nama_barang" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label for="nama_bahan_baku">Nama Bahan Baku</label>
                    <input type="text" id="nama_bahan_baku" name="nama_bahan_baku" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label for="merk">Merk</label>
                    <input type="text" id="merk" name="merk" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label for="ukuran">Ukuran</label>
                    <input type="text" id="ukuran" name="ukuran" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label for="harga_satuan">Harga Satuan</label>
                    <input type="text" id="harga_satuan" name="harga_satuan" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label for="kuantitas">Kuantitas</label>
                    <input type="number" id="kuantitas" name="kuantitas" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>

        </div>

        <!-- Tabel -->
        <div class="col-md-8">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Nama Bahan Baku</th>
                        <th>Kategori</th>
                        <th>Tipe Produksi</th>
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
                    <?php $no = 1; foreach ($persediaan_awal as $item): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $item['nama_barang'] ?></td>
                            <td><?= $item['nama_bahan_baku'] ?></td>
                            <td><?= $item['kategori'] ?></td>
                            <td><?= $item['tipe_produksi'] ?></td>
                            <td><?= $item['merk'] ?></td>
                            <td><?= $item['ukuran'] ?></td>
                            <td><?= number_format($item['harga_satuan'], 2) ?></td>
                            <td><?= $item['kuantitas'] ?></td>
                            <td><?= $item['total_unit'] ?></td>
                            <td><?= number_format($item['total_harga'], 2) ?></td>
                            <td><?= number_format($item['hpp'], 2) ?></td>
                            <td>
                                <a href="<?= base_url('persediaanawal/delete/' . $item['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?= $pagination ?>
        </div>
    </div>
</div>

<script>
document.getElementById('cari-nama-barang').addEventListener('input', function () {
    const keyword = this.value.trim();

    if (keyword.length > 2) {
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



</script>
