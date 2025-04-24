<h3>NOTIFIKASI PRODUK REFUND</h3>
<p>Kode Refund: <?= $refund[0]->kode_refund ?></p>
<p>No Transaksi: <?= $refund[0]->no_transaksi ?></p>
<hr>
<?php
$grouped = [];
foreach ($refund as $item) {
    $grouped[$item->nama_divisi][] = $item;
}
foreach ($grouped as $divisi => $items):
?>
<h4>Divisi: <?= strtoupper($divisi) ?></h4>
<table border="1" cellpadding="4" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Produk</th>
            <th>Extra</th>
            <th>Jumlah</th>
            <th>Alasan</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $i): ?>
        <tr>
            <td><?= $i->nama_produk ?></td>
            <td><?= $i->nama_extra ?? '-' ?></td>
            <td><?= $i->jumlah ?></td>
            <td><?= $i->alasan ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<hr>
<?php endforeach; ?>
<script>
window.print();
</script>