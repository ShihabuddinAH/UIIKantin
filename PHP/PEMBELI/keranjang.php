<?php
session_start();
include '../connect.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: ../LOGIN/login.php');
    exit();
}

$ID_Warung = $_GET['ID_Warung'];

// Get user ID
$stmt = $conn->prepare("SELECT id_pengguna FROM user WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$ID_Pengguna = $user['id_pengguna'];

// Fix SQL query syntax
$query = "SELECT keranjang.ID_Keranjang, 
                 menu.Nama_Menu, 
                 menu.Harga_Menu, 
                 keranjang.Jumlah_Pesanan,
                 keranjang.Subtotal_Harga AS Total
          FROM keranjang 
          JOIN menu ON keranjang.ID_Menu = menu.ID_Menu 
          WHERE keranjang.ID_Pengguna = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $ID_Pengguna);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Keranjang Pesanan</title>
  <link rel="stylesheet" href="../../CSS/PEMBELI/keranjang.css">
</head>
<body>
  <div class="container">
    <!-- Daftar Pesanan -->
    <section class="cart-items" id="cart-items">
      <!-- Item pesanan akan dimuat secara dinamis menggunakan JavaScript -->
      <?php if ($result->num_rows > 0): ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Nama Menu</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Di dalam loop while untuk menampilkan items:
                    $grandTotal = 0;
                    while ($row = $result->fetch_assoc()): 
                        // Hitung total per item
                        $itemTotal = $row['Harga_Menu'] * $row['Jumlah_Pesanan'];
                        $grandTotal += $itemTotal;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($row['Nama_Menu']) ?></td>
                            <td>Rp <?= number_format($row['Harga_Menu'], 0, ',', '.') ?></td>
                            <td>
                                <div class="quantity-control">
                                    <button class="btn-qty" data-cartid="<?= $row['ID_Keranjang'] ?>" data-action="decrease">-</button>
                                    <span id="quantity-<?= $row['ID_Keranjang'] ?>" class="quantity-display">
                                        <?= $row['Jumlah_Pesanan'] ?>
                                    </span>
                                    <button class="btn-qty" data-cartid="<?= $row['ID_Keranjang'] ?>" data-action="increase">+</button>
                                </div>
                            </td>
                            <td id="total-<?= $row['ID_Keranjang'] ?>">
                                Rp <?= number_format($itemTotal, 0, ',', '.') ?>
                            </td>
                            <td>
                                <button class="btn-delete" data-cartid="<?= $row['ID_Keranjang'] ?>">Hapus</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="empty-cart">Keranjang belanja kosong</p>
            <a href="utama.php" class="btn-shopping">Mulai Belanja</a>
        <?php endif; ?>
    </section>

    <!-- Bagian summary -->
<section class="order-summary">
    <a href="menu.php?ID_Warung=<?= $ID_Warung ?>" style="float: right;">
        <button>Batal</button>
    </a>
    <h2>Pesanan</h2>
    <div class="summary-item">
        <span>Subtotal</span>
        <span id="subtotal">Rp <?= number_format($grandTotal, 0, ',', '.') ?></span>
    </div>
    <div class="summary-item">
        <span>Biaya Admin</span>
        <span id="admin-fees">Rp 2.000</span>
    </div>
    <div class="summary-item total">
        <span>Total</span>
        <span id="total">Rp <?= number_format($grandTotal + 2000, 0, ',', '.') ?></span>
    </div>
    <button class="checkout-button">Checkout</button>
</section>
  </div>
  <script src="../../SCRIPT/PEMBELI/keranjang.js"></script>
</body>
</html>
