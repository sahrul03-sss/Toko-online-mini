<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "produk");
if ($conn->connect_error) die("Koneksi gagal: " . $conn->connect_error);

// Validasi dan hapus item
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM keranjang WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Kembali ke halaman keranjang
header("Location: keranjang.php");
exit;
?>
