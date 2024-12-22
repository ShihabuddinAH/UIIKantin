<?php
session_start();
include '../connect.php';

// Check if user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Update user status to 'nonaktif'
    $updateStatusSql = "UPDATE user SET status = 'nonaktif' WHERE username = ?";
    $stmt = $conn->prepare($updateStatusSql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();
}

// Destroy the session
session_destroy();

// Redirect to login page
header('Location: login.php');
exit();
?>