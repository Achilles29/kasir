<div class="container" style="max-width: 75%;">
    <h2>Saldo Kas</h2>
    <form method="get" action="<?= base_url('rekap_rekening') ?>" class="form-inline mb-3">
        <div class="form-group mr-3">
            <label for="bulan" class="mr-2">Bulan</label>
            <select name="bulan" id="bulan" class="form-control">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= $i ?>" <?= $i == $bulan ? 'selected' : '' ?>>
                        <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="form-group mr-3">
            <label for="tahun" class="mr-2">Tahun</label>
            <select name="tahun" id="tahun" class="form-control">
                <?php for ($i = 2020; $i <= date('Y'); $i++): ?>
                    <option value="<?= $i ?>" <?= $i == $tahun ? 'selected' : '' ?>><?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Tampilkan</button>
    </form>
<!-- Tombol Generate Saldo Awal Bulan Ini
<button id="generate-saldo-awal-bulan-ini" class="btn btn-warning">
    Generate Saldo Awal Bulan Ini
</button> -->

<!-- Tombol Generate Saldo Awal Bulan Berikutnya -->
<button id="generate-saldo-awal-bulan-berikutnya" class="btn btn-primary">
    Generate Saldo Awal
</button>
<button id="generate-saldo-awal-bulan-ini" class="btn btn-primary">
    Generate Saldo Awal Bulan ini
</button>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr class="text-center">
                    <th style="white-space: nowrap;">Tanggal</th>
                    <?php foreach ($rekening_list as $rekening): ?>
                        <th style="white-space: nowrap;"><?= $rekening['nama_rekening'] ?></th>
                    <?php endforeach; ?>
                    <th style="white-space: nowrap;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="white-space: nowrap;">Saldo Awal</td>
                    <?php 
                    $total_awal = 0;
                    foreach ($rekening_list as $rekening): 
                        $nilai = $rekap['Saldo Awal'][$rekening['id']] ?? 0;
                        $total_awal += $nilai;
                    ?>
                        <td class="text-right" style="white-space: nowrap;">Rp <?= number_format($nilai, 2) ?></td>
                    <?php endforeach; ?>
                    <td class="text-right" style="white-space: nowrap;">Rp <?= number_format($total_awal, 2) ?></td>
                </tr>
                <?php foreach ($rekap as $tanggal => $nilai): ?>
                    <?php if ($tanggal !== 'Saldo Awal'): ?>
                        <tr>
                            <td style="white-space: nowrap;"><?= htmlspecialchars($tanggal) ?></td>
                            <?php 
                            $total_harian = 0;
                            foreach ($rekening_list as $rekening): 
                                $nilai_harian = $nilai[$rekening['id']] ?? 0;
                                $total_harian += $nilai_harian;
                            ?>
                                <td class="text-right" style="white-space: nowrap;">Rp <?= number_format($nilai_harian, 2) ?></td>
                            <?php endforeach; ?>
                            <td class="text-right" style="white-space: nowrap;">Rp <?= number_format($total_harian, 2) ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>


<script>
    document.getElementById('generate-saldo-awal-bulan-ini').addEventListener('click', function () {
        if (confirm('Apakah Anda yakin ingin generate saldo awal untuk bulan ini?')) {
            const bulan = <?= $bulan ?>;
            const tahun = <?= $tahun ?>;

            fetch('<?= base_url("rekap_rekening/generate_saldo_awal_bulan_ini") ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ bulan, tahun })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Saldo awal bulan ini berhasil digenerate.');
                    location.reload();
                } else {
                    alert('Gagal generate saldo awal bulan ini: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat generate saldo awal bulan ini.');
            });
        }
    });

    document.getElementById('generate-saldo-awal-bulan-berikutnya').addEventListener('click', function () {
        if (confirm('Apakah Anda yakin ingin generate saldo awal untuk bulan berikutnya?')) {
            const bulan = <?= $bulan ?>;
            const tahun = <?= $tahun ?>;

            fetch('<?= base_url("rekap_rekening/generate_saldo_awal") ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ bulan, tahun })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Saldo awal bulan berikutnya berhasil digenerate.');
                    location.reload();
                } else {
                    alert('Gagal generate saldo awal bulan berikutnya: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat generate saldo awal bulan berikutnya.');
            });
        }
    });
</script>