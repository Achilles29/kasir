<div class="container">
  <h4 class="mb-4"><?= $title ?></h4>
  <a href="<?= base_url('stok/tambah') ?>" class="btn btn-success mb-3"><i class="fas fa-plus"></i> Penyesuaian Stok</a>
    <a href="<?= base_url('stok/log') ?>" class="btn btn-secondary mb-3">
    <i class="fas fa-history"></i> Lihat Log Perubahan
    </a>

  <table class="table table-bordered">
    <thead class="table-light">
      <tr>
        <th>Divisi</th>
        <th>Nama Bahan</th>
        <th>Stok Awal</th>
        <th>Masuk</th>
        <th>Keluar</th>
        <th>Penyesuaian</th>
        <th>Sisa</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($stok as $s): ?>
        <tr>
          <td><?= $s['nama_divisi'] ?></td>
          <td><?= $s['nama_barang'] ?></td>
          <td><?= $s['stok_awal'] ?></td>
          <td><?= $s['stok_masuk'] ?></td>
          <td><?= $s['stok_keluar'] ?></td>
          <td><?= $s['stok_penyesuaian'] ?></td>
          <td><?= $s['stok_sisa'] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
