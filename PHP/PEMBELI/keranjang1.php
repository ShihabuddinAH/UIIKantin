<?php
session_start();
include '../connect.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: ../LOGIN/login.php');
    exit();
}

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
        <h2>Keranjang Belanja</h2>
        
        <?php if ($result->num_rows > 0): ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $grandTotal = 0;
                    while ($row = $result->fetch_assoc()): 
                        $grandTotal += $row['Total'];
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($row['Nama_Menu']) ?></td>
                            <td>Rp <?= number_format($row['Harga_Menu'], 0, ',', '.') ?></td>
                            <td>
                                <div class="quantity-control">
                                    <button class="btn-qty" onclick="updateQuantity(<?= $row['ID_Keranjang'] ?>, 'decrease')">-</button>
                                    <span><?= $row['Jumlah_Pesanan'] ?></span>
                                    <button class="btn-qty" onclick="updateQuantity(<?= $row['ID_Keranjang'] ?>, 'increase')">+</button>
                                </div>
                            </td>
                            <td>Rp <?= number_format($row['Total'], 0, ',', '.') ?></td>
                            <td>
                                <button onclick="removeItem(<?= $row['ID_Keranjang'] ?>)" class="btn-delete">Hapus</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Total Belanja:</strong></td>
                        <td colspan="2"><strong>Rp <?= number_format($grandTotal, 0, ',', '.') ?></strong></td>
                    </tr>
                </tfoot>
            </table>
            
            <div class="cart-actions">
                <button onclick="window.location.href='checkout.php'" class="btn-checkout">Checkout</button>
            </div>
        <?php else: ?>
            <p class="empty-cart">Keranjang belanja kosong</p>
            <a href="utama.php" class="btn-shopping">Mulai Belanja</a>
        <?php endif; ?>
    </div>

    <script src="../../SCRIPT/PEMBELI/keranjang.js"></script>
</body>
</html>