<?php
session_start();

if (isset($_SESSION['username'])) {
    header('Location: ../PEMBELI/utama.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UIIKantin</title>
  <link rel="stylesheet" href="../../CSS/LOGIN/login.css">
</head>
<body>
  <div class="container">
    <!-- Left Section: Image -->
    <div class="left-section">
      <img src="../../ASSETS/LOGIN/uiikantin.jpg" alt="UII Kantin" class="uii-kantin-image">
      <img src="../../ASSETS/LOGIN/companylogo.png" alt="Company Logo" class="company-logo">
    </div>

    <!-- Right Section: Login Form -->
    <div class="right-section">
      <div class="toggle-wrapper">
        <div class="toggle">
          <div id="buyer-toggle" class="toggle-option active">Pembeli</div>
          <div id="seller-toggle" class="toggle-option">Penjual</div>
          <div id="admin-toggle" class="toggle-option active">Admin</div>
        </div>
      </div>
      
      <!-- Form Container -->
      <div class="form-container">
        <h1>Selamat Datang di UIIKantin</h1>
        <h2>Masuk</h2>
        
        <!-- Google Sign-in Button -->
        <button class="google-button">
          <img src="../../ASSETS/LOGIN/google.webp" alt="Google Logo" class="google-icon">
          Masuk dengan Google
        </button>

        <!-- Login Form -->
        <form action="auth.php" id="login-form" method="POST">
          <label for="username">Masukkan Username atau Email UII</label>
          <input type="text" id="username" name="username" placeholder="Username atau Email UII" required>

          <label for="password">Masukkan Password</label>
          <input type="password" id="password" name="password" placeholder="Password" required>

          <!-- Hidden input to store toggle state -->
          <input type="hidden" id="role" name="role" value="buyer">

          <a href="#" class="forgot-password">Lupa</a>
          <button type="submit" class="sign-in-button">Masuk</button>
        </form>

        <p class="no-account">
          Tidak Punya Akun? 
          <a id="signup-link" href="../PEMBELI/signupPembeli.php">Daftar</a>
        </p>
      </div>
    </div>
  </div>

  <script src="../../SCRIPT/LOGIN/script.js"></script>
</body>
</html>
