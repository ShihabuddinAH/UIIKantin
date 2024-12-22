<?php
include '../connect.php';

$cart_id = $_POST['ID_Keranjang'];
$new_quantity = $_POST['Jumlah_Pesanan'];

$update_query = "UPDATE keranjang SET Jumlah_Pesanan = ? WHERE id = ?";
$stmt = $conn->prepare($update_query);
$stmt->bind_param("ii", $new_quantity, $cart_id);
$stmt->execute();

header("Location: keranjang.php");
?>
