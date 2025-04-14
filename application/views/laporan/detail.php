<h3>Faktur: <?= $transaksi['no_transaksi'] ?></h3>
<p>Tanggal: <?= $transaksi['waktu_order'] ?></p>
<p>Total: Rp <?= number_format($transaksi['total_penjualan'], 0, ',', '.') ?></p>

<h4>Detail Produk</h4>
<table class="table">
  <thead>
    <tr>
      <th>Produk</th><th>Jumlah</th><th>Harga</th><th>Subtotal</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($detail as $d): ?>
      <tr>
        <td><?= $d['nama_produk'] ?></td>
        <td><?= $d['jumlah'] ?></td>
        <td>Rp <?= number_format($d['harga'], 0, ',', '.') ?></td>
        <td>Rp <?= number_format($d['subtotal'], 0, ',', '.') ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<h4>Pembayaran</h4>
<ul>
  <?php foreach ($pembayaran as $p): ?>
    <li><?= $p['metode_pembayaran'] ?> - Rp <?= number_format($p['jumlah'], 0, ',', '.') ?></li>
  <?php endforeach; ?>
</ul>

<?php if ($refund): ?>
  <div class="alert alert-warning">Refund: Rp <?= number_format($refund['subtotal'], 0, ',', '.') ?> (<?= $refund['alasan'] ?>)</div>
<?php endif; ?>

<?php if ($void): ?>
  <div class="alert alert-danger">Void: Rp <?= number_format($void['subtotal'], 0, ',', '.') ?> (<?= $void['alasan'] ?>)</div>
<?php endif; ?>
