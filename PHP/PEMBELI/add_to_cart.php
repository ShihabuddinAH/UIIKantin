<?php
header('Content-Type: application/json');
session_start();
include '../connect.php';

try {
    // Check authentication
    if (!isset($_SESSION['username'])) {
        throw new Exception('User not authenticated');
    }

    // Validate form data
    if (!isset($_POST['ID_Menu']) || !isset($_POST['Jumlah_Pesanan'])) {
        throw new Exception('Invalid form data');
    }

    // Debugging: Log received form data
    error_log("ID_Menu: " . $_POST['ID_Menu']);
    error_log("Jumlah_Pesanan: " . $_POST['Jumlah_Pesanan']);

    // Get user ID
    $stmt = $conn->prepare("SELECT id_pengguna FROM user WHERE username = ?");
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if (!$user) {
        throw new Exception('User not found');
    }

    $ID_Pengguna = $user['id_pengguna'];
    $ID_Menu = (int)$_POST['ID_Menu'];
    $Jumlah_Pesanan = (int)$_POST['Jumlah_Pesanan'];

    // Start transaction
    $conn->begin_transaction();

    // Check if item already in cart
    $stmt = $conn->prepare("SELECT ID_Keranjang, Jumlah_Pesanan FROM keranjang WHERE ID_Pengguna = ? AND ID_Menu = ?");
    $stmt->bind_param("ii", $ID_Pengguna, $ID_Menu);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update existing item
        $row = $result->fetch_assoc();
        $new_quantity = $row['Jumlah_Pesanan'] + $Jumlah_Pesanan;
        
        $stmt = $conn->prepare("UPDATE keranjang SET Jumlah_Pesanan = ? WHERE ID_Keranjang = ?");
        $stmt->bind_param("ii", $new_quantity, $row['ID_Keranjang']);
        $stmt->execute();
    } else {
        // Add new item
        $stmt = $conn->prepare("INSERT INTO keranjang (ID_Pengguna, ID_Menu, Jumlah_Pesanan) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $ID_Pengguna, $ID_Menu, $Jumlah_Pesanan);
        $stmt->execute();
    }

    // Commit transaction
    $conn->commit();
    
    // Redirect back to menu
    header("Location: menu.php?ID_Warung=" . $_POST['ID_Warung']);
    exit();

} catch (Exception $e) {
    if (isset($conn)) {
        $conn->rollback();
    }
    echo "Error: " . $e->getMessage();
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}
?>