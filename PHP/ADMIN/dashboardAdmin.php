<?php
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['username'])) {
    $username = $_POST['username'];
    $action = $_POST['action'];

    if ($action === 'activate') {
        $status = 'aktif';
    } elseif ($action === 'deactivate') {
        $status = 'nonaktif';
    }

    $sql = "UPDATE user SET status = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $status, $username);
    $stmt->execute();
    $stmt->close();
    header('Location: dashboardAdmin.php');
    exit();
}

if (isset($_POST['delete_user'])) {
    $username = $_POST['username'];

    $sql = "DELETE FROM user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("s", $username);
    if ($stmt->execute() === false) {
        die('Execute failed: ' . htmlspecialchars($stmt->error));
    }
    $stmt->close();
    header('Location: dashboardAdmin.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/ADMIN/dashboardAdmin.css">
    <title>Dashboard Admin</title>
</head>
<body>
    <div class="container">
        <!-- Main Content -->
        <main class="content">
            <h1>User Management</h1>
            <!-- Statistics Section -->
            <section class="stats">
                <div class="card">
                    <?php
                    // Fetch total active users (sellers and buyers)
                    $sql = "SELECT COUNT(*) as total_active_users FROM user WHERE status = 'aktif' AND role IN ('seller', 'buyer')";
                    $result = $conn->query($sql);
                    $total_active_users = 0;

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $total_active_users = $row['total_active_users'];
                    }
                    ?>
                    <h3><?php echo $total_active_users; ?></h3>
                    <p>Total Pengguna Aktif</p>
                </div>
                <div class="card">
                    <h3>65</h3>
                    <p>Total Transaksi</p>
                </div>
                <div class="card">
                    <h3>10</h3>
                    <p>Total Canceled</p>
                </div>
                <div class="card">
                    <?php
                    // Fetch total seller accounts
                    $sql = "SELECT COUNT(*) as total_sellers FROM user WHERE role = 'seller'";
                    $result = $conn->query($sql);
                    $total_sellers = 0;

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $total_sellers = $row['total_sellers'];
                    }
                    ?>
                    <h3><?php echo $total_sellers; ?></h3>
                    <p>Total Akun Penjual</p>
                </div>
                <div class="card">
                <?php
                    // Fetch total buyer accounts
                    $sql = "SELECT COUNT(*) as total_buyers FROM user WHERE role = 'buyer'";
                    $result = $conn->query($sql);
                    $total_buyers = 0;

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $total_buyers = $row['total_buyers'];
                    }
                    ?>
                    <h3><?php echo $total_sellers; ?></h3>
                    <p>Total Akun Pembeli</p>
                </div>
            </section>

            <section class="add-user-form">
                <h2>Tambah Akun Baru</h2>
                <!-- Step 1: Pilih Role -->
                <label>Pilih Role:</label><br>
                <input type="radio" id="role_buyer" value="buyer" onclick="showForm('buyer')">
                <label for="role_buyer">Pembeli</label>
                <input type="radio" id="role_seller" value="seller" onclick="showForm('seller')">
                <label for="role_seller">Penjual</label>
                
                <br>

                <!-- Step 2: Form Dinamis -->
                <div id="form_buyer" style="display: none;">
                    <form action="check.php" class="signup-form" method="POST">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text"  name="username" placeholder="Masukkan Username" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" name="email" placeholder="Masukkan Email" recuired>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" placeholder="Masukkan password" recuired>
                        </div>
                        <div class="form-group">
                            <label>Nomor Handphone</label>
                            <input type="tel" name="kontak" placeholder="Masukkan Nomor Handphone" recuired>
                        </div>

                        <input type="hidden" name="role" value="buyer">
                        <button type="submit" name="add_user">Tambah Pembeli</button>
                    </form>
                </div>

                <div id="form_seller" style="display: none;">
                    <form action="check.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text"  name="username" placeholder="Masukkan Username" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" name="email" placeholder="Masukkan Email" recuired>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" placeholder="Masukkan password" recuired>
                        </div>
                        <div class="form-group">
                            <label>Nomor Handphone</label>
                            <input type="tel" name="kontak" placeholder="Masukkan Nomor Handphone" recuired>
                        </div>
                        <div class="form-group">
                            <label>Nama Kantin</label>
                            <input type="text" name="Nama_Warung" placeholder="Masukkan Nama Kantin" recuired>
                        </div>
                        <div class="form-group">
                            <label>Foto Kantin</label>
                            <input type="file" name="Gambar_Warung" recuired>
                        </div>

                        <input type="hidden" name="role" value="seller">
                        <button type="submit">Submit</button>
                    </form>
                </div>
            </section>

            <!-- User Accounts Tables -->
            <section class="user-accounts">
                <h2>Penjual</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Warung</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Foto</th>
                            <th>Action</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch seller data
                        $sql = "SELECT u.username, u.email, u.status, k.Nama_Warung, k.Gambar_Warung 
                                FROM user u 
                                LEFT JOIN kantin k ON u.id_pengguna = k.id_pengguna 
                                WHERE u.role = 'seller'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // Output data of each row
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["Nama_Warung"] . "</td>";
                                echo "<td>" . $row["username"] . "</td>";
                                echo "<td>" . $row["email"] . "</td>";
                                echo "<td>" . $row["Gambar_Warung"] . "</td>";
                                echo "<td>";
                                echo "<form method='POST' style='display:inline;'>";
                                echo "<input type='hidden' name='username' value='" . $row["username"] . "'>";
                                if ($row["status"] === 'aktif') {
                                    echo "<button type='submit' name='action' value='deactivate'>Nonaktifkan</button>";
                                } else {
                                    echo "<button type='submit' name='action' value='activate'>Aktifkan</button>";
                                }
                                echo "</form>";
                                echo "<form method='POST' style='display:inline;'>";
                                echo "<input type='hidden' name='username' value='" . htmlspecialchars($row['username']) . "'>";
                                echo "<button type='submit' class='delete-button' name='delete_user'>Hapus Akun</button>";
                                echo "<button type='submit' class='update-button' name='update_user'>Update Akun</button>";
                                echo "<button class='save-button' style='display:none;'>Update</button>";
                                echo "<button class='cancel-button' style='display:none;'>Batal</button>";
                                echo "</form>";
                                echo "</td>";
                                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No sellers found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <h2>Pembeli</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Action</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch buyer data
                        $sql = "SELECT username, email, status FROM user WHERE role = 'buyer'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // Output data of each row
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["username"] . "</td>";
                                echo "<td>" . $row["email"] . "</td>";
                                echo "<td>";
                                echo "<form method='POST' style='display:inline;'>";
                                echo "<input type='hidden' name='username' value='" . $row["username"] . "'>";
                                if ($row["status"] === 'aktif') {
                                    echo "<button type='submit' name='action' value='deactivate'>Nonaktifkan</button>";
                                } else {
                                    echo "<button type='submit' name='action' value='activate'>Aktifkan</button>";
                                }
                                echo "</form>";
                                echo "<form method='POST' style='display:inline;'>";
                                echo "<input type='hidden' name='username' value='" . htmlspecialchars($row['username']) . "'>";
                                echo "<button type='submit' class='delete-button' name='delete_user'>Hapus Akun</button>";
                                echo "<button type='submit' class='update-button' name='update_user'>Update Akun</button>";
                                echo "<button class='save-button' style='display:none;'>Update</button>";
                                echo "<button class='cancel-button' style='display:none;'>Batal</button>";
                                echo "</form>";
                                echo "</td>";
                                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No buyers found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>
            <a href="logout.php">
                <button style="background-color: red; color: white">LOGOUT</button>
            </a>
        </main>
    </div>

    <script src="../../SCRIPT/ADMIN/dashboardAdmin.js"></script>
</body>
</html>