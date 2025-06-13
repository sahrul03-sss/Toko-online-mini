<?php
// proses-checkout.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Koneksi database
$conn = new mysqli("localhost", "root", "", "produk");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi aman untuk input
function clean_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Tangkap data POST
$id_produk = isset($_POST['id_produk']) ? (int)$_POST['id_produk'] : 0;
$size = isset($_POST['size']) ? clean_input($_POST['size']) : '';
$jumlah = isset($_POST['jumlah']) ? (int)$_POST['jumlah'] : 1;
$total = isset($_POST['total']) ? (float)$_POST['total'] : 0.0;
$nama = isset($_POST['nama']) ? clean_input($_POST['nama']) : '';
$alamat = isset($_POST['alamat']) ? clean_input($_POST['alamat']) : '';
$telepon = isset($_POST['telepon']) ? clean_input($_POST['telepon']) : '';
$catatan = isset($_POST['catatan']) ? clean_input($_POST['catatan']) : '';

// Validasi sederhana
if ($id_produk <= 0 || empty($size) || $jumlah <= 0 || $total <= 0 || empty($nama) || empty($alamat) || empty($telepon)) {
    die("Data tidak lengkap atau tidak valid.");
}

// Handle upload bukti transfer
if (isset($_FILES['bukti']) && $_FILES['bukti']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['bukti']['tmp_name'];
    $fileName = $_FILES['bukti']['name'];
    $fileSize = $_FILES['bukti']['size'];
    $fileType = $_FILES['bukti']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    // Format file yang diizinkan
    $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];

    if (!in_array($fileExtension, $allowedfileExtensions)) {
        die("Format file tidak didukung. Gunakan jpg, png, gif, atau pdf.");
    }

    // Buat nama file baru agar unik
    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

    // Folder upload
    $uploadFileDir = './uploads/';
    if (!is_dir($uploadFileDir)) {
        mkdir($uploadFileDir, 0755, true);
    }
    $dest_path = $uploadFileDir . $newFileName;

    if (!move_uploaded_file($fileTmpPath, $dest_path)) {
        die("Gagal mengupload file bukti transfer.");
    }

} else {
    die("File bukti transfer wajib diupload.");
}

// Simpan data ke tabel `pesanan`
$stmt = $conn->prepare("INSERT INTO pesanan (id_produk, size, jumlah, total, nama_customer, alamat, telepon, catatan, bukti_transfer, tanggal_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("isidsssss", $id_produk, $size, $jumlah, $total, $nama, $alamat, $telepon, $catatan, $newFileName);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Proses Checkout - VOIDWEAR</title>
    <style>
        body {
            background: #121212;
            color: #eee;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 600px;
            margin: 3rem auto;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.6);
            background-image: linear-gradient(45deg, #1e1e1e, #2e2e2e);
        }
        h2 {
            color: #ff5722;
            text-align: center;
            margin-bottom: 1rem;
            font-weight: 700;
            letter-spacing: 1.5px;
        }
        p {
            font-size: 1.1rem;
            line-height: 1.6;
            text-align: center;
        }
        a {
            display: inline-block;
            margin-top: 2rem;
            background-color: #ff5722;
            color: #fff;
            padding: 0.75rem 2rem;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        a:hover {
            background-color:rgb(0, 0, 0);
        }
        .error {
            color:rgb(0, 0, 0);
            font-weight: 700;
            text-align: center;
            margin-top: 2rem;
            padding: 1rem;
            border: 1px solidrgb(0, 0, 0);
            border-radius: 8px;
            background-color:rgb(0, 0, 0);
        }
    </style>
</head>
<body>

<?php
if ($stmt->execute()) {
    echo "<h2>Pesanan Berhasil Dikirim!</h2>";
    echo "<p>Terima kasih, <strong>" . htmlspecialchars($nama) . "</strong>, pesanan Anda sudah kami terima.</p>";
    echo "<p>Kami akan memproses pesanan Anda sesegera mungkin.</p>";
    echo "<p><a href='index.php'>Kembali ke Beranda</a></p>";
} else {
    echo "<div class='error'>Terjadi kesalahan saat menyimpan data: " . htmlspecialchars($stmt->error) . "</div>";
    echo "<p><a href='javascript:history.back()'>Kembali ke Form Checkout</a></p>";
}

$stmt->close();
$conn->close();
?>

</body>
</html>
