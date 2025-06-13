<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produk = (int) $_POST['id_produk'];
    $nama_produk = trim($_POST['nama_produk']);
    $size = trim($_POST['size']);
    $jumlah = max(1, (int) $_POST['jumlah']);
    $action = $_POST['action'] ?? '';

    // Validasi ukuran
    $size_tersedia = ['S', 'M', 'L', 'XL', 'XXL'];
    if (!in_array($size, $size_tersedia)) {
        $size = 'M';
    }

    // Koneksi ke database
    $conn = new mysqli("localhost", "root", "", "produk");
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Jika tombol yang diklik adalah "checkout", arahkan langsung ke halaman checkout
    if ($action === 'checkout') {
        header("Location: proses-checkout.php?id_produk=$id_produk&size=$size&jumlah=$jumlah");
        exit;
    }

    // Jika tombol yang diklik adalah "keranjang", simpan ke database keranjang
    if ($action === 'keranjang') {
        // Cek apakah produk valid
        $cek = $conn->prepare("SELECT id FROM produk WHERE id = ?");
        $cek->bind_param("i", $id_produk);
        $cek->execute();
        $cek->store_result();

        if ($cek->num_rows > 0) {
            $cek->close();
            $stmt = $conn->prepare("INSERT INTO keranjang (id_produk, nama_produk, size, jumlah) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("issi", $id_produk, $nama_produk, $size, $jumlah);
            $stmt->execute();
            $stmt->close();

            // Redirect otomatis ke halaman keranjang
            header("Location: simpan-keranjang.php");
            exit;
        } else {
            $cek->close();
            echo "<script>alert('Produk tidak ditemukan.'); window.location.href='index.php';</script>";
            exit;
        }
    }

    // Jika aksi tidak valid
    echo "Aksi tidak valid.";
}
?>
