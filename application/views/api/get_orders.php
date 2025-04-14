<?php
include "config.php";

$sql = "SELECT * FROM pr_transaksi WHERE waktu_bayar IS NULL";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(["status" => "success", "orders" => $data]);
?>
