<?php
include '../connect.php'; // Pastikan file connect.php ada dan berisi kode untuk koneksi ke database
include '../LOGIN/session.php'; // Pastikan file session.php ada dan berisi kode untuk memulai session

if (!isset($_SESSION['username'])) {
    // Jika username tidak ada dalam sesi, arahkan ke login
    header('Location: ../LOGIN/login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/PENJUAL/list.css">
    <title>Riwayat Pesanan</title>
</head>
<body>
<div class="dashboard-container">
        <div class="main-content">
            <h1>Riwayat Pesanan</h1>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Nama Customer</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#001</td>
                        <td>John Doe</td>
                        <td>2023-10-01</td>
                        <td>Terkirim</td>
                        <td>Rp50,000</td>
                    </tr>
                    <tr>
                        <td>#002</td>
                        <td>Jane Smith</td>
                        <td>2023-10-02</td>
                        <td>Tertunda</td>
                        <td>Rp30,000</td>
                    </tr>
                    <tr>
                        <td>#003</td>
                        <td>Michael Brown</td>
                        <td>2023-10-03</td>
                        <td>Dibatalkan</td>
                        <td>Rp20,000</td>
                    </tr>
                    <tr>
                        <td>#004</td>
                        <td>Amad Dialo</td>
                        <td>2023-10-5</td>
                        <td>Sukses</td>
                        <td>Rp60,000</td>
                    </tr>
                    <tr>
                        <td>#005</td>
                        <td>Antony</td>
                        <td>2023-11-21</td>
                        <td>Sukses</td>
                        <td>Rp25,000</td>
                    </tr>
                    <tr>
                        <td>#006</td>
                        <td>Messi</td>
                        <td>2024-12-11</td>
                        <td>Sukses</td>
                        <td>Rp100,000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>