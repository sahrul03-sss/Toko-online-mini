<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "produk");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Hapus item dari keranjang jika ada parameter 'hapus'
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM keranjang WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: simpan-keranjang.php");
    exit;
}

// Ambil isi keranjang beserta data produk
$sql = "SELECT k.id, k.id_produk, k.nama_produk, k.size, k.jumlah, p.harga, p.gambar 
        FROM keranjang k 
        JOIN produk p ON k.id_produk = p.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Keranjang & Pesanan - VOIDWEAR</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Anton&family=Orbitron:wght@500&display=swap" rel="stylesheet">
  <style>
    body { background: #121212; color: white; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 2rem; max-width: 1000px; margin: auto; }
    h1, h2 { color: #ff5722; text-align: center; margin-bottom: 2rem; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 2rem; }
    th, td { padding: 1rem; border-bottom: 1px solid #444; text-align: center; vertical-align: middle; }
    th { background: #1e1e1e; }
    img { max-width: 80px; border-radius: 6px; }
    input[type=number], select {
      width: 60px; padding: 6px; border-radius: 5px; border: 1px solid #555; background: #222; color: #eee;
    }
    form.inline { display: inline-block; margin: 0; }
    button {
      background-color: #ff5722; border: none; color: white; padding: 6px 12px;
      border-radius: 5px; cursor: pointer; font-weight: bold; transition: background-color 0.3s;
    }
    button:hover { background-color: #e64a19; }
    a { color: #ff5722; text-decoration: none; font-weight: bold; }
    a:hover { text-decoration: underline; }
    .total { font-weight: 700; color: #ff5722; font-size: 1.2rem; text-align: right; margin-top: 1rem; }
    .checkout { text-align: center; margin-top: 2rem; }
  </style>
</head>
<body>

<h1>Keranjang Belanja</h1>

<?php if ($result && $result->num_rows > 0): ?>
<table>
  <thead>
    <tr>
      <th>Produk</th>
      <th>Ukuran & Jumlah</th>
      <th>Harga</th>
      <th>Subtotal</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
  <?php
    $totalHarga = 0;
    while ($row = $result->fetch_assoc()):
        $subtotal = $row['harga'] * $row['jumlah'];
        $totalHarga += $subtotal;
  ?>
    <tr>
      <td style="text-align:left;">
        <img src="img/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['nama_produk']) ?>"><br>
        <?= htmlspecialchars($row['nama_produk']) ?>
      </td>
      <td>
        <form method="POST" class="inline" action="ubah-keranjang.php">
          <input type="hidden" name="id" value="<?= $row['id'] ?>">
          <select name="size" required>
            <?php foreach (['S','M','L','XL','XXL'] as $s): ?>
              <option value="<?= $s ?>" <?= $s === $row['size'] ? 'selected' : '' ?>><?= $s ?></option>
            <?php endforeach; ?>
          </select>
          <input type="number" name="jumlah" value="<?= $row['jumlah'] ?>" min="1" required>
          <button type="submit">Ubah</button>
        </form>
      </td>
      <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
      <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
      <td>
        <form method="GET" class="inline" action="simpan-keranjang.php" onsubmit="return confirm('Hapus item ini?');">
          <input type="hidden" name="hapus" value="<?= $row['id'] ?>">
          <button type="submit" style="background:#900; padding:5px 10px;">Hapus</button>
        </form>
        <form method="GET" action="checkout.php" class="inline">
          <input type="hidden" name="id_produk" value="<?= $row['id_produk'] ?>">
          <input type="hidden" name="size" value="<?= $row['size'] ?>">
          <input type="hidden" name="jumlah" value="<?= $row['jumlah'] ?>">
          <button type="submit" style="background:#4CAF50; margin-top:5px;">Checkout</button>
        </form>
      </td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>

<p class="total">Total Harga: Rp <?= number_format($totalHarga, 0, ',', '.') ?></p>

<?php else: ?>
  <p style="text-align:center; font-size:1.2rem;">Keranjang masih kosong. Yuk belanja dulu!</p>
<?php endif; ?>

<hr style="margin: 3rem 0; border: 1px solid #333;">

<!-- BAGIAN PESANAN -->
<h2>Daftar Pesanan</h2>

<?php
$sqlPesanan = "SELECT 
                 p.id, 
                 p.nama_customer, 
                 p.alamat, 
                 p.telepon, 
                 pr.nama AS nama_produk, 
                 p.size, 
                 p.jumlah, 
                 p.total, 
                 p.bukti_transfer, 
                 p.catatan, 
                 p.created_at
               FROM pesanan p
               LEFT JOIN produk pr ON p.id_produk = pr.id
               ORDER BY p.created_at DESC";
$resPesanan = $conn->query($sqlPesanan);
?>

<?php if ($resPesanan && $resPesanan->num_rows > 0): ?>
<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Nama</th>
      <th>Alamat</th>
      <th>Telepon</th>
      <th>Produk</th>
      <th>Ukuran</th>
      <th>Jumlah</th>
      <th>Total</th>
      <th>Bukti</th>
      <th>Catatan</th>
      <th>Waktu</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $resPesanan->fetch_assoc()): ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= htmlspecialchars($row['nama_customer']) ?></td>
      <td><?= nl2br(htmlspecialchars($row['alamat'])) ?></td>
      <td><?= htmlspecialchars($row['telepon']) ?></td>
      <td><?= htmlspecialchars($row['nama_produk']) ?></td>
      <td><?= $row['size'] ?></td>
      <td><?= $row['jumlah'] ?></td>
      <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
      <td>
        <?php if (!empty($row['bukti_transfer'])): ?>
          <a href="uploads/<?= htmlspecialchars($row['bukti_transfer']) ?>">Lihat</a>
        <?php else: ?>
          Tidak ada
        <?php endif; ?>
      </td>
      <td><?= nl2br(htmlspecialchars($row['catatan'])) ?></td>
      <td><?= $row['created_at'] ?></td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<?php else: ?>
  <p style="text-align:center;">Belum ada pesanan.</p>
<?php endif; ?>

<br><a href="index.php">← Kembali ke Beranda</a>

</body>
</html>

<?php $conn->close(); ?>
