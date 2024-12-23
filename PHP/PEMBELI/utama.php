<?php
include '../LOGIN/session.php';
include '../connect.php';

// Ambil data kantin dari database
$result = $conn->query("SELECT * FROM kantin");

// Check if username is set in session and verify role
if (isset($_SESSION['username'])) {
    $stmt = $conn->prepare('SELECT role FROM user WHERE username = ?');
    $stmt->bind_param('s', $_SESSION['username']);
    $stmt->execute();
    $result_role = $stmt->get_result();
    
    if ($row = $result_role->fetch_assoc()) {
        $role = $row['role'];
        if ($role === 'admin') {
            header('Location: ../ADMIN/dashboardAdmin.php');
            exit();
        } elseif ($role === 'seller') {
            header('Location: ../PENJUAL/dashboardPenjual.php');
            exit();
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Platform untuk menjelajahi kantin favorit di Universitas Islam Indonesia.">
  <meta name="keywords" content="UII, Kantin, Makanan, Universitas Islam Indonesia">
  <title>UII Kantin - Beranda</title>

  <!-- Favicon -->
  <link rel="icon" href="favicon.ico" type="image/x-icon">

  <!-- CSS -->
  <link rel="stylesheet" href="../../CSS/PEMBELI/utama.css">
</head>

<body>
  <!-- Header -->
  <header class="header">
    <div class="container header-container">
      <h1 class="site-title">Halaman Utama UIIKantin</h1>
      <nav class="navbar">
        <ul class="navbar-list">
          <li><a href="kantin.php" class="navbar-link">Kantin</a></li>
          <li><a href="riwayatPesanan.php" class="navbar-link">Riwayat Pesanan</a></li>
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

  <!-- Main Content -->
  <main>
    <!-- Hero Section -->
    <section class="hero">
      <div class="container hero-container">
        <div class="hero-text">
          <h2>Selamat Datang di <span class="highlight">UIIKantin</span></h2>
          <p>Jelajahi Menu Makanan yang Enak dan Terjangkau</p>
        </div>
      </div>
    </section>

    <!-- Section: Kantin Favorit -->
    <section class="kantin-favorit">
      <div class="container">
        <h2 class="section-title">Kantin Favorit</h2>
        <div class="kantin-list">
        <?php while ($row = $result->fetch_assoc()): ?>
              <a href="menu.php?ID_Warung=<?= $row['ID_Warung'] ?>" class="kantin-link">
                  <div class="kantin-card">
                      <div class="kantin-image" style="background-image: url('../../ASSETS/KANTIN/<?= $row['Gambar_Warung'] ?>');"></div>
                      <div class="kantin-info">
                          <h3><?= htmlspecialchars($row['Nama_Warung']) ?></h3>
                          <p>Rating: <?= $row['rating'] ?></p>
                      </div>
                  </div>
              </a>
          <?php endwhile; ?>
        </div>
      </div>
    </section>
  </main>

<!-- Bottom Navbar -->
<nav class="bottom-navbar">
  <div class="container bottom-navbar-container">
    <!-- Kolom 1: Brand dan Alamat -->
    <div class="bottom-navbar-column">
      <h4 class="brand">Tap&Eat</h4>
      <p>Fakultas Teknologi Industri<br>Universitas Islam Indonesia<br>Jl. Kaliurang 14,5</p>
    </div>

    <!-- Kolom 2: Links -->
    <div class="bottom-navbar-column">
      <h4>Links</h4>
      <ul class="bottom-navbar-links">
        <li><a href="home.html">Home</a></li>
        <li><a href="kantin.html">Canteen</a></li>
        <li><a href="about.html">About</a></li>
        <li><a href="contact.html">Contact</a></li>
      </ul>
    </div>

    <!-- Kolom 3: Help -->
    <div class="bottom-navbar-column">
      <h4>Help</h4>
      <ul class="bottom-navbar-links">
        <li><a href="#">Payment Options</a></li>
        <li><a href="#">Returns</a></li>
        <li><a href="#">Privacy Policies</a></li>
      </ul>
    </div>

    <!-- Kolom 4: Newsletter -->
    <div class="bottom-navbar-column">
      <h4>See What Next?</h4>
      <div class="newsletter">
        <input type="email" placeholder="Enter Your Email Address" />
        <button>Subscribe</button>
      </div>
    </div>
  </div>
</nav>


  <!-- Footer -->
  <footer class="footer">
    <div class="container footer-container">
      <p>&copy; 2024 UIIKantin. All rights reserved.</p>
    </div>
  </footer>
</body>
</html>
