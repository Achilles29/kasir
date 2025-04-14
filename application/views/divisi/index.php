<div class="container">
    <h2>Daftar Divisi</h2>
    <a href="<?= site_url('divisi/tambah'); ?>" class="btn btn-success">+ Tambah Divisi</a>
<table class="table table-striped">
    <thead class="text-center">
        <tr>
            <th>Departemen</th>
            <th>Urutan Tampilan</th>
            <th>Jumlah Kategori</th>
            <th class="text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($divisi as $d): ?>
        <tr>
            <td class="text-left"> <?= htmlspecialchars($d['nama_divisi']); ?> </td>
            <td class="text-center"> <?= htmlspecialchars($d['urutan_tampilan']); ?> </td>
            <td class="text-center"> <?= htmlspecialchars($d['jumlah_kategori']); ?> </td>
            <td class="text-center">
                <a href="<?= site_url('divisi/edit/'.$d['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="<?= site_url('divisi/hapus/'.$d['id']); ?>" class="btn btn-danger btn-sm">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>