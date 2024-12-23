<?php
include '../connect.php'; // Pastikan file connect.php ada dan berisi kode untuk koneksi ke database
include '../LOGIN/session.php'; // Pastikan file session.php ada dan berisi kode untuk memulai session

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/PENJUAL/list.css">
    <title>Kantin <?= htmlspecialchars($Nama_Warung) ?></title>
</head>
<body>
<div class="dashboard-container">
        <div class="sidebar">
            <h4>Kantin <?= htmlspecialchars($Nama_Warung) ?></h4>
            <ul>
                <li><a href="dashboardPenjual.php" class="button">Dashboard</a></li>
                <li><a class="active" href="#">Order List</a></li>
                <li><a href="#">Customer</a></li>
                <li><a href="#">Reviews</a></li>
                <li><a href="editmenu.php" class="button">Menu</a></li>
                <li><a href="../LOGIN/logout.php" class="logout">Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>List Pesanan</h1>
            <table>
                <thead>
                    <tr>
                        <th>Nama Customer</th>
                        <th>Status</th>
                        <th>Jumlah Pesanan</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
    <?php
    // Ensure $username is set
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
    } else {
        echo "<tr><td colspan='5'>Username tidak ditemukan.</td></tr>";
        exit();
    }

    $sql_orders = "SELECT k.ID_Keranjang, u.nama AS customer_name, k.status, k.Jumlah_Pesanan AS jumlah_pesanan, 
                    k.Subtotal_Harga AS total 
                    FROM keranjang k 
                    JOIN user u ON k.ID_Pengguna = u.id_pengguna
                    JOIN menu m ON k.ID_Menu = m.ID_Menu
                    JOIN kantin w ON m.ID_Warung = w.ID_Warung
                    WHERE w.id_pengguna = (SELECT id_pengguna FROM user WHERE username = ?) 
                    AND k.status != 'belum checkout'
                    GROUP BY k.ID_Keranjang, u.nama, k.status, k.Jumlah_Pesanan, k.Subtotal_Harga";
    $stmt_orders = $conn->prepare($sql_orders);
    $stmt_orders->bind_param("s", $username);
    $stmt_orders->execute();
    $result_orders = $stmt_orders->get_result();

    if ($result_orders->num_rows > 0) {
        while ($row_orders = $result_orders->fetch_assoc()) {
            echo "<tr id='order-{$row_orders['ID_Keranjang']}'>";
            echo "<td>" . htmlspecialchars($row_orders['customer_name']) . "</td>";
            echo "<td id='status-{$row_orders['ID_Keranjang']}'>" . htmlspecialchars($row_orders['status']) . "</td>";
            echo "<td>" . htmlspecialchars($row_orders['jumlah_pesanan']) . "</td>";
            echo "<td>Rp" . htmlspecialchars(number_format($row_orders['total'], 0, ',', '.')) . "</td>";
            echo "<td id='actions-{$row_orders['ID_Keranjang']}'>";
            if ($row_orders['status'] == 'checkout') {
                echo '<button class="terima" onclick="updateStatus(' . $row_orders['ID_Keranjang'] . ', \'diterima\')">Terima</button>';
                echo '<button class="tolak" onclick="updateStatus(' . $row_orders['ID_Keranjang'] . ', \'ditolak\')">Tolak</button>';
            } elseif ($row_orders['status'] == 'diterima') {
                echo '<button class="dibuat" onclick="updateStatus(' . $row_orders['ID_Keranjang'] . ', \'dibuat\')">Dibuat</button>';
            } elseif ($row_orders['status'] == 'dibuat') {
                echo '<button class="selesai" onclick="updateStatus(' . $row_orders['ID_Keranjang'] . ', \'selesai\')">Selesai</button>';
            }
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>Tidak ada pesanan</td></tr>";
    }
    $stmt_orders->close();
    ?>
</tbody>
</table>
</div>
</div>
<script>
function updateStatus(orderId, status) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "update_status.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById('status-' + orderId).innerText = status;
            const actionsCell = document.getElementById('actions-' + orderId);
            if (status === 'diterima') {
                actionsCell.innerHTML = '<button class="dibuat" onclick="updateStatus(' + orderId + ', \'dibuat\')">Dibuat</button>';
            } else if (status === 'dibuat') {
                actionsCell.innerHTML = '<button class="selesai" onclick="updateStatus(' + orderId + ', \'selesai\')">Selesai</button>';
            } else {
                actionsCell.innerHTML = '';
            }
        }
    };
    xhr.send("orderId=" + orderId + "&status=" + status);
}
</script>
</body>
</html>