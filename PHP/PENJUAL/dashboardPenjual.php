<?php
include '../connect.php'; // Pastikan file connect.php ada dan berisi kode untuk koneksi ke database
include '../LOGIN/session.php'; // Pastikan file session.php ada dan berisi kode untuk memulai session

if (!isset($_SESSION['username'])) {
    // Jika username tidak ada dalam sesi, arahkan ke login
    header('Location: ../LOGIN/login.php');
    exit();
}

$username = $_SESSION['username'];

// Mengambil nama warung dari database
$sql = "SELECT Nama_Warung FROM kantin WHERE id_pengguna = (SELECT id_pengguna FROM user WHERE username = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $Nama_Warung = $row['Nama_Warung'];
} else {
    $Nama_Warung = "Nama Warung Tidak Ditemukan";
}
$stmt->close();

// Mengambil jumlah menu dari database
$sql = "SELECT COUNT(*) as total_menu FROM menu WHERE ID_Warung = (SELECT ID_Warung FROM kantin WHERE id_pengguna = (SELECT id_pengguna FROM user WHERE username = ?))";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Mengambil data
    $row = $result->fetch_assoc();
    $total_menu = $row['total_menu'];
} else {
    $total_menu = 0;
}
$stmt->close();

// Mengambil jumlah total order dari database
$sql = "SELECT Total_Order as total_order FROM kantin WHERE ID_Warung = (SELECT ID_Warung FROM kantin WHERE id_pengguna = (SELECT id_pengguna FROM user WHERE username = ?))";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Mengambil data
    $row = $result->fetch_assoc();
    $total_order = $row['total_order'];
} else {
    $total_order = 0;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/PENJUAL/dashboard.css">
    <title>Kantin <?= htmlspecialchars($Nama_Warung) ?></title>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <h4>Kantin <?= htmlspecialchars($Nama_Warung) ?></h4>
            <ul>
                <li><a class="active" href="#">Dashboard</a></li>
                <li><a href="list.php" class="button">Order List</a></li>
                <li><a href="../EROR/eror.html">Customer</a></li>
                <li><a href="../EROR/eror.html" class="button">Reviews</a></li>
                <li><a href="editmenu.php" class="button">Menu</a></li>
                <li><a href="../LOGIN/logout.php" class="logout">Logout</a></li>
            </ul>
        </div>
        <!-- Main Content -->
        <main class="content">
            <!-- Statistics Section -->
            <section class="stats">
                <div class="card">
                    <h3><?= $total_order ?></h3>
                    <p>Total Order</p>
                    <p>4% (30days)</p>
                </div>
                <div class="card">
                    <h3>375</h3>
                    <p>Total Delivered</p>
                    <p>4% (30days)</p>
                </div>
                <div class="card">
                    <h3><?= $total_menu ?></h3>
                    <p>Total Menu</p>
                </div>
                <div class="card">
                    <h3>Rp128</h3>
                    <p>Total Revenue</p>
                    <p>12% (30days)</p>
                </div>
            </section>

            <!-- Charts Section -->
            <section class="charts">
                <h4>Pie Chart</h4>
                <div class="chart-container">
                    <!-- Chart 1 -->
                    <div class="card">
                        <div class="circle-chart red">
                            <span>81%</span>
                        </div>
                        <p>Total Order</p>
                    </div>
                    <!-- Chart 2 -->
                    <div class="card">
                        <div class="circle-chart green">
                            <span>22%</span>
                        </div>
                        <p>Customer Growth</p>
                    </div>
                    <!-- Chart 3 -->
                    <div class="card">
                        <div class="circle-chart blue">
                            <span>62%</span>
                        </div>
                        <p>Total Revenue</p>
                    </div>
                </div>
            </section>

            <!-- Customer Reviews Section -->
            <section class="reviews">
                <h4>Customer Review</h4>
                <div class="review-container">
                    <div class="review-card">
                      <div class="review-header">
                        <div class="reviewer-info">
                          <img src="assets/placeholder.png" alt="Reviewer Avatar" class="reviewer-avatar">
                          <div>
                            <h3 class="reviewer-name">Jons Sena</h3>
                            <p class="review-date">2 days ago</p>
                          </div>
                        </div>
                        <div class="review-rating">
                          <div class="stars">★★★★☆</div>
                          <span class="rating-score">4.5</span>
                        </div>
                      </div>
                      <p class="review-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</p>
                    </div>
                    <div class="review-card">
                      <div class="review-header">
                        <div class="reviewer-info">
                          <img src="assets/placeholder (1).png" alt="Reviewer Avatar" class="reviewer-avatar">
                          <div>
                            <h3 class="reviewer-name">Anandreansyah</h3>
                            <p class="review-date">2 days ago</p>
                          </div>
                        </div>
                        <div class="review-rating">
                          <div class="stars">★★★★☆</div>
                          <span class="rating-score">4.5</span>
                        </div>
                      </div>
                      <p class="review-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</p>
                    </div>
                    <div class="review-card">
                      <div class="review-header">
                        <div class="reviewer-info">
                          <img src="assets/placeholder (2).png" alt="Reviewer Avatar" class="reviewer-avatar">
                          <div>
                            <h3 class="reviewer-name">Jons Sena</h3>
                            <p class="review-date">2 days ago</p>
                          </div>
                        </div>
                        <div class="review-rating">
                          <div class="stars">★★★★☆</div>
                          <span class="rating-score">4.5</span>
                        </div>
                      </div>
                      <p class="review-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</p>
                    </div>
                  </div>
</body>
</html>