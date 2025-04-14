<div class="container-fluid">
    <h2>Refund Management</h2>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>

    <!-- Form Tambah -->
    <h4 class="mt-4">Tambah Refund</h4>
    <form method="post" action="<?= base_url('refund/add') ?>">
        <div class="form-row">
            <div class="col-md-3">
                <label for="kode">Kode</label>
                <input type="text" id="kode" name="kode" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label for="tanggal">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label for="nilai">Nilai</label>
                <input type="number" id="nilai" name="nilai" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label for="rekening">Rekening</label>
                <select id="rekening" name="rekening" class="form-control" required>
                    <?php foreach ($rekening_list as $rekening): ?>
                        <option value="<?= $rekening['id'] ?>"><?= $rekening['nama_rekening'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-row mt-3">
            <div class="col-md-12">
                <label for="keterangan">Keterangan</label>
                <input type="text" id="keterangan" name="keterangan" class="form-control">
            </div>
        </div>
        <div class="form-row mt-3">
            <div class="col-md-3">
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </div>
    </form>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" id="editForm" action="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Refund</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit-id" name="id">
                        <div class="form-group">
                            <label for="edit-kode">Kode</label>
                            <input type="text" id="edit-kode" name="kode" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-tanggal">Tanggal</label>
                            <input type="date" id="edit-tanggal" name="tanggal" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-nilai">Nilai</label>
                            <input type="number" id="edit-nilai" name="nilai" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-rekening">Rekening</label>
                            <select id="edit-rekening" name="rekening" class="form-control" required>
                                <?php foreach ($rekening_list as $rekening): ?>
                                    <option value="<?= $rekening['id'] ?>"><?= $rekening['nama_rekening'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit-keterangan">Keterangan</label>
                            <input type="text" id="edit-keterangan" name="keterangan" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- Filter Tanggal -->
    <form method="get" action="<?= base_url('refund') ?>" class="form-inline mb-3">
        <label for="start_date" class="mr-2">Tanggal Awal:</label>
        <input type="date" id="start_date" name="start_date" class="form-control mr-3" value="<?= $start_date ?>">

        <label for="end_date" class="mr-2">Tanggal Akhir:</label>
        <input type="date" id="end_date" name="end_date" class="form-control mr-3" value="<?= $end_date ?>">

        <button type="submit" class="btn btn-primary">Tampilkan</button>
    </form>

    <!-- Tabel Refund -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr class="text-center">
                    <th>No</th>
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th>Nilai</th>
                    <th>Rekening</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; $total_nilai = 0; foreach ($refunds as $refund): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= $refund['kode'] ?></td>
                        <td><?= $refund['tanggal'] ?></td>
                        <td class="text-right">Rp <?= number_format($refund['nilai'], 2) ?></td>
                        <td><?= $refund['rekening_name'] ?></td>
                        <td><?= $refund['keterangan'] ?></td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal" 
                                data-id="<?= $refund['id'] ?>" 
                                data-kode="<?= $refund['kode'] ?>" 
                                data-tanggal="<?= $refund['tanggal'] ?>" 
                                data-nilai="<?= $refund['nilai'] ?>" 
                                data-rekening="<?= $refund['rekening'] ?>" 
                                data-keterangan="<?= $refund['keterangan'] ?>">Edit</button>
                            <a href="<?= base_url('refund/delete/' . $refund['id']) ?>" 
                                class="btn btn-danger btn-sm" 
                                onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                     <?php $total_nilai += $refund['nilai']; ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="font-weight-bold">
                    <td colspan="3" class="text-center">Total</td>
                    <td class="text-right">Rp <?= number_format($total_nilai, 2) ?></td> <!-- Display the total nilai -->
                    <td colspan="3"></td> <!-- Empty cells for the other columns -->
                </tr>
            </tfoot>
        </table>
    </div>

<script>
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var kode = button.data('kode');
        var tanggal = button.data('tanggal');
        var nilai = button.data('nilai');
        var rekening = button.data('rekening');
        var keterangan = button.data('keterangan');

        $('#edit-id').val(id);
        $('#edit-kode').val(kode);
        $('#edit-tanggal').val(tanggal);
        $('#edit-nilai').val(nilai);
        $('#edit-rekening').val(rekening);
        $('#edit-keterangan').val(keterangan);

        $('#editForm').attr('action', '<?= base_url("refund/edit/") ?>' + id);
    });
</script>
