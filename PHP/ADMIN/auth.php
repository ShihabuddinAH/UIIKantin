<?php
session_start();
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mencari admin berdasarkan username
    $sql = "SELECT * FROM user WHERE username = '$username' AND role = 'admin'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Update status user menjadi aktif
            $updateStatusSql = "UPDATE user SET status = 'aktif' WHERE username = '$username'";
            $conn->query($updateStatusSql);

            // Arahkan ke dashboard admin
            header('Location: ../ADMIN/dashboardAdmin.php');
            exit();
        }
    }

    // Jika login gagal, tampilkan error
    echo "<script>alert('Username atau password salah'); window.location.href = '../../loginAdmin.php';</script>";
    exit();
}
?>
