<style>

    .pagination .active a {
        background-color: #007bff;
        color: #fff;
    }
    .container {
        max-width: 100%; /* Perlebar container */
    }

    .table-responsive {
        overflow-x: auto; /* Scroll horizontal untuk tabel panjang */
    }

    .table td,
    .table th {
        padding: 10px 20px; /* Tambahkan jarak antar kolom */
        white-space: nowrap; /* Tetap no-wrap */
        vertical-align: middle; /* Rata tengah vertikal */
    }

    .penyesuaian-input,
    .keterangan-input {
        min-width: 150px; /* Lebarkan kolom input */
    }

    .pagination {
        display: flex;
        justify-content: center;
        padding: 10px 0;
    }

    .form-inline {
        display: flex;
        flex-wrap: nowrap;
        gap: 10px;
        align-items: center;
    }

    .form-inline select, .form-inline input, .form-inline button {
        flex-shrink: 0;
    }

    .form-inline .form-group {
        display: flex;
        flex-direction: column;
        margin-right: 10px;
    }

    .form-inline .form-group label {
        margin-bottom: 2px;
        font-size: 0.9rem;
    }

    .form-inline .form-control {
        width: auto;
    }
</style>
<div class="container">
    <h2>Penjualan Kasir</h2>

<!-- Filter -->
<div class="container">
    <form method="get" action="<?= base_url('penjualan_kasir/index') ?>" class="form-inline mb-3">
        <div class="form-group">
            <label for="tanggal_awal">Tanggal Awal</label>
            <input type="date" id="tanggal_awal" name="tanggal_awal" value="<?= $tanggal_awal ?>" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="tanggal_akhir">Tanggal Akhir</label>
            <input type="date" id="tanggal_akhir" name="tanggal_akhir" value="<?= $tanggal_akhir ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="metode_pembayaran" class="mr-2">Metode Pembayaran</label>
            <select id="metode_pembayaran" name="metode_pembayaran" class="form-control mr-3">
                <option value="">Semua</option>
                <?php foreach ($metode_pembayaran_list as $key => $value): ?>
                    <option value="<?= $key ?>" <?= $metode_pembayaran == $key ? 'selected' : '' ?>><?= $value ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="rekening_id">Rekening</label>
            <select id="rekening_id" name="rekening_id" class="form-control">
                <option value="">Semua</option>
                <?php foreach ($rekening_list as $rekening): ?>
                    <option value="<?= $rekening['id'] ?>" <?= $rekening_id == $rekening['id'] ? 'selected' : '' ?>>
                        <?= $rekening['nama_rekening'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="search_nota">Cari No Nota</label>
            <input type="text" id="search_nota" name="search_nota" value="<?= $search_nota ?>" class="form-control" placeholder="No Nota">
        </div>

        <button type="submit" class="btn btn-primary">Filter</button>

        <div class="form-group">
            <label for="per_page">Tampilkan</label>
            <select id="per_page" name="per_page" class="form-control" onchange="this.form.submit()">
                <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                <option value="30" <?= $limit == 30 ? 'selected' : '' ?>>30</option>
                <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
            </select>
        </div>
    </form>
</div>


    <!-- Tabel Data -->
<?php if (!empty($penjualan)): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-sm">
<thead>
    <tr class="text-center">
        <th>Tanggal</th>
        <th>No Nota</th>
        <th>Waktu Order</th>
        <th>Waktu Bayar</th>
        <th>Penjualan (Rp)</th>
        <th>Metode Pembayaran</th>
        <th>Rekening</th>
        <th>Penyesuaian</th>
        <th>Selisih</th>
        <th>Keterangan</th>
        <th>Aksi</th>
    </tr>
</thead>
<tbody>
    <?php foreach ($penjualan as $row): ?>
    <tr data-rekening-id="<?= $row['rekening_id'] ?>">
        <td><?= $row['tanggal'] ?></td>
        <td><?= $row['no_nota'] ?></td>
        <td><?= $row['waktu_order'] ?></td>
        <td><?= $row['waktu_bayar'] ?></td>
        <td class="text-right penjualan-value"><?= number_format($row['penjualan'], 2) ?></td>
        <td><?= $row['metode_pembayaran'] ?></td>
        <td><?= $row['rekening'] ?></td>
        <td>
            <input type="number" class="form-control penyesuaian-input" data-id="<?= $row['no_nota'] ?>" value="<?= $row['penyesuaian'] ?>">
        </td>
        <td class="text-right selisih-value"><?= ($row['selisih']) ?></td>
        <td>
            <input type="text" class="form-control keterangan-input" data-id="<?= $row['no_nota'] ?>" value="<?= $row['keterangan'] ?>">
        </td>
        <td>
            <button class="btn btn-primary btn-sm kirim-mutasi" data-id="<?= $row['no_nota'] ?>">Kirim Mutasi</button>
        </td>
    </tr>

    <?php endforeach; ?>
</tbody>

       </table>
    </div>
<?php else: ?>
    <p class="text-center">Tidak ada data penjualan untuk ditampilkan.</p>
<?php endif; ?>

    <!-- Pagination -->
    <div class="pagination"><?= $pagination ?></div>
</div>
<script>
// document.querySelectorAll('.penyesuaian-input').forEach(input => {
//     input.addEventListener('blur', function () {
//         const row = this.closest('tr');
//         const noNota = this.getAttribute('data-id');
//         const penyesuaian = parseFloat(this.value) || 0;

//         fetch('<?= base_url("penjualan_kasir/update_penyesuaian") ?>', {
//             method: 'POST',
//             headers: { 'Content-Type': 'application/json' },
//             body: JSON.stringify({ no_nota: noNota, penyesuaian })
//         })
//         .then(response => response.json())
//         .then(data => {
//             if (data.success) {
//                 const penjualan = parseFloat(row.querySelector('.penjualan-value').innerText) || 0;
//                 const selisih = penyesuaian - penjualan; // Penyesuaian - Penjualan
//                 row.querySelector('.selisih-value').innerText = selisih.toFixed(2);
//             } else {
//                 alert('Gagal memperbarui data.');
//             }
//         });
//     });
// });

document.querySelectorAll('.penyesuaian-input').forEach(input => {
    input.addEventListener('input', function () {
        const row = this.closest('tr');
        const penjualan = parseFloat(row.querySelector('.penjualan-value').innerText.replace(/,/g, '')) || 0;
        const penyesuaian = parseFloat(this.value.replace(/,/g, '')) || 0;

        const selisih = penyesuaian - penjualan;
        row.querySelector('.selisih-value').innerText = selisih.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    });

    input.addEventListener('blur', function () {
        const row = this.closest('tr');
        const noNota = this.getAttribute('data-id');
        const penyesuaian = parseFloat(this.value.replace(/,/g, '')) || 0;

        fetch('<?= base_url("penjualan_kasir/update_penyesuaian") ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ no_nota: noNota, penyesuaian })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const penjualan = parseFloat(row.querySelector('.penjualan-value').innerText.replace(/,/g, '')) || 0;
                const selisih = penyesuaian - penjualan;
                row.querySelector('.selisih-value').innerText = selisih.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            } else {
                alert('Gagal memperbarui data.');
            }
        });
    });
});
document.querySelectorAll('.keterangan-input').forEach(input => {
    input.addEventListener('blur', function () {
        const noNota = this.getAttribute('data-id');
        const keterangan = this.value;

        fetch('<?= base_url("penjualan_kasir/update_keterangan") ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ no_nota: noNota, keterangan })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert('Gagal memperbarui keterangan.');
            }
        });
    });
});
document.querySelectorAll('.kirim-mutasi').forEach(button => {
    button.addEventListener('click', function () {
        const row = this.closest('tr');
        const tanggal = row.querySelector('td:nth-child(1)').innerText;
        
        // Ambil teks selisih dan ganti format
        const selisihText = row.querySelector('.selisih-value').innerText
            .replace(/,/g, '')  // Hilangkan koma ribuan (seharusnya titik di beberapa format)
            .replace(/\./g, ','); // Ubah titik menjadi koma sementara
        
        // Konversi ke format angka
        const selisih = parseFloat(selisihText.replace(',', '.')) || 0;
        const keterangan = row.querySelector('.keterangan-input').value;
        const rekeningId = row.getAttribute('data-rekening-id');

        if (!rekeningId) {
            alert('Rekening ID tidak ditemukan!');
            return;
        }

        fetch('<?= base_url("penjualan_kasir/kirim_mutasi") ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ tanggal, selisih, keterangan, rekening_id: rekeningId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengirim mutasi.');
        });
    });
});

// document.querySelectorAll('.kirim-mutasi').forEach(button => {
//     button.addEventListener('click', function () {
//         const row = this.closest('tr');
//         const tanggal = row.querySelector('td:nth-child(1)').innerText; // Kolom tanggal
//         const selisih = parseFloat(row.querySelector('.selisih-value').innerText) || 0; // Kolom selisih
//         const keterangan = row.querySelector('.keterangan-input').value; // Kolom keterangan
//         const rekeningId = row.getAttribute('data-rekening-id'); // Ambil rekening_id

//         if (!rekeningId) {
//             alert('Rekening ID tidak ditemukan!');
//             return;
//         }

//         fetch('<?= base_url("penjualan_kasir/kirim_mutasi") ?>', {
//             method: 'POST',
//             headers: { 'Content-Type': 'application/json' },
//             body: JSON.stringify({ tanggal, selisih, keterangan, rekening_id: rekeningId })
//         })
//         .then(response => response.json())
//         .then(data => {
//             if (data.success) {
//                 alert(data.message);
//             } else {
//                 alert(data.message);
//             }
//         })
//         .catch(error => {
//             console.error('Error:', error);
//             alert('Terjadi kesalahan saat mengirim mutasi.');
//         });
//     });
// });
  


</script>
