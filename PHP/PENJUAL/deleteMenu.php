<?php
session_start();
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil ID menu dari request
    $id_menu = isset($_POST['ID_Menu']) ? intval($_POST['ID_Menu']) : 0;

    if ($id_menu > 0) {
        // Query untuk menghapus menu
        $query = "DELETE FROM menu WHERE ID_Menu = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id_menu);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Menu berhasil dihapus"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal menghapus menu"]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "ID Menu tidak valid"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Metode request tidak valid"]);
}
?>
