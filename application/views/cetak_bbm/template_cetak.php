<!DOCTYPE html>
<html>

<head>
    <title>Cetak BBM</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        font-size: 14px;
    }

    h3 {
        text-align: center;
    }

    .info {
        margin: 20px auto;
        width: 80%;
    }
    </style>
</head>

<body onload="window.print()">
    <h3>DATA SPBU</h3>
    <div class="info">
        <p><strong>Kode:</strong> <?= $spbu->kode ?></p>
        <p><strong>Nama:</strong> <?= $spbu->nama ?></p>
        <p><strong>Alamat:</strong> <?= $spbu->alamat ?></p>
        <p><strong>Tanggal Cetak:</strong> <?= date('d-m-Y H:i:s') ?></p>
    </div>
</body>

</html>