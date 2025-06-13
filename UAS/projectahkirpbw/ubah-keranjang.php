<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "produk");
if ($conn->connect_error) die("Koneksi gagal: " . $conn->connect_error);

// Validasi dan update data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int) $_POST['id'];
    $jumlah = max(1, (int) $_POST['jumlah']);
    $size = trim($_POST['size']);
    $validSizes = ['S', 'M', 'L', 'XL', 'XXL'];
    if (!in_array($size, $validSizes)) $size = 'M';

    $stmt = $conn->prepare("UPDATE keranjang SET jumlah = ?, size = ? WHERE id = ?");
    $stmt->bind_param("isi", $jumlah, $size, $id);
    $stmt->execute();
    $stmt->close();
}

// Kembali ke halaman keranjang
header("Location: simpan-keranjang.php");
exit;
?>
