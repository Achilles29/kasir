<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Preview Cetakan - <?= $printer['lokasi_printer'] ?></title>
    <style>
    body {
        font-family: monospace;
        background: #f8f8f8;
        padding: 20px;
    }

    pre {
        background: #fff;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .btn {
        padding: 10px 20px;
        margin: 5px;
    }
    </style>
</head>

<body>

    <h2>Preview Cetakan - <?= $printer['lokasi_printer'] ?></h2>
    <p>Transaksi: <?= $transaksi['no_transaksi'] ?> | <?= $transaksi['customer'] ?></p>

    <pre><?= $preview_struk ?></pre>

    <form method="post" action="<?= site_url('kasir/cetak_pending_printer') ?>">
        <input type="hidden" name="transaksi_id" value="<?= $transaksi['id'] ?>">
        <input type="hidden" name="lokasi_printer" value="<?= $printer['lokasi_printer'] ?>">
        <input type="hidden" name="struk_text" value="<?= htmlentities($preview_struk) ?>">
        <button type="submit" class="btn btn-success">Cetak Sekarang</button>
        <a href="<?= site_url('kasir') ?>" class="btn btn-secondary">Kembali</a>
    </form>

</body>

</html>