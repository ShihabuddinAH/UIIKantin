<?php
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $kontak = $_POST['kontak'];
    $role = $_POST['role'];
    $Nama_Warung = isset($_POST['Nama_Warung']) ? $_POST['Nama_Warung'] : null;
    $Gambar_Warung = isset($_FILES['Gambar_Warung']['name']) ? $_FILES['Gambar_Warung']['name'] : null;
    $target_dir = "../../ASSETS/KANTIN/";
    $target_file = $target_dir . basename($Gambar_Warung);

    $sql_check = "SELECT * FROM user WHERE username = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        $error = "Username sudah digunakan. Silakan pilih username lain.";
    } else {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        if ($role == 'buyer') {
            $sql_insert = "INSERT INTO user (username, email, password, kontak, role) 
                           VALUES (?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("sssss", $username, $email, $password_hashed, $kontak, $role);
            $stmt_insert->execute();
            $stmt_insert->close();
            header("Location:dashboardAdmin.php");
        } elseif ($role == 'seller') {
            if (move_uploaded_file($_FILES['Gambar_Warung']['tmp_name'], $target_file)) {
                $sql_insert = "INSERT INTO user (username, email, password, kontak, role) 
                               VALUES (?, ?, ?, ?, ?)";
                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bind_param("sssss", $username, $email, $password_hashed, $kontak, $role);
                $stmt_insert->execute();
                $last_id = $conn->insert_id;
                $stmt_insert->close();

                $sql_kantin = "INSERT INTO kantin (Nama_Warung, Gambar_Warung, rating, id_pengguna) 
                               VALUES (?, ?, 0, ?)";
                $stmt_kantin = $conn->prepare($sql_kantin);
                $stmt_kantin->bind_param("ssi", $Nama_Warung, $Gambar_Warung, $last_id);
                $stmt_kantin->execute();
                $stmt_kantin->close();
                header("Location:dashboardAdmin.php");
            } else {
                echo "Error uploading file.";
            }
        }
    }
    $stmt_check->close();
    $conn->close();
}
?>