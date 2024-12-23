<?php
session_start();
include '../connect.php';

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartId = filter_input(INPUT_POST, 'cart_id', FILTER_VALIDATE_INT);
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
    
    if (!$cartId || !in_array($action, ['increase', 'decrease'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit;
    }
    
    // Get current cart item and menu price
    $stmt = $conn->prepare("SELECT k.Jumlah_Pesanan, k.ID_Menu, m.Harga_Menu 
                           FROM keranjang k
                           JOIN menu m ON k.ID_Menu = m.ID_Menu
                           WHERE k.ID_Keranjang = ?");
    $stmt->bind_param("i", $cartId);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart = $result->fetch_assoc();
    
    if (!$cart) {
        echo json_encode(['success' => false, 'message' => 'Cart item not found']);
        exit;
    }
    
    // Calculate new quantity
    $newQuantity = $action === 'increase' ? 
                   $cart['Jumlah_Pesanan'] + 1 : 
                   $cart['Jumlah_Pesanan'] - 1;
    
    // Validate minimum quantity
    if ($newQuantity < 1) {
        echo json_encode(['success' => false, 'message' => 'Quantity cannot be less than 1']);
        exit;
    }
    
    // Calculate new subtotal
    $newSubtotal = $newQuantity * $cart['Harga_Menu'];
    
    // Update database
    $stmt = $conn->prepare("UPDATE keranjang 
                           SET Jumlah_Pesanan = ?, 
                               Subtotal_Harga = ? 
                           WHERE ID_Keranjang = ?");
    $stmt->bind_param("idi", $newQuantity, $newSubtotal, $cartId);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'newQuantity' => $newQuantity,
            'newTotal' => $newSubtotal
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update cart']);
    }
}
?>