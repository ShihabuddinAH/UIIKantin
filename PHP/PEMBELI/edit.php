<?php
session_start();
include '../connect.php';

if (!isset($_GET['ID_Pengguna'])) {
    die("ID tidak ditemukan.");
}

$id = $_GET['ID_Pengguna'];

$sql = "SELECT * FROM user WHERE ID_Pengguna = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Pengguna tidak ditemukan.");
}

$user = $result->fetch_assoc();
$foto_profile = ($user['foto_profile']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = $_POST['nama'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $kontak = $_POST['kontak'];

    $sql_update = "UPDATE user SET 
                   nama = '$nama_lengkap', 
                   email = '$email', 
                   kontak = '$kontak' 
                   WHERE ID_Pengguna = $id";

    if ($conn->query($sql_update) === TRUE) {
      // Update session data with new values after successful update
      $_SESSION['email'] = $email;        // Update session with new email
      $_SESSION['kontak'] = $kontak;      // Update session with new contact info
      $_SESSION['nama'] = $nama_lengkap;  // Update session with new full name

        header("Location: profile.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../../CSS/PEMBELI/profileEdit.css" />
  </head>
  <body>
    <header class="header">
      <div class="container header-container">
        <h1 class="site-title">Edit Profile</h1>
      </div>
    </header>
    <main>
      <div class="container">
        <div class="main-content">
          <div class="profile-pic">
          <img src="/PABW/PROYEK/EXPO/ASSETS/PROFILE/<?php echo htmlspecialchars($foto_profile); ?>" alt="Profile Picture">
            <div class="edit-icon">✏️</div>
          </div>
          <form method="POST">
            <label for="full-name">Full Name</label>
            <input type="text" id="nama" name="nama" value="<?= $user['nama'] ?>" required/>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= $user['email'] ?>" required/>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Ubah Password Anda"/>

            <label for="phone">No Telp</label>
            <input type="tel" id="kontak" name="kontak" value="<?= $user['kontak'] ?>" required/>

            <div class="buttons">
              <a href="profile.php">
                <button type="button" class="cancel">Batalkan</button>
              </a>
              <input type="submit" value="Simpan" class="save" />
            </div>
          </form>
        </div>
      </div>
    </main>
  </body>
</html>
