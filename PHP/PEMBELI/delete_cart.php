<?php
include '../connect.php';

$ID_Keranjang = $_GET['ID_Keranjang'];

$delete_query = "DELETE FROM keranjang WHERE ID_Keranjang = ?";
$stmt = $conn->prepare($delete_query);
$stmt->bind_param("i", $ID_Keranjang);
$stmt->execute();

header("Location: keranjang.php");
?>
