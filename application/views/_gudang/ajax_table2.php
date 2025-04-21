<?php 
$no = $start + 1;
foreach ($gudang as $item): ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $item['kategori'] ?></td>
    <td><?= $item['nama_barang'] ?></td>
    <td><?= $item['nama_bahan_baku'] ?></td>
    <td><?= $item['tipe'] ?></td>
    <td><?= $item['stok_awal'] ?? 0 ?></td>
    <td><?= $item['stok_masuk'] ?? 0 ?></td>
    <td><?= $item['stok_keluar'] ?? 0 ?></td>
    <td><?= $item['stok_terbuang'] ?? 0 ?></td>
    <td><?= $item['stok_penyesuaian'] ?? 0 ?></td>
    <td><?= $item['stok_akhir'] ?? 0 ?></td>
    <td><?= $item['stok_akhir'] * $item['ukuran'] ?></td>
    <td><?= number_format($item['stok_akhir'] * $item['harga'], 2) ?></td>
</tr>
<?php endforeach; ?>
