<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UII Kantin - Sign Up</title>
  <link rel="stylesheet" href="../../CSS/PEMBELI/signup.css">
</head>
<body>
  <div class="signup-container">
    <!-- Left Section -->
    <div class="left-section">
      <img src="../../ASSETS/LOGIN/uiikantin.jpg" alt="UII Kantin Logo" class="uii-kantin-logo">
      <img src="../../ASSETS/LOGIN/companylogo.png" alt="Tap & Eat Logo" class="tap-eat-logo">
    </div>

    <!-- Right Section -->
    <div class="right-section">
      <div class="signup-form-container">
        <h1>Welcome to <span class="highlight">UIIKantin</span></h1>
        <h2>Sign up</h2>
        <form action="check.php" class="signup-form" method="POST">
          <label for="email">Masukan email UII</label>
          <input type="email" id="email" name="email" placeholder="Email" required>

          <div class="two-column">
            <div>
              <label for="username">Username</label>
              <input type="text" id="username" name="username" placeholder="Username" required>
            </div>
            <div>
              <label for="kontak">Contact Number</label>
              <input type="tel" id="kontak" name="kontak" placeholder="Contact Number" required>
            </div>
          </div>

          <label for="password">Masukkan Password</label>
          <input type="password" id="password" name="password" placeholder="Password" required>
          <button type="submit" class="signup-button">Sign up</button>
          <p class="already-account">
            Have an Account? <a href="../LOGIN/login.php">Sign in</a>
          </p>
        </form>
        <?php if (isset($error)): ?>
          <div class="alert alert-danger mt-3" role="alert">
            <?php echo $error; ?>
              </div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
          <div class="alert alert-success mt-3" role="alert">
            <?php echo $success; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
