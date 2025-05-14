<!DOCTYPE html>
<html>

<head>
    <title>Laporan Shift Kasir</title>
    <style>
    body {
        font-family: monospace;
        padding: 20px;
    }

    h3 {
        text-align: center;
    }

    .section {
        margin-bottom: 20px;
    }

    .row {
        display: flex;
        justify-content: space-between;
    }

    .bold {
        font-weight: bold;
    }
    </style>
</head>

<body onload="window.print()">

    <h3>LAPORAN TUTUP SHIFT</h3>

    <div class="section">
        <div class="row"><span>Kasir:</span> <span><?= $kasir['nama'] ?></span></div>
        <div class="row"><span>Waktu Buka:</span> <span><?= $shift['waktu_mulai'] ?></span></div>
        <div class="row"><span>Waktu Tutup:</span> <span><?= $shift['waktu_tutup'] ?></span></div>
    </div>

    <div class="section">
        <div class="row"><span>Modal Awal:</span> <span>Rp
                <?= number_format($shift['modal_awal'], 0, ',', '.') ?></span></div>
        <div class="row"><span>Modal Akhir:</span> <span>Rp
                <?= number_format($shift['modal_akhir'], 0, ',', '.') ?></span></div>
    </div>

    <div class="section">
        <div class="bold">Rincian Pembayaran:</div>
        <?php foreach ($metode as $m): ?>
        <div class="row"><span><?= $m['metode_pembayaran'] ?></span> <span>Rp
                <?= number_format($m['total'], 0, ',', '.') ?></span></div>
        <?php endforeach; ?>
    </div>

    <div class="section">
        <div class="bold text-danger">Rincian Refund:</div>
        <?php foreach ($refund as $r): ?>
        <div class="row"><span><?= $r['metode_pembayaran'] ?></span> <span class="text-danger">- Rp
                <?= number_format($r['total'], 0, ',', '.') ?></span></div>
        <?php endforeach; ?>
    </div>

    <div class="section">
        <div class="bold text-primary">Penerimaan per Rekening:</div>
        <?php foreach ($rekening as $r): ?>
        <div class="row"><span><?= $r['nama_rekening'] ?></span> <span>Rp
                <?= number_format($r['total'], 0, ',', '.') ?></span></div>
        <?php endforeach; ?>
    </div>

</body>

</html>