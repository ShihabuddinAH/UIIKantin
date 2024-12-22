<?php
session_start();
include '../connect.php';

$timeout_duration = 1 * 30;

if (isset($_SESSION['last_activity'])) {
    $inactive_duration = time() - $_SESSION['last_activity'];

    if ($inactive_duration > $timeout_duration) {
        $username = $_SESSION['username'];
            $updateStatusSql = "UPDATE user SET status = 'nonaktif' WHERE username = ?";
            $stmt = $conn->prepare($updateStatusSql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->close();
            $updateStatusSql = "UPDATE user SET status = 'nonaktif' WHERE username = '$username'";
            $conn->query($updateStatusSql);
        session_unset(); 
        session_destroy();
        header("Location: ../LOGIN/login.php"); 
        exit();
    }
}

$_SESSION['last_activity'] = time();

if (!isset($_SESSION['username'])) {
    header('Location: ../LOGIN/login.php');
    exit();
}

// Tambahkan header no-cache
header("Cache-Control: no-cache, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
?>
