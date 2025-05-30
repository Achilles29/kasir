<?php
// Konfigurasi koneksi ke database di server remote
$host = '89.116.171.157';
$user = 'root';
$pass = '29011989';
$dbname = 'namua';

// Koneksi ke MySQL (gunakan port 3306 jika default)
$conn = new mysqli($host, $user, $pass, $dbname, 3306);

// Cek koneksi
if ($conn->connect_error) {
    die("❌ Koneksi gagal: " . $conn->connect_error);
}

$output = "";
$missing_timestamps = [];

// Ambil semua tabel
$tables = $conn->query("SHOW TABLES");
if (!$tables) {
    die("❌ Gagal mengambil daftar tabel: " . $conn->error);
}

while ($row = $tables->fetch_array()) {
    $table = $row[0];
    $output .= "=============================\n";
    $output .= "TABEL: $table\n";
    $output .= "=============================\n";

    $columns = $conn->query("SHOW COLUMNS FROM `$table`");
    if (!$columns) {
        $output .= "⚠️  Gagal membaca struktur tabel $table: " . $conn->error . "\n\n";
        continue;
    }

    $has_created = false;
    $has_updated = false;

    while ($col = $columns->fetch_assoc()) {
        $field = $col['Field'];
        $type = $col['Type'];
        $output .= "- $field ($type)\n";

        if ($field === 'created_at') $has_created = true;
        if ($field === 'updated_at') $has_updated = true;
    }

    if (!$has_created || !$has_updated) {
        $missing_timestamps[] = $table;
        $output .= "⚠️  TABEL INI TIDAK PUNYA ";
        $output .= !$has_created ? "`created_at`" : "";
        $output .= (!$has_created && !$has_updated) ? " dan " : "";
        $output .= !$has_updated ? "`updated_at`" : "";
        $output .= "\n";
    }

    $output .= "\n";
}

// Simpan hasil ke file
file_put_contents("struktur_tabel_namua.txt", $output);

// Ringkasan di terminal
echo "✅ Struktur tabel berhasil disimpan ke: struktur_tabel_namua.txt\n";
if (!empty($missing_timestamps)) {
    echo "\n⚠️ Tabel tanpa created_at / updated_at:\n";
    foreach ($missing_timestamps as $tbl) {
        echo "- $tbl\n";
    }
}

$conn->close();
?>