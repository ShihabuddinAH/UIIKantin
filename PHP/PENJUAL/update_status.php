<?php
session_start();
include '../connect.php'; // Include your database connection file

if (isset($_POST['orderId']) && isset($_POST['status'])) {
    $orderId = $_POST['orderId'];
    $status = $_POST['status'];

    $sql_update = "UPDATE keranjang SET status = ? WHERE ID_Keranjang = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("si", $status, $orderId);

    if ($stmt_update->execute()) {
        echo "Status updated successfully";
    } else {
        echo "Error updating status: " . $stmt_update->error;
    }

    $stmt_update->close();
    $conn->close();
} else {
    echo "Invalid request";
}
?>