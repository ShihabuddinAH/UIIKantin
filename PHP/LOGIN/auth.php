<?php
session_start();
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $sql = "SELECT * FROM user WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            $updateStatusSql = "UPDATE user SET status = 'aktif' WHERE username = '$username'";
            $conn->query($updateStatusSql);

            if ($role === $user['role']) {
                if ($role === 'buyer') {
                    header('Location: ../PEMBELI/utama.php');
                } elseif ($role === 'seller') {
                    header('Location: ../PENJUAL/dashboardPenjual.php');
                } 
            } else {
                    echo "<script>alert('Role tidak sesuai'); window.location.href = 'login.php';</script>";
                    session_unset(); 
                    session_destroy();  
                    exit();
                }
        }
    } else {
        echo "<script>alert('Username atau password salah'); window.location.href = 'login.php';</script>";
        exit();
    }
}
?>