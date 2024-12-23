<?php
session_start();
include '../connect.php';

if (!isset($_SESSION['username'])) {
    // Jika username tidak ada dalam sesi, arahkan ke login
    header('Location: ../LOGIN/login.php');
    exit();
}

$username = $_SESSION['username'];

// Mengambil nama warung dari database
$sql = "SELECT Nama_Warung FROM kantin WHERE id_pengguna = (SELECT id_pengguna FROM user WHERE username = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $Nama_Warung = $row['Nama_Warung'];
} else {
    $Nama_Warung = "Nama Warung Tidak Ditemukan";
}
$stmt->close();

// Query untuk mengambil ID_Warung
$query = "SELECT ID_Warung FROM kantin WHERE id_pengguna = (SELECT id_pengguna FROM user WHERE username = ?)";
$stmt = $conn->prepare($query);
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

// Query untuk mengambil data menu
$query_menu = "SELECT * FROM menu WHERE ID_Warung = ?";
$stmt_menu = $conn->prepare($query_menu);
$stmt_menu->bind_param("i", $ID_Warung);
$stmt_menu->execute();
$result_menu = $stmt_menu->get_result();

$menu = [];
while ($row_menu = $result_menu->fetch_assoc()) {
    $menu[] = $row_menu;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/PENJUAL/editMenu.css">
    <title>Kantin <?= htmlspecialchars($Nama_Warung) ?></title>
</head>
<body>
<div class="container">
    <div class="sidebar">
        <h4>Kantin <?= htmlspecialchars($Nama_Warung) ?></h4>
        <ul>
            <li><a href="dashboardPenjual.php" class="button">Dashboard</a></li>
            <li><a href="list.php" class="button">Order List</a></li>
            <li><a href="#">Customer</a></li>
            <li><a href="#" class="button">Reviews</a></li>
            <li><a class="active" href="#">Menu</a></li>
            <li><a href="../LOGIN/logout.php" class="logout">Logout</a></li>
        </ul>
    </div>
    <div class="content">
        <?php if (empty($menu)): ?>
            <p>Belum ada menu yang tersedia.</p>
        <?php else: ?>
            <?php foreach ($menu as $item): ?>
                <div class="card">
                    <img src="../../ASSETS/MENU/<?= htmlspecialchars($item['Gambar_Menu']) ?>" alt="<?= htmlspecialchars($item['Nama_Menu']) ?>">
                    <h5><?= htmlspecialchars($item['Nama_Menu']) ?></h5>
                    <p>Rp<?= number_format($item['Harga_Menu'], 0, ',', '.') ?></p>
                    <div class="actions">
                        <a href="editDetailMenu.php?ID_Menu=<?= $item['ID_Menu'] ?>">
                            <button class="edit">Edit</button>
                        </a>
                        <button class="hapus" style="display: none;" data-menu-id="<?= $item['ID_Menu'] ?>">Hapus</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <div class="buttons">
            <button class="hapus-bawah">Hapus</button>
            <button class="tambahMenu">+</button>
        </div>
    </div>
</div>
<script src="../../SCRIPT/PENJUAL/editMenu.js"></script>
</body>
</html>