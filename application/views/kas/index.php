<div class="container">
    <h2 class="mt-4"><?= $title ?></h2>

    <!-- Tombol Tambah Kas -->
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahKasModal">Tambah Kas</button>

    <!-- Tabel Data Kas Awal -->
    <h4>Data Kas Awal</h4>
    <div class="table-responsive">
        <table class="table table-bordered text-right">
            <thead class="text-center">
                <tr>
                    <th>No</th>
                    <th>Rekening</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0; 
                $no = 1; 
                foreach ($kas as $row): 
                    $total += $row['jumlah'];
                ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="text-left"><?= $row['nama_rekening'] ?></td>
                        <td class="text-center"><?= $row['tanggal'] ?></td>
                        <td><?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning edit-kas" 
                                data-id="<?= $row['id'] ?>" 
                                data-rekening="<?= $row['bl_rekening_id'] ?>" 
                                data-tanggal="<?= $row['tanggal'] ?>" 
                                data-jumlah="<?= $row['jumlah'] ?>">
                                Edit
                            </button>
                            <a href="<?= base_url('kas/delete_kas/'.$row['id']) ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-center">Total</th>
                    <th><?= number_format($total, 0, ',', '.') ?></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Modal Tambah Kas -->
<div class="modal fade" id="tambahKasModal" tabindex="-1" aria-labelledby="tambahKasModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahKasModalLabel">Tambah Kas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="<?= base_url('kas/add_kas') ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="bl_rekening_id">Rekening</label>
                        <select name="bl_rekening_id" id="bl_rekening_id" class="form-control" required>
                            <?php foreach ($rekening as $rek): ?>
                                <option value="<?= $rek['id'] ?>"><?= $rek['nama_rekening'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Kas -->
<div class="modal fade" id="editKasModal" tabindex="-1" aria-labelledby="editKasModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editKasModalLabel">Edit Kas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="<?= base_url('kas/update_kas') ?>">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_kas_id">
                    <div class="form-group">
                        <label for="edit_bl_rekening_id">Rekening</label>
                        <select name="bl_rekening_id" id="edit_bl_rekening_id" class="form-control" required>
                            <?php foreach ($rekening as $rek): ?>
                                <option value="<?= $rek['id'] ?>"><?= $rek['nama_rekening'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_tanggal">Tanggal</label>
                        <input type="date" name="tanggal" id="edit_tanggal" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_jumlah">Jumlah</label>
                        <input type="number" name="jumlah" id="edit_jumlah" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript untuk Modal Edit -->
<script>
    $(document).ready(function() {
        $('.edit-kas').on('click', function() {
            $('#edit_kas_id').val($(this).data('id'));
            $('#edit_bl_rekening_id').val($(this).data('rekening'));
            $('#edit_tanggal').val($(this).data('tanggal'));
            $('#edit_jumlah').val($(this).data('jumlah'));
            $('#editKasModal').modal('show');
        });
    });
</script>
