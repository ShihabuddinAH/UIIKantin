<?php
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $kontak = $_POST['kontak'];
    $role = 'buyer';
    // $confirm_password = $_POST['confirm_password'];

    if ($password !== $password) {
        $error = "Password dan konfirmasi password tidak cocok!";
    } else {
        $sql_check = "SELECT * FROM user WHERE username = '$username'";
        $result = $conn->query($sql_check);

        if ($result->num_rows > 0) {
            $error = "Username sudah digunakan. Silakan pilih username lain.";
        } else {
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            $sql_insert = "INSERT INTO user (username, email, password, kontak, role) 
                           VALUES ('$username', '$email', '$password_hashed', '$kontak', '$role')";
            if ($conn->query($sql_insert) === TRUE) {
                header("Location:../LOGIN/login.php");
                exit();
            } else {
                $error = "Error: " . $conn->error;
            }
        }
    }
    $conn->close();
}
?>