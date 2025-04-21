<div class="container">
    <h2>Daftar Kategori</h2>
    <a href="<?= site_url('kategori/tambah'); ?>" class="btn btn-success">+ Tambah Kategori</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Nama Kategori</th>
            <th>Urutan</th>
            <th>Jumlah Produk</th>
            <th>Departemen</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($kategori as $k): ?>
            <tr>
                <td><?= $k['nama_kategori']; ?></td>
                <td><?= $k['urutan']; ?></td>
                <td><?= $k['jumlah_produk']; ?> item</td>
                <td><?= $k['nama_divisi']; ?></td>
                <td><?= $k['status'] == 1 ? '<span class="badge badge-success">Tampil</span>' : '<span class="badge badge-danger">Tidak Tampil</span>'; ?></td>
                <td>
                    <a href="<?= site_url('kategori/edit/'.$k['id']); ?>" class="btn btn-warning">Edit</a>
                    <a href="<?= site_url('kategori/hapus/'.$k['id']); ?>" class="btn btn-danger" onclick="return confirm('Hapus kategori ini?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>