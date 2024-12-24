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
// $foto_profile = ($user['foto_profile']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = $_POST['nama'];
    $email = $_POST['email'];
    $kontak = $_POST['kontak'];

    // Handle file upload
    if (isset($_FILES['foto_profile']) && $_FILES['foto_profile']['error'] == 0) {
      $target_dir = "../../ASSETS/PROFILE/";
      $target_file = $target_dir . basename($_FILES['foto_profile']['name']);
      if (move_uploaded_file($_FILES['foto_profile']['tmp_name'], $target_file)) {
          $foto_profile = $_FILES['foto_profile']['name'];
      } else {
          echo "Error uploading file.";
      }
  }

  // Update user info
  $sql_update = "UPDATE user SET 
                 nama = ?, 
                 email = ?,
                 kontak = ?, 
                 foto_profile = ? 
                 WHERE ID_Pengguna = ?";
  $stmt = $conn->prepare($sql_update);
  $stmt->bind_param("ssssi", $nama_lengkap, $email, $kontak, $foto_profile, $id);

  if ($stmt->execute()) {
      // Update session data with new values after successful update
      $_SESSION['email'] = $email;        // Update session with new email
      $_SESSION['kontak'] = $kontak;      // Update session with new contact info
      $_SESSION['nama'] = $nama_lengkap;  // Update session with new full name

      header("Location: profile.php");
      exit();
  } else {
      echo "Error: " . $stmt->error;
  }

  $stmt->close();
  $conn->close();
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
            <input type="file" name="foto_profile" id="foto_profile" style="display: none;" required>
          
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
    <script src="../../SCRIPT/PEMBELI/profileEdit.js"></script>
  </body>
</html>
