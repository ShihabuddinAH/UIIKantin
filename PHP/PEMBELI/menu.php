<?php
include '../connect.php';

// Ambil ID kantin dari URL
$ID_Warung = isset($_GET['ID_Warung']) ? (int)$_GET['ID_Warung'] : 0;

// Ambil detail kantin
$kantin = $conn->query("SELECT * FROM kantin WHERE ID_Warung = $ID_Warung")->fetch_assoc();

// Query untuk mengambil menu dari database
$sql = "SELECT * FROM menu WHERE ID_Warung = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ID_Warung);
$stmt->execute();
$menu = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Platform untuk menjelajahi kantin favorit di Universitas Islam Indonesia.">
  <meta name="keywords" content="UII, Kantin, Makanan, Universitas Islam Indonesia">
  <title>UIIKantin</title>

  <!-- Favicon -->
  <link rel="icon" href="favicon.ico" type="image/x-icon">

  <!-- CSS -->
  <link rel="stylesheet" href="../../CSS/PEMBELI/menu.css">
</head>

<body>
  <!-- Header -->
  <header class="header">
    <div class="container header-container">
      <h1 class="site-title">Menu <?= htmlspecialchars($kantin['Nama_Warung']) ?></h1>
      <nav class="navbar">
        <div class="search-box">
          <input type="text" placeholder="Search in site" class="search-input">
          <button class="search-button">
            <img src="assets/search.png" alt="Search" class="search-icon">
          </button>
        </div>
      </nav>
    </div>
  </header>

  <div class="container">
    <div class="products">
      <?php if ($menu->num_rows > 0): ?>
        <?php while ($item = $menu->fetch_assoc()): ?>
          <div class="card">
          <div class="menu-card">
                <img src="../../ASSETS/MENU/<?= $item['Gambar_Menu'] ?>" alt="<?= htmlspecialchars($item['Nama_Menu']) ?>">
                <div class="menu-info">
                    <h3><?= htmlspecialchars($item['Nama_Menu']) ?></h3>
                    <p>Harga: Rp. <?= number_format($item['Harga_Menu'], 0, ',', '.') ?></p>
                </div>
            </div>
            <form class="actions" action="add_to_cart.php" method="post">
              <input type="hidden" name="ID_Menu" value="<?= $item['ID_Menu'] ?>">
              <input type="hidden" name="ID_Warung" value="<?= $ID_Warung ?>">
              <div class="quantity">
                <input type="number" name="Jumlah_Pesanan" value="1" min="1">
              </div>
              <button type="submit" class="add-button">Add</button>
            </form>
          </div>
        <?php endwhile; ?>
        <?php else: ?>
            <p>Belum Terdapat Menu</p>
        <?php endif; ?>
    </div>
  </div>

  <!-- Tombol Keranjang -->
  <button id="cart-button">
    <a href="keranjang.php">
      <img src="../../ASSETS/PEMBELI/keranjang.png" alt="Cart Icon">
      <p>0</p>
    </a>
  </button>

  <script src="../../SCRIPT/PEMBELI/menu.js"></script>  
</body>
</html>
