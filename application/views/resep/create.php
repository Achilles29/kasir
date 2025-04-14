<div class="container mt-4">
  <h4 class="mb-4 fw-bold">Input Resep</h4>

  <!-- Tabs -->
  <ul class="nav nav-tabs mb-3" id="resepTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="produk-tab" data-bs-toggle="tab" data-bs-target="#resep-produk" type="button" role="tab">Resep Produk</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="base-tab" data-bs-toggle="tab" data-bs-target="#resep-base" type="button" role="tab">Resep Base</button>
    </li>
  </ul>

  <div class="tab-content" id="resepTabContent">

    <!-- RESEP PRODUK -->
    <div class="tab-pane fade show active" id="resep-produk" role="tabpanel">
      <form action="<?= base_url('resep/store_produk') ?>" method="POST" class="card card-body shadow-sm mb-4">
        <h6 class="mb-3 fw-bold">Resep Produk</h6>

        <div class="row mb-3">
          <div class="col-md-6">
            <label>Produk</label>
            <select name="produk_id" class="form-select" required>
              <option value="">Pilih Produk</option>
              <?php foreach ($produk as $p): ?>
                <option value="<?= $p['id'] ?>"><?= $p['nama_produk'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label>Bahan Baku</label>
            <select name="bahan_id" class="form-select" required>
              <option value="">Pilih Bahan</option>
              <?php foreach ($bahan as $b): ?>
                <option value="<?= $b['id'] ?>"><?= $b['nama_barang'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-4">
            <label>Jumlah</label>
            <input type="number" step="0.01" name="jumlah" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label>Satuan</label>
            <input type="text" name="satuan" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label>HPP (Opsional)</label>
            <input type="number" step="0.01" name="hpp" class="form-control">
          </div>
        </div>

        <button type="submit" class="btn btn-success">Simpan Resep Produk</button>
      </form>
    </div>

    <!-- RESEP BASE -->
    <div class="tab-pane fade" id="resep-base" role="tabpanel">
      <form action="<?= base_url('resep/store_base') ?>" method="POST" class="card card-body shadow-sm mb-4">
        <h6 class="mb-3 fw-bold">Resep Base</h6>

        <div class="row mb-3">
          <div class="col-md-6">
            <label>Base</label>
            <select name="base_id" class="form-select" required>
              <option value="">Pilih Base</option>
              <?php foreach ($produk_base as $b): ?>
                <option value="<?= $b['id'] ?>"><?= $b['nama_produk'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label>Bahan (Bahan Baku atau Base)</label>
            <select name="bahan_id" class="form-select" required>
              <option value="">Pilih Bahan</option>
              <?php foreach ($bahan_semua as $b): ?>
                <option value="<?= $b['id'] ?>"><?= $b['nama'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-4">
            <label>Jumlah</label>
            <input type="number" step="0.01" name="jumlah" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label>Satuan</label>
            <input type="text" name="satuan" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label>HPP (Opsional)</label>
            <input type="number" step="0.01" name="hpp" class="form-control">
          </div>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Resep Base</button>
      </form>
    </div>
  </div>
</div>
