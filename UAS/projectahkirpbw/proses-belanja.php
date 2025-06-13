<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produk = $_POST['id_produk'];
    $nama_produk = $_POST['nama_produk'];
    $size = $_POST['size'];
    $jumlah = $_POST['jumlah'];
    $action = $_POST['action'];

    if ($action === 'checkout') {
        // Redirect ke checkout.php dengan parameter
        header("Location: checkout.php?id_produk=$id_produk&size=$size&jumlah=$jumlah");
        exit;
    } elseif ($action === 'keranjang') {
        // Simpan ke database
        $conn = new mysqli("localhost", "root", "", "produk");
        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO keranjang (id_produk, nama_produk, size, jumlah) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issi", $id_produk, $nama_produk, $size, $jumlah);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        echo "<script>alert('Produk berhasil ditambahkan ke keranjang!'); window.location.href='simpan-keranjang.php';</script>";
        exit;
    } else {
        echo "Aksi tidak valid.";
    }
}
?>
