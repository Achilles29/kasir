<table>
    <thead>
        <tr>
            <th>Nama Barang</th>
            <th>Merk</th>
            <th>Ukuran</th>
            <th>Unit</th>
            <th>Harga Satuan</th>
            <th>Kuantitas</th>
            <th>Total Unit</th>
            <th>Total Harga</th>
            <th>HPP</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($purchases as $purchase): ?>
        <tr>
            <td><?= $purchase['nama_barang'] ?></td>
            <td><?= $purchase['merk'] ?></td>
            <td><?= $purchase['ukuran'] ?></td>
            <td><?= $purchase['unit'] ?></td>
            <td><?= $purchase['harga_satuan'] ?></td>
            <td><?= $purchase['kuantitas'] ?></td>
            <td><?= $purchase['total_unit'] ?></td>
            <td><?= $purchase['total_harga'] ?></td>
            <td><?= $purchase['hpp'] ?></td>
            <td><?= ucfirst($purchase['status']) ?></td>
            <td>
                <?php if ($purchase['status'] == 'pending'): ?>
                    <a href="<?= base_url('purchasekitchen/delete/' . $purchase['id']) ?>" onclick="return confirm('Hapus data ini?')">Hapus</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
