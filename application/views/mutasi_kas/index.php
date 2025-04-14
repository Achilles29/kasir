<div class="container">
    <h2 class="mt-4"><?= $title ?></h2>

    <!-- Tombol Tambah Mutasi Kas -->
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambah">Tambah Mutasi Kas</button>

    <h4>Data Mutasi Kas</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Rekening</th>
                <th>Jenis Mutasi</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mutasi_kas as $index => $item): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $item['tanggal'] ?></td>
                    <td><?= $item['nama_rekening'] ?></td>
                    <td><?= ucfirst($item['jenis_mutasi']) ?></td>
                    <td><?= number_format($item['jumlah'], 2, ',', '.') ?></td>
                    <td><?= $item['keterangan'] ?></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-warning edit-mutasi" 
                                data-id="<?= $item['id'] ?>" 
                                data-rekening="<?= $item['bl_rekening_id'] ?>" 
                                data-tanggal="<?= $item['tanggal'] ?>" 
                                data-jenis="<?= $item['jenis_mutasi'] ?>" 
                                data-jumlah="<?= $item['jumlah'] ?>" 
                                data-keterangan="<?= $item['keterangan'] ?>"
                                data-toggle="modal" data-target="#modalEdit">
                            Edit
                        </button>
                        <a href="<?= base_url('mutasi_kas/delete_mutasi_kas/'.$item['id']) ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Tambah Mutasi Kas -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Mutasi Kas</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post" action="<?= base_url('mutasi_kas/add_mutasi_kas') ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="bl_rekening_id">Rekening</label>
                        <select name="bl_rekening_id" class="form-control" required>
                            <?php foreach ($rekening as $rek): ?>
                                <option value="<?= $rek['id'] ?>"><?= $rek['nama_rekening'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jenis_mutasi">Jenis Mutasi</label>
                        <select name="jenis_mutasi" class="form-control" required>
                            <option value="masuk">Masuk</option>
                            <option value="keluar">Keluar</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="text" name="jumlah" class="form-control" pattern="\d+(\.\d{1,2})?" title="Masukkan angka dengan maksimal 2 desimal" required>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Mutasi Kas -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Mutasi Kas</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post" action="<?= base_url('mutasi_kas/update_mutasi_kas') ?>">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="form-group">
                        <label for="edit_tanggal">Tanggal</label>
                        <input type="date" name="tanggal" id="edit_tanggal" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_bl_rekening_id">Rekening</label>
                        <select name="bl_rekening_id" id="edit_bl_rekening_id" class="form-control" required>
                            <?php foreach ($rekening as $rek): ?>
                                <option value="<?= $rek['id'] ?>"><?= $rek['nama_rekening'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_jenis_mutasi">Jenis Mutasi</label>
                        <select name="jenis_mutasi" id="edit_jenis_mutasi" class="form-control" required>
                            <option value="masuk">Masuk</option>
                            <option value="keluar">Keluar</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_jumlah">Jumlah</label>
                        <input type="text" name="jumlah" id="edit_jumlah" class="form-control" pattern="\d+(\.\d{1,2})?" title="Masukkan angka dengan maksimal 2 desimal" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_keterangan">Keterangan</label>
                        <textarea name="keterangan" id="edit_keterangan" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Mengisi modal edit saat tombol edit diklik
document.querySelectorAll('.edit-mutasi').forEach(button => {
    button.addEventListener('click', function() {
        document.getElementById('edit_id').value = this.getAttribute('data-id');
        document.getElementById('edit_tanggal').value = this.getAttribute('data-tanggal');
        document.getElementById('edit_bl_rekening_id').value = this.getAttribute('data-rekening');
        document.getElementById('edit_jenis_mutasi').value = this.getAttribute('data-jenis');
        document.getElementById('edit_jumlah').value = this.getAttribute('data-jumlah');
        document.getElementById('edit_keterangan').value = this.getAttribute('data-keterangan');
    });
});
</script>
