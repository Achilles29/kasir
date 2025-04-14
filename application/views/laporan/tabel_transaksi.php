<?php
  // Perhitungan Ringkasan
  $total_penjualan = 0;
  $total_pembayaran = 0;
  $total_piutang = 0;
  $penjualan_bersih = 0; // Placeholder, bisa diganti dengan rumus lain

  foreach ($transaksi as $t) {
    $total_penjualan += $t['total_pembayaran'] !== null ? $t['total_pembayaran'] : $t['total_penjualan'];

    if ($t['total_pembayaran'] !== null) {
      $total_pembayaran += $t['total_pembayaran'];
    } else {
      $total_piutang += $t['total_penjualan'];
    }
  }
?>
<div class="row mb-4 g-3">
  <div class="col-md-2">
    <div class="card shadow-sm border-0 border-bottom border-success border-3">
      <div class="card-body text-center">
        <div class="fw-bold text-muted small">Total Penjualan</div>
        <div class="text-success fs-6 fw-bold">
          Rp <?= number_format($total_penjualan, 0, ',', '.') ?>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card shadow-sm border-0 border-bottom border-primary border-3">
      <div class="card-body text-center">
        <div class="fw-bold text-muted small">Total Transaksi</div>
        <div class="text-primary fs-6 fw-bold"><?= count($transaksi) ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card shadow-sm border-0 border-bottom border-info border-3">
      <div class="card-body text-center">
        <div class="fw-bold text-muted small">Penjualan Bersih <i class="fas fa-info-circle"></i></div>
        <div class="text-info fs-6 fw-bold">Rp <?= number_format($penjualan_bersih, 0, ',', '.') ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card shadow-sm border-0 border-bottom border-purple border-3">
      <div class="card-body text-center">
        <div class="fw-bold text-muted small">Total Pembayaran</div>
        <div class="text-purple fs-6 fw-bold">Rp <?= number_format($total_pembayaran, 0, ',', '.') ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card shadow-sm border-0 border-bottom border-warning border-3">
      <div class="card-body text-center">
        <div class="fw-bold text-muted small">Total Piutang</div>
        <div class="text-warning fs-6 fw-bold">Rp <?= number_format($total_piutang, 0, ',', '.') ?></div>
      </div>
    </div>
  </div>
</div>


<!-- TABEL TRANSAKSI -->
<div class="table-responsive">
  <table class="table table-hover align-middle">
    <thead class="table-light text-center">
      <tr>
        <th>NO TRANSAKSI</th>
        <!-- <th>TANGGAL</th> -->
        <th>WAKTU ORDER</th>
        <th>WAKTU BAYAR</th>
        <th>JENIS ORDER</th>
        <th>TOTAL PENJUALAN</th>
        <th>AKSI</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($transaksi as $t): ?>
        <tr class="text-center">
          <td><?= $t['no_transaksi'] ?></td>
          <!-- <td><?= date('d/m/Y', strtotime($t['tanggal'])) ?></td> -->
          <td><?= $t['waktu_order'] ?: '-' ?></td>
          <td><?= $t['waktu_bayar'] ?: '-' ?></td>
          <td><?= $t['jenis_order'] ?></td>
          <td>Rp <?= number_format($t['total_penjualan'], 0, ',', '.') ?></td>
          <td>
            <a href="<?= base_url('laporan/detail/' . $t['id']) ?>" class="btn btn-sm btn-outline-primary">
              <i class="fas fa-eye"></i> Detail
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($transaksi)): ?>
        <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada data transaksi.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
