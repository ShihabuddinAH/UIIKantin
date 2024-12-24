<?php
session_start();
include '../connect.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['ID_Pengguna'])) {
    die("ID tidak ditemukan.");
}

$id = $_GET['ID_Pengguna'];

$sql = "SELECT * FROM user WHERE ID_Pengguna = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows == 0) {
    die("Pengguna tidak ditemukan.");
}

$user = $result->fetch_assoc();
$foto_profile = $user['foto_profile'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = $_POST['nama'];
    $email = $_POST['email'];
    $kontak = $_POST['kontak'];
    $password = $_POST['password'];

    if (isset($_FILES['foto_profile']) && $_FILES['foto_profile']['error'] == 0) {
        $target_dir = "../../ASSETS/PROFILE/";
        $target_file = $target_dir . basename($_FILES['foto_profile']['name']);
        if (move_uploaded_file($_FILES['foto_profile']['tmp_name'], $target_file)) {
            $foto_profile = $_FILES['foto_profile']['name'];
        } else {
            echo "Error uploading file.";
        }
    }

    $sql_update = "UPDATE user SET 
                   nama = ?, 
                   email = ?, 
                   kontak = ?, 
                   foto_profile = ?";

    $params = [$nama_lengkap, $email, $kontak, $foto_profile];
    $types = "ssss";

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql_update .= ", password = ?";
        $params[] = $hashed_password;
        $types .= "s";
    }

    $sql_update .= " WHERE ID_Pengguna = ?";
    $params[] = $id;
    $types .= "i";

    $stmt = $conn->prepare($sql_update);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param($types, ...$params);

    if (!$stmt->execute()) {
        die("Query error: " . $stmt->error);
    }

    $_SESSION['email'] = $email;
    $_SESSION['kontak'] = $kontak;
    $_SESSION['nama'] = $nama_lengkap;

    header("Location: profile.php");
    exit();
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
              <?php
              // Check if $foto_profile is set and not empty
              if (isset($foto_profile) && !empty($foto_profile)) {
                  $profile_image = htmlspecialchars($foto_profile);
              } else {
                  // Use default image if $foto_profile is not set or empty
                  $profile_image = 'profile.jpeg'; // Make sure this image exists in the specified directory
              }
              ?>
              <img src="../../ASSETS/PROFILE/<?php echo $profile_image; ?>" alt="Profile Picture">
            <div class="edit-icon">✏️</div>
          </div>
          <form method="POST" enctype="multipart/form-data">
            <!-- Tambahkan input file ke dalam form -->
            <input type="file" name="foto_profile" id="foto_profile" style="display: none;">
          
            <label for="nama">Full Name</label>
            <input type="text" id="nama" name="nama" value="<?= $user['nama'] ?>" required/>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= $user['email'] ?>" required/>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Ubah Password Anda"/>

            <label for="kontak">No Telp</label>
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
    <script src="../../SCRIPT/PEMBELI/profileEdit.js"></script>
  </body>
</html>
