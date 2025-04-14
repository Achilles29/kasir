<h2><?= $title ?></h2>
<a href="<?= base_url('purchase/add') ?>">Tambah Purchase</a>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Tanggal</th>
        <th>Jenis Pengeluaran</th>
        <th>Kuantitas</th>
        <th>Total Unit</th>
        <th>Total Harga</th>
        <th>HPP</th>
        <th>Aksi</th>
    </tr>
    <?php foreach ($purchases as $purchase): ?>
    <tr>
        <td><?= $purchase['id'] ?></td>
        <td><?= $purchase['tanggal_pembelian'] ?></td>
        <td><?= $purchase['jenis_pengeluaran'] ?></td>
        <td><?= $purchase['kuantitas'] ?></td>
        <td><?= $purchase['total_unit'] ?></td>
        <td><?= $purchase['total_harga'] ?></td>
        <td><?= $purchase['hpp'] ?></td>
        <td><a href="<?= base_url('purchase/edit/' . $purchase['id']) ?>">Edit</a> | <a href="<?= base_url('purchase/delete/' . $purchase['id']) ?>">Hapus</a></td>
    </tr>
    <?php endforeach; ?>
</table>
