<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Manajemen Resep</h4>
    <a href="<?= base_url('resep/create') ?>" class="btn btn-success">
      <i class="fas fa-plus me-1"></i> Tambah Resep
    </a>
  </div>

  <!-- NAV TABS -->
  <ul class="nav nav-pills mb-3" id="resepTabs" role="tablist">
    <li class="nav-item">
      <button class="nav-link active" id="produk-tab" data-bs-toggle="pill" data-bs-target="#produkResep" type="button">
        Resep Produk
      </button>
    </li>
    <li class="nav-item">
      <button class="nav-link" id="base-tab" data-bs-toggle="pill" data-bs-target="#baseResep" type="button">
        Resep Base
      </button>
    </li>
  </ul>

  <div class="tab-content">
    <!-- TAB RESEP PRODUK -->
    <div class="tab-pane fade show active" id="produkResep" role="tabpanel">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white fw-bold">Resep Produk</div>
        <div class="card-body table-responsive">
          <table class="table table-hover">
            <thead class="table-light">
              <tr>
                <th>Produk</th>
                <th>Bahan / Base</th>
                <th>Jumlah</th>
                <th>Satuan</th>
                <th>HPP</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($resep_produk as $row): ?>
              <tr>
                <td><?= $row['produk'] ?></td>
                <td><?= $row['bahan'] ?></td>
                <td><?= $row['jumlah'] ?></td>
                <td><?= $row['satuan'] ?></td>
                <td>Rp <?= number_format($row['hpp'], 0, ',', '.') ?></td>
              </tr>
              <?php endforeach ?>
              <?php if (empty($resep_produk)): ?>
              <tr><td colspan="5" class="text-center text-muted">Belum ada data resep.</td></tr>
              <?php endif ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- TAB RESEP BASE -->
    <div class="tab-pane fade" id="baseResep" role="tabpanel">
      <div class="card shadow-sm mt-3">
        <div class="card-header bg-warning fw-bold">Resep Base</div>
        <div class="card-body table-responsive">
          <table class="table table-hover">
            <thead class="table-light">
              <tr>
                <th>Base</th>
                <th>Bahan Baku</th>
                <th>Jumlah</th>
                <th>Satuan</th>
                <th>HPP</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($resep_base as $row): ?>
              <tr>
                <td><?= $row['produk'] ?></td>
                <td><?= $row['bahan'] ?></td>
                <td><?= $row['jumlah'] ?></td>
                <td><?= $row['satuan'] ?></td>
                <td>Rp <?= number_format($row['hpp'], 0, ',', '.') ?></td>
              </tr>
              <?php endforeach ?>
              <?php if (empty($resep_base)): ?>
              <tr><td colspan="5" class="text-center text-muted">Belum ada data resep base.</td></tr>
              <?php endif ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
