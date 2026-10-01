<?php
// Koneksi database sederhana untuk project sekolah.
$host = 'localhost';
$db   = 'db_portfolio';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Koneksi database gagal: ' . $conn->connect_error);
}
$conn->set_charset('utf8mb4');
