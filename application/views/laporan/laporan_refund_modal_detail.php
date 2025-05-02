  <!-- Bootstrap 5 JS Bundle (wajib untuk tombol X modal bekerja) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <style>
.text-maroon {
    color: maroon;
}

.table-refund th {
    background-color: #800000;
    color: white;
    text-align: center;
}

.refund-extra {
    padding-left: 25px;
    font-style: italic;
    color: #555;
}

.info-transaksi {
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    animation: fadeIn 0.5s ease;
}

.info-transaksi p {
    margin-bottom: 5px;
}

.modal-title {
    font-weight: 600;
}

.table-refund td,
.table-refund th {
    vertical-align: middle;
}

.table-refund tfoot {
    background-color: #800000;
    color: white;
    font-weight: bold;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
  </style>

  <div class="info-transaksi">
      <h5 class="fw-bold text-maroon mb-3"><i class="fas fa-receipt"></i> Info Transaksi</h5>
      <p><strong>No Transaksi:</strong> <?= $refund[0]->no_transaksi ?></p>
      <p><strong>Customer:</strong> <?= $refund[0]->customer ?></p>
      <p><strong>Meja:</strong> <?= $refund[0]->nomor_meja ?></p>
      <p><strong>Waktu:</strong> <?= date('d/m/Y H:i', strtotime($refund[0]->waktu_refund)) ?></p>
  </div>

  <h5 class="fw-bold text-maroon mb-3"><i class="fas fa-box-open"></i> Detail Produk</h5>
  <table class="table table-bordered table-refund">
      <thead>
          <tr>
              <th>Produk</th>
              <th class="text-center">Jumlah</th>
              <th class="text-end">Harga</th>
              <th class="text-end">Subtotal</th>
          </tr>
      </thead>
      <tbody>
          <?php 
        $total = 0; 
        foreach ($refund as $r): 
        ?>
          <?php if (empty($r->nama_extra)): ?>
          <tr>
              <td><strong><?= $r->nama_produk ?></strong></td>
              <td class="text-center"><?= $r->jumlah ?></td>
              <td class="text-end">Rp <?= number_format($r->harga, 0, ',', '.') ?></td>
              <td class="text-end">Rp <?= number_format($r->harga * $r->jumlah, 0, ',', '.') ?></td>
          </tr>
          <?php $total += ($r->harga * $r->jumlah); ?>
          <?php else: ?>
          <tr>
              <td class="refund-extra">+ <?= $r->nama_extra ?></td>
              <td class="text-center"><?= $r->jumlah ?></td>
              <td class="text-end">Rp <?= number_format($r->harga, 0, ',', '.') ?></td>
              <td class="text-end">Rp <?= number_format($r->harga * $r->jumlah, 0, ',', '.') ?></td>
          </tr>
          <?php $total += ($r->harga * $r->jumlah); ?>
          <?php endif; ?>
          <?php endforeach; ?>
      </tbody>
      <tfoot>
          <tr>
              <td colspan="3" class="text-end">Total Refund</td>
              <td class="text-end">Rp <?= number_format($total, 0, ',', '.') ?></td>
          </tr>
      </tfoot>
  </table>