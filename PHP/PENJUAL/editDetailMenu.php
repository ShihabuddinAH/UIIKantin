<?php
session_start();
include '../connect.php'; // Koneksi ke database

if (!isset($_SESSION['username'])) {
    header('Location: ../LOGIN/login.php');
    exit();
}

$id = $_GET['ID_Menu'];

// Ambil data menu berdasarkan ID
$sql = "SELECT * FROM menu WHERE ID_Menu = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $menu = $result->fetch_assoc();
} else {
    die("Menu tidak ditemukan.");
}

$gambarAwal = $menu['Gambar_Menu'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_menu = $_POST['Nama_Menu'];
    $deskripsi_menu = $_POST['Deskripsi_Menu'];
    $harga_menu = $_POST['Harga_Menu'];
    $gambar_menu = $_FILES['gambar_menu']['name'];

    // Folder tujuan upload
    $target_dir = "../../ASSETS/MENU/";
    $target_file = $target_dir . basename($gambar_menu);

    // Jika ada file baru diunggah, gunakan file baru, jika tidak, gunakan file lama
    if (!empty($gambar_menu) && move_uploaded_file($_FILES['gambar_menu']['tmp_name'], $target_file)) {
        $gambarFinal = $gambar_menu;
    } else {
        $gambarFinal = $gambarAwal;
    }

    // Update data menu
    $updateMenu = "UPDATE menu SET Nama_Menu = ?, Deskripsi_Menu = ?, Harga_Menu = ?, Gambar_Menu = ? WHERE ID_Menu = ?";
    $stmt = $conn->prepare($updateMenu);
    $stmt->bind_param("ssdsi", $nama_menu, $deskripsi_menu, $harga_menu, $gambarFinal, $id);

    if ($stmt->execute()) {
        echo "Menu berhasil diperbarui.";
        header('Location: menuList.php'); // Redirect ke halaman daftar menu (ubah sesuai kebutuhan)
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/PENJUAL/tambahMenu.css">
    <title>Edit Menu</title>
</head>
<body>
    <div class="container">
        <h1>Edit menu</h1>
        <div class="content">
            <h2>Info item</h2>
            <h3>Edit foto</h3>
            <p>Foto yang bagus akan meningkatkan pembelian</p>
            <div class="photo-upload">
                <div class="photo-placeholder">
                    <img src="../../ASSETS/MENU/<?= htmlspecialchars($menu['Gambar_Menu']) ?>" alt="<?= htmlspecialchars($menu['Nama_Menu']) ?>">
                </div>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nama Menu</label>
                    <input type="text" name="Nama_Menu" value="<?= htmlspecialchars($menu['Nama_Menu']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi Menu</label>
                    <input type="text" name="Deskripsi_Menu" value="<?= htmlspecialchars($menu['Deskripsi_Menu']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Harga Menu</label>
                    <input type="number" name="Harga_Menu" value="<?= htmlspecialchars($menu['Harga_Menu']) ?>" required>
                </div>
                <input type="file" name="gambar_menu" id="gambar_menu" style="display: none;">
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
    <script src="../../SCRIPT/PENJUAL/tambahMenu.js"></script>
</body>
</html>
