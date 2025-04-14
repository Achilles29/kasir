<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
    <h4 class="mb-4"><?= $title ?></h4>

    <form method="post" action="<?= base_url('printer/simpan') ?>" class="border rounded p-4 mb-4 bg-light shadow-sm">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label><strong>Divisi</strong> (opsional)</label>
                <select name="divisi" class="form-control">
                    <option value="">- Tidak Ada Divisi -</option>
                    <?php foreach ($divisi as $d): ?>
                    <option value="<?= $d['id'] ?>"><?= $d['nama_divisi'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group col-md-3">
                <label><strong>Lokasi Printer</strong></label>
                <select name="lokasi_printer" class="form-control" required>
                    <option value="">-- Pilih Lokasi --</option>
                    <?php foreach (['KASIR', 'BAR', 'KITCHEN', 'CHECKER'] as $opt): ?>
                    <option value="<?= $opt ?>"><?= $opt ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group col-md-3">
                <label><strong>Nama Printer</strong></label>
                <input type="text" name="printer_name" class="form-control" placeholder="misal: EP5802AI" required>
            </div>

            <div class="form-group col-md-3">
                <label><strong>Port Printer (COM/IP)</strong></label>
                <input type="text" name="port" class="form-control" placeholder="misal: COM5" required>
            </div>

            <div class="form-group col-md-3">
                <label><strong>Port Python</strong></label>
                <input type="number" name="python_port" class="form-control" value="3000" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Printer</button>
    </form>

    <table class="table table-bordered table-striped shadow-sm">
        <thead class="thead-dark">
            <tr>
                <th>Lokasi</th>
                <th>Divisi</th>
                <th>Nama Printer</th>
                <th>Port</th>
                <th>Python Port</th>
                <th style="width:180px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($printer as $p): ?>
            <tr>
                <td><strong><?= $p['lokasi_printer'] ?></strong></td>
                <td>
                    <?= $p['divisi'] == 0
                        ? '<span class="text-muted"><i>Default</i></span>'
                        : $p['nama_divisi'] ?>
                </td>
                <td><?= $p['printer_name'] ?></td>
                <td><code><?= $p['port'] ?></code></td>
                <td><code><?= $p['python_port'] ?></code></td>
                <td>
                    <a class="btn btn-sm btn-success mb-1" href="<?= base_url('printer/test/'.$p['id']) ?>">
                        Tes Cetak
                    </a>
                    <button class="btn btn-sm btn-warning mb-1" data-toggle="modal"
                        data-target="#editModal<?= $p['id'] ?>">
                        Edit
                    </button>
                    <a class="btn btn-sm btn-danger mb-1" onclick="return confirm('Hapus printer ini?')"
                        href="<?= base_url('printer/hapus/'.$p['id']) ?>">Hapus</a>
                </td>
            </tr>

            <!-- Modal Edit -->
            <div class="modal fade" id="editModal<?= $p['id'] ?>" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <form method="post" action="<?= base_url('printer/update/'.$p['id']) ?>">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Printer - <?= $p['lokasi_printer'] ?></h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Divisi</label>
                                    <select name="divisi" class="form-control">
                                        <option value="0">- Tidak Ada Divisi -</option>
                                        <?php foreach ($divisi as $d): ?>
                                        <option value="<?= $d['id'] ?>"
                                            <?= $p['divisi'] == $d['id'] ? 'selected' : '' ?>>
                                            <?= $d['nama_divisi'] ?>
                                        </option>
                                        <?php endforeach ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Lokasi Printer</label>
                                    <select name="lokasi_printer" class="form-control" required>
                                        <?php foreach (['KASIR', 'BAR', 'KITCHEN', 'CHECKER'] as $opt): ?>
                                        <option value="<?= $opt ?>"
                                            <?= $p['lokasi_printer'] == $opt ? 'selected' : '' ?>>
                                            <?= $opt ?>
                                        </option>
                                        <?php endforeach ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Nama Printer (Bluetooth/USB)</label>
                                    <input type="text" name="printer_name" class="form-control"
                                        value="<?= $p['printer_name'] ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Port Printer (COM/IP)</label>
                                    <input type="text" name="port" class="form-control" value="<?= $p['port'] ?>"
                                        required>
                                </div>

                                <div class="form-group">
                                    <label>Port Python</label>
                                    <input type="number" name="python_port" class="form-control"
                                        value="<?= $p['python_port'] ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php endforeach ?>
        </tbody>
    </table>
</div>

<!-- Tambahkan jika belum -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>