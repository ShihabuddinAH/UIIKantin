<?php
$conn = new mysqli("localhost", "root", "", "tap&eat");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
