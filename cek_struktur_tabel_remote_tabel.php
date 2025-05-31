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

$tables = $conn->query("SHOW TABLES");
if (!$tables) {
    die("❌ Gagal mengambil daftar tabel: " . $conn->error);
}

$output .= "[\n";

while ($row = $tables->fetch_array()) {
    $table = $row[0];
    $output .= "    '$table',\n";
}

$output .= "];\n";

// Simpan ke file
file_put_contents("daftar_tabel_namua.txt", $output);

// Tampilkan ringkasan di terminal
echo "✅ Daftar tabel berhasil disimpan ke: daftar_tabel_namua.txt\n";

$conn->close();
?>