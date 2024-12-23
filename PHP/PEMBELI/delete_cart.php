<?php
session_start();
include '../connect.php';

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartId = filter_input(INPUT_POST, 'cart_id', FILTER_VALIDATE_INT);
    
    if (!$cartId) {
        echo json_encode(['success' => false, 'message' => 'Invalid cart ID']);
        exit;
    }
    
    // Delete from database
    $stmt = $conn->prepare("DELETE FROM keranjang WHERE ID_Keranjang = ?");
    $stmt->bind_param("i", $cartId);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete item']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>