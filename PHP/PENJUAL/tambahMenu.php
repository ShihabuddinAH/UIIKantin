<?php
session_start();
include '../connect.php'; // Pastikan file connect.php ada dan berisi kode untuk koneksi ke database

if (!isset($_SESSION['username'])) {
    // Jika username tidak ada dalam sesi, arahkan ke login
    header('Location: ../LOGIN/login.php');
    exit();
}

$username = $_SESSION['username'];

// Mengambil ID_Warung dari database
$sql = "SELECT ID_Warung FROM kantin WHERE id_pengguna = (SELECT id_pengguna FROM user WHERE username = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $ID_Warung = $row['ID_Warung'];
} else {
    echo "ID_Warung tidak ditemukan.";
    exit();
}
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_menu = $_POST['nama_menu'];
    $deskripsi_menu = $_POST['deskripsi_menu'];
    $harga_menu = $_POST['harga_menu'];
    $gambar_menu = $_FILES['gambar_menu']['name'];
    $target_dir = "../../ASSETS/MENU/";
    $target_file = $target_dir . basename($gambar_menu);

    // Pindahkan file gambar ke folder target
    if (move_uploaded_file($_FILES['gambar_menu']['tmp_name'], $target_file)) {
        // Simpan data ke tabel menu
        $sql = "INSERT INTO menu (ID_Warung, Nama_Menu, Deskripsi_Menu, Harga_Menu, Gambar_Menu) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $ID_Warung, $nama_menu, $deskripsi_menu, $harga_menu, $gambar_menu);
        if ($stmt->execute()) {
            echo "Menu berhasil ditambahkan.";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error uploading file.";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/PENJUAL/tambahMenu.css">
    <title>Tambah Menu</title>
</head>
<body>
    <div class="container">
        <h1>Tambah menu</h1>
        <div class="content">
            <h2>Info item</h2>
            <h3>Tambahkan foto</h3>
            <p>Foto yang bagus akan meningkatkan pembelian</p>
            <div class="photo-upload">
                <div class="photo-placeholder">
                    <span>+</span>
                    <p>Tambahkan Foto</p>
                </div>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nama Menu</label>
                    <input type="text" name="nama_menu" placeholder="Nama Menu" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi Menu</label>
                    <input type="text" name="deskripsi_menu" placeholder="Deskripsi Menu" required>
                </div>
                <div class="form-group">
                    <label>Harga Menu</label>
                    <input type="text" name="harga_menu" placeholder="Harga Menu" required>
                </div>
                <!-- Tambahkan input file ke dalam form -->
                <input type="file" name="gambar_menu" id="gambar_menu" style="display: none;" required>
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
    <script src="../../SCRIPT/PENJUAL/tambahMenu.js"></script>
</body>
</html>