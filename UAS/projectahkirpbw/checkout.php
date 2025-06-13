<?php 
// checkout.php

session_start();

// Ambil data dari POST atau GET
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produk = isset($_POST['id_produk']) ? (int)$_POST['id_produk'] : 0;
    $size = isset($_POST['size']) ? $_POST['size'] : '';
    $jumlah = isset($_POST['jumlah']) ? (int)$_POST['jumlah'] : 1;
} else {
    $id_produk = isset($_GET['id_produk']) ? (int)$_GET['id_produk'] : 0;
    $size = isset($_GET['size']) ? $_GET['size'] : '';
    $jumlah = isset($_GET['jumlah']) ? (int)$_GET['jumlah'] : 1;
}

// Validasi
if ($id_produk <= 0 || $size === '') {
    echo "<h1>Data produk tidak valid!</h1>";
    echo "<p>Kembali ke <a href='index.php'>beranda</a></p>";
    exit;
}

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "produk");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data produk
$sql = "SELECT id, nama, harga, gambar FROM produk WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_produk);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<h1>Produk tidak ditemukan!</h1>";
    echo "<p>Kembali ke <a href='index.php'>beranda</a></p>";
    exit;
}

$produk = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout - <?= htmlspecialchars($produk['nama']) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #121212;
            color: #fff;
            max-width: 600px;
            margin: auto;
            padding: 2rem;
        }
        img {
            max-width: 100%;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        .form-section {
            margin-top: 2rem;
        }
        label {
            display: block;
            margin-top: 1rem;
        }
        input, textarea {
            width: 100%;
            padding: 0.6rem;
            margin-top: 0.3rem;
            background: #1e1e1e;
            border: 1px solid #555;
            color: #fff;
            border-radius: 4px;
        }
        button {
            margin-top: 2rem;
            width: 100%;
            padding: 0.8rem;
            background-color: #ff5722;
            border: none;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
        }
        a {
            color: #ff9800;
        }
        .product-info {
            margin-top: 1rem;
        }
    </style>
</head>
<body>

<h1>Checkout - <?= htmlspecialchars($produk['nama']) ?></h1>

<img src="img/<?= htmlspecialchars($produk['gambar']) ?>" alt="<?= htmlspecialchars($produk['nama']) ?>">

<div class="product-info">
    <p><strong>Ukuran:</strong> <?= htmlspecialchars($size) ?></p>
    <p><strong>Jumlah:</strong> <?= $jumlah ?></p>
    <p><strong>Harga Satuan:</strong> Rp <?= number_format($produk['harga'], 0, ',', '.') ?></p>
    <p><strong>Total:</strong> Rp <?= number_format($produk['harga'] * $jumlah, 0, ',', '.') ?></p>
</div>

<div class="form-section">
    <form action="proses-checkout.php" method="POST" enctype="multipart/form-data">
        <!-- Data tersembunyi untuk dikirim ke proses-checkout -->
        <input type="hidden" name="id_produk" value="<?= $produk['id'] ?>">
        <input type="hidden" name="size" value="<?= htmlspecialchars($size) ?>">
        <input type="hidden" name="jumlah" value="<?= $jumlah ?>">
        <input type="hidden" name="total" value="<?= $produk['harga'] * $jumlah ?>">

        <label for="nama">Nama Lengkap:</label>
        <input type="text" id="nama" name="nama" required>

        <label for="alamat">Alamat Pengiriman:</label>
        <textarea id="alamat" name="alamat" rows="3" required></textarea>

        <label for="telepon">Nomor Telepon:</label>
        <input type="text" id="telepon" name="telepon" required>

        <label for="bukti">Upload Bukti Transfer: <br>
        <small>Rek BCA 12345678 A/N QWERTY</small></label>
        <input type="file" id="bukti" name="bukti" required accept="image/*,application/pdf">

        <label for="catatan">Catatan (opsional):</label>
        <textarea id="catatan" name="catatan" rows="2"></textarea>

        <button type="submit">Kirim Pesanan</button>
    </form>
</div>

</body>
</html>
