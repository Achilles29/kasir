<div class="container">
    <h2>Laporan Mutasi Rekening</h2>

    <!-- Filter Bulan -->
    <form method="GET" action="<?= site_url('mutasi_rekening'); ?>" class="form-inline mb-3">
        <label for="bulan" class="mr-2">Pilih Bulan:</label>
        <select id="bulan" name="bulan" class="form-control mr-3">
            <?php 
            $current_month = date('m');
            $current_year = date('Y');
            for ($i = 1; $i <= 12; $i++) :
                $month = sprintf('%02d', $i);
                $selected = $bulan == "$current_year-$month" ? 'selected' : '';
                echo "<option value='$current_year-$month' $selected>" . date('F', mktime(0, 0, 0, $i, 10)) . " $current_year</option>";
            endfor;
            ?>
        </select>
        <button type="submit" class="btn btn-primary">Terapkan</button>
    </form>

    <!-- Add New Record -->
    <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addModal">Tambah Mutasi Rekening</button>

<!-- Table for Mutasi Rekening -->
<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th>Tanggal</th>
                <th>Sumber Rekening</th>
                <th>Tujuan Rekening</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mutasi_rekening_data as $row): ?>
                <tr>
                    <td><?= $row['tanggal'] ?></td>
                    <td><?= $row['sumber_rekening'] ?></td> <!-- Displaying 'sumber_rekening' -->
                    <td><?= $row['tujuan_rekening'] ?></td> <!-- Displaying 'tujuan_rekening' -->
                    <td class="text-right"><?= number_format($row['jumlah'], 2) ?></td>
                    <td><?= $row['keterangan'] ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal<?= $row['id'] ?>">Edit</button>
                        <a href="<?= site_url('mutasi_rekening/delete/'.$row['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Mutasi Rekening</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="<?= site_url('mutasi_rekening/edit/'.$row['id']); ?>" method="POST">
                                    <div class="form-group">
                                        <label for="tanggal">Tanggal</label>
                                        <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= $row['tanggal'] ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="bl_rekening_id_sumber">Sumber Rekening</label>
                                        <select class="form-control" id="bl_rekening_id_sumber" name="bl_rekening_id_sumber" required>
                                            <?php foreach ($rekening_options as $rekening): ?>
                                                <option value="<?= $rekening['id'] ?>" <?= $rekening['id'] == $row['bl_rekening_id_sumber'] ? 'selected' : '' ?>>
                                                    <?= $rekening['nama_rekening'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="bl_rekening_id_tujuan">Tujuan Rekening</label>
                                        <select class="form-control" id="bl_rekening_id_tujuan" name="bl_rekening_id_tujuan" required>
                                            <?php foreach ($rekening_options as $rekening): ?>
                                                <option value="<?= $rekening['id'] ?>" <?= $rekening['id'] == $row['bl_rekening_id_tujuan'] ? 'selected' : '' ?>>
                                                    <?= $rekening['nama_rekening'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="jumlah">Jumlah</label>
                                        <input type="number" class="form-control" id="jumlah" name="jumlah" value="<?= $row['jumlah'] ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <textarea class="form-control" id="keterangan" name="keterangan" required><?= $row['keterangan'] ?></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add Mutasi Rekening</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= site_url('mutasi_rekening/add'); ?>" method="POST">
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                    </div>
                    <div class="form-group">
                        <label for="bl_rekening_id_sumber">Sumber Rekening</label>
                        <select class="form-control" id="bl_rekening_id_sumber" name="bl_rekening_id_sumber" required>
                            <?php foreach ($rekening_options as $rekening): ?>
                                <option value="<?= $rekening['id'] ?>"><?= $rekening['nama_rekening'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="bl_rekening_id_tujuan">Tujuan Rekening</label>
                        <select class="form-control" id="bl_rekening_id_tujuan" name="bl_rekening_id_tujuan" required>
                            <?php foreach ($rekening_options as $rekening): ?>
                                <option value="<?= $rekening['id'] ?>"><?= $rekening['nama_rekening'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" required>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
