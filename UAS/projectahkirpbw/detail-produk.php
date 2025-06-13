<?php 
// detail-produk.php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "produk";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "SELECT id, nama, deskripsi, harga, gambar FROM produk WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo "<h1>Produk tidak ditemukan.</h1>";
    echo "<p>Silakan kembali ke <a href='index.php'>halaman utama</a>.</p>";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Detail Produk - <?= htmlspecialchars($product['nama']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #121212;
            color: #f1f1f1;
            padding: 2rem;
            max-width: 600px;
            margin: auto;
        }
        img {
            max-width: 100%;
            border-radius: 8px;
            border: 1px solid #444;
            margin-bottom: 1rem;
        }
        h1 {
            color: #ff5722;
            font-family: 'Anton', sans-serif;
        }
        .price {
            font-size: 1.5rem;
            color: #ff5722;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        a.back {
            color: #ccc;
            text-decoration: none;
            display: inline-block;
            margin-top: 2rem;
            border: 1px solid #ff5722;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        a.back:hover {
            background-color: #ff5722;
            color: #121212;
        }
        form {
            margin-top: 2rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            margin-top: 1rem;
        }
        select, input[type="number"] {
            width: 100%;
            padding: 0.5rem;
            background: #1e1e1e;
            border: 1px solid #444;
            color: white;
            border-radius: 4px;
        }
        button {
            margin-top: 1rem;
            width: 100%;
            padding: 0.75rem;
            background-color: #ff5722;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #e64a19;
        }
    </style>
</head>
<body>

<h1><?= htmlspecialchars($product['nama']) ?></h1>
<img src="img/<?= htmlspecialchars($product['gambar']) ?>" alt="<?= htmlspecialchars($product['nama']) ?>">
<div class="price">Rp <?= number_format($product['harga'], 0, ',', '.') ?></div>
<p><?= nl2br(htmlspecialchars($product['deskripsi'])) ?></p>

<!-- Form untuk checkout dan keranjang -->
<form method="POST" action="proses-belanja.php  ">
    <input type="hidden" name="id_produk" value="<?= $product['id'] ?>">
    <input type="hidden" name="nama_produk" value="<?= htmlspecialchars($product['nama']) ?>">

    <label for="size">Pilih Ukuran:</label>
    <select name="size" id="size" required>
        <option value="">--Pilih Ukuran--</option>
        <option value="S">S</option>
        <option value="M" selected>M</option>
        <option value="L">L</option>
        <option value="XL">XL</option>
        <option value="XXL">XXL</option>
    </select>

    <label for="jumlah">Jumlah:</label>
    <input type="number" name="jumlah" id="jumlah" min="1" value="1" required>

    <button type="submit" name="action" value="checkout">Ke Pembayaran</button>
    <button type="submit" name="action" value="keranjang">Simpan ke Keranjang</button>
</form>

<a href="index.php" class="back">← Kembali ke Beranda</a>

</body>
</html>
