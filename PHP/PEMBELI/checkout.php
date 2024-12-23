<?php
session_start();
include '../connect.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    echo json_encode(["status" => "error", "message" => "Pengguna belum login"]);
    exit();
}

// Ambil ID Pengguna dari sesi
$stmt = $conn->prepare("SELECT id_pengguna FROM user WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$ID_Pengguna = $user['id_pengguna'];

// Ambil semua item di keranjang berdasarkan ID Pengguna
$query = "SELECT keranjang.ID_Keranjang, keranjang.ID_Menu 
          FROM keranjang 
          JOIN menu ON keranjang.ID_Menu = menu.ID_Menu 
          WHERE keranjang.ID_Pengguna = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $ID_Pengguna);
$stmt->execute();
$result = $stmt->get_result();

// Validasi apakah semua menu masih tersedia
$itemsToCheckout = [];
while ($row = $result->fetch_assoc()) {
    // Periksa apakah menu masih ada di tabel menu
    $menuQuery = "SELECT COUNT(*) AS count FROM menu WHERE ID_Menu = ?";
    $menuStmt = $conn->prepare($menuQuery);
    $menuStmt->bind_param("i", $row['ID_Menu']);
    $menuStmt->execute();
    $menuResult = $menuStmt->get_result();
    $menu = $menuResult->fetch_assoc();

    if ($menu['count'] == 0) {
        echo json_encode([
            "status" => "error",
            "message" => "Menu dengan ID " . $row['ID_Menu'] . " sudah tidak tersedia"
        ]);
        exit();
    }

    // Tambahkan item ke daftar checkout
    $itemsToCheckout[] = $row['ID_Keranjang'];
}

// Jika semua menu valid, ubah status menjadi "checkout"
if (!empty($itemsToCheckout)) {
    $ids = implode(",", $itemsToCheckout); // Gabungkan ID menjadi string untuk query
    $updateQuery = "UPDATE keranjang SET status = 'checkout' WHERE ID_Keranjang IN ($ids)";
    if ($conn->query($updateQuery)) {
        echo json_encode(["status" => "success", "message" => "Checkout berhasil"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Terjadi kesalahan saat checkout"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Keranjang kosong"]);
}
?>
