<?php
$host = "localhost";
$user = "root";  // Sesuaikan dengan MySQL Anda
$password = "29011989"; // Sesuaikan dengan MySQL Anda
$database = "namua";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Gagal koneksi ke database"]));
}
?>
