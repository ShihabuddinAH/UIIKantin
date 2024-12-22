<?php
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $kontak = $_POST['kontak'];
    $Nama_Warung = $_POST['Nama_Warung'];
    $Gambar_Warung = $_FILES['Gambar_Warung']['name'];
    $target_dir = "../../ASSETS/KANTIN/";
    $target_file = $target_dir . basename($Gambar_Warung);

    $sql_check = "SELECT * FROM user WHERE username = '$username'";
    $result = $conn->query($sql_check);
        if ($result->num_rows > 0) {
            $error = "Username sudah digunakan. Silakan pilih username lain.";
        } else {
            // Pindahkan file gambar ke folder target
            if (move_uploaded_file($_FILES['Gambar_Warung']['tmp_name'], $target_file)) {
                // Simpan data ke tabel user
                $sql_user = "INSERT INTO user (username, email, password, kontak, role) VALUES ('$username', '$email', '$password', '$kontak', 'seller')";
                if ($conn->query($sql_user) === TRUE) {
                    $last_id = $conn->insert_id;
                    // Simpan data ke tabel kantin
                    $sql_kantin = "INSERT INTO kantin (Nama_Warung, Gambar_Warung, rating, id_pengguna) VALUES ('$Nama_Warung', '$Gambar_Warung', 0, '$last_id')";
                    if ($conn->query($sql_kantin) === TRUE) {
                        header("Location: ../LOGIN/login.php");
                    } else {
                        echo "Error: " . $sql_kantin . "<br>" . $conn->error;
                    }
                } else {
                    echo "Error: " . $sql_user . "<br>" . $conn->error;
                }
            } else {
                echo "Error uploading file.";
            }
        }

    

    // Tutup koneksi
    $conn->close();
}
?>