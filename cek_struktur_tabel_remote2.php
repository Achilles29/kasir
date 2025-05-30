<?php
// Konfigurasi koneksi ke database di server remote
$host = '89.116.171.157';
$user = 'root';
$pass = '29011989';
$dbname = 'namua';

// Koneksi ke MySQL
$conn = new mysqli($host, $user, $pass, $dbname, 3306);
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

    $columns = $conn->query("SHOW COLUMNS FROM `$table`");
    if (!$columns) {
        $output .= "TABEL: $table → ⚠️ Gagal baca struktur: " . $conn->error . "\n";
        continue;
    }

    $has_created = false;
    $has_updated = false;
    $column_list = [];

    while ($col = $columns->fetch_assoc()) {
        $field = $col['Field'];
        $type = $col['Type'];
        $column_list[] = "$field ($type)";

        if ($field === 'created_at') $has_created = true;
        if ($field === 'updated_at') $has_updated = true;
    }

    $warning = "";
    if (!$has_created || !$has_updated) {
        $missing_timestamps[] = $table;
        $warning = " ⚠️ (missing: ";
        if (!$has_created) $warning .= "created_at";
        if (!$has_created && !$has_updated) $warning .= " & ";
        if (!$has_updated) $warning .= "updated_at";
        $warning .= ")";
    }

    $columns_str = implode(", ", $column_list);
    $output .= "TABEL: $table → $columns_str$warning\n";
}

// Simpan hasil ke file
file_put_contents("struktur_tabel_namua2.txt", $output);

// Ringkasan di terminal
echo "✅ Struktur tabel berhasil disimpan ke: struktur_tabel_namua2.txt\n";
if (!empty($missing_timestamps)) {
    echo "\n⚠️ Tabel tanpa created_at / updated_at:\n";
    foreach ($missing_timestamps as $tbl) {
        echo "- $tbl\n";
    }
}

$conn->close();
?>