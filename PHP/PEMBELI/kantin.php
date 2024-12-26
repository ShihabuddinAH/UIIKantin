<?php
include '../connect.php';

// Ambil data kantin dari database
$result = $conn->query("SELECT * FROM kantin");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kantin</title>
    <link rel="stylesheet" href="../../CSS/PEMBELI/utama.css">
</head>

<body>
    <header class="header">
        <div class="container header-container">
        <h1 class="site-title">Daftar Kantin</h1>
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
    <!-- Section: Kantin Favorit -->
    <section class="kantin-favorit">
      <div class="container">
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
                </div>
              </a>
            <?php endwhile; ?>
        </div>
      </div>
    </section>
  </main>
  
  <!-- Footer -->
  <footer class="footer">
    <div class="container footer-container">
      <p>&copy; 2024 UIIKantin. All rights reserved.</p>
    </div>
  </footer>
</body>
</html>
