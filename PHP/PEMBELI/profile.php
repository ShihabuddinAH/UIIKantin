<?php
session_start();
include '../connect.php'; 

// Check if user is logged in
if (!isset($_SESSION['username'])) {
  header('Location: ../LOGIN/login.php');
  exit();
}

$username = $_SESSION['username'];

$query = "SELECT ID_Pengguna, email, kontak, nama, foto_profile FROM user WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();
$conn->close();

// Check if user data is retrieved successfully
if ($user === null) {
  echo "User data not found for username: " . htmlspecialchars($username);
  exit(); // Stop script execution if no user data is found
}

$foto_profile = ($user['foto_profile']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Saya</title>
    <link rel="stylesheet" href="../../CSS/PEMBELI/profile.css">
</head>

<body>
        <!-- Header -->
  <header class="header">
    <div class="container header-container">
      <h1 class="site-title">Pengaturan Profile</h1>
      <nav class="navbar">
        <ul class="navbar-list">
          <li><a href="kantin.php" class="navbar-link">Kantin</a></li>
          <li><a href="status.html" class="navbar-link">Riwayat Pesanan</a></li>
          <li><a href="profile.php" class="navbar-link">Profile</a></li>
        </ul>
        <div class="search-box">
          <input type="text" placeholder="Search in site" class="search-input">
          <button class="search-button">
            <img src="assets/search.png" alt="Search" class="search-icon">
          </button>
        </div>
      </nav>
    </div>
  </header>
  <main>
  <div class="container">
        <!-- Profile Section -->
        <section class="profile-section">
            <div class="profile-header">
                <div class="profile-picture">
                    <!-- Gambar diambil dari database -->
                    <img src="../../ASSETS/PROFILE/<?php echo htmlspecialchars($foto_profile); ?>" alt="Profile Picture">
                </div>
                <div class="profile-info">
                    <!-- Data diambil dari database -->
                    <h3><?php echo htmlspecialchars($user['nama']); ?></h3>
                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                    <p><?php echo htmlspecialchars($user['kontak']); ?></p>
                </div>
            </div>

            <div class="profile-actions">
                <a href="edit.php?ID_Pengguna=<?= $user['ID_Pengguna'] ?>"><button><span>&#128100;</span> Edit Profile</button></a>
                <a href=""><button><span>&#128276;</span> Notifikasi</button></a>
                <a href="../LOGIN/logout.php"><button><span>&#10150;</span> Keluar Akun</button></a>
            </div>
        </section>

        <!-- Recent Canteens Section -->
        <section class="recent-section">
            <h2>Kantin Terakhir di Lihat</h2>
            <div class="canteen-images">
                <img src="https://via.placeholder.com/300x200" alt="Canteen 1">
                <img src="https://via.placeholder.com/300x200" alt="Canteen 2">
                <img src="https://via.placeholder.com/300x200" alt="Canteen 3">
            </div>
        </section>
    </div>
  </main>
  <!-- Footer -->
  <footer class="footer">
    <div class="container footer-container">
      <p>&copy; 2024 UIIKantin. All rights reserved.</p>
    </div>
  </footer>
</body>
</html>
