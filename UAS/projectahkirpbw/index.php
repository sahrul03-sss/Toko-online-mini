<?php
// index.php

// 1. Konfigurasi Database
$servername = "localhost"; // Database
$username = "root";      // Username default XAMPP
$password = "";          // Password default XAMPP (kosong)
$dbname = "produk";    // Nama database

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// 2. Ambil Data Produk dari Database

// Query untuk "Produk Terbaru"
// Mengambil 6 produk terbaru berdasarkan ID (asumsi ID lebih tinggi berarti lebih baru)
$sql_terbaru = "SELECT id, nama, deskripsi, harga, gambar FROM produk ORDER BY id DESC LIMIT 6";
$result_terbaru = $conn->query($sql_terbaru);

$products_terbaru = [];
if ($result_terbaru && $result_terbaru->num_rows > 0) {
    while($row = $result_terbaru->fetch_assoc()) {
        $products_terbaru[] = $row;
    }
}

// Query untuk "Produk Terlaris"
// Mengambil 8 produk secara acak sebagai simulasi "terlaris"
$sql_terlaris = "SELECT id, nama, deskripsi, harga, gambar FROM produk ORDER BY RAND() LIMIT 8";
$result_terlaris = $conn->query($sql_terlaris);

$products_terlaris = [];
if ($result_terlaris && $result_terlaris->num_rows > 0) {
    while($row = $result_terlaris->fetch_assoc()) {
        $products_terlaris[] = $row;
    }
}

// Tutup koneksi database
$conn->close();

$conn = new mysqli("localhost", "root", "", "produk");
$result = $conn->query("SELECT * FROM keranjang");

?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>VOIDWEAR</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Anton&family=Orbitron:wght@500&display=swap" rel="stylesheet">

  <style>
  
    * {
      margin: 0; padding: 0; box-sizing: border-box;
    }

    body {
      font-family: 'Orbitron', sans-serif;
      background: #121212;
      color: #f1f1f1;
      animation: fadeInBody 0.8s ease;
    }

    @keyframes fadeInBody {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    header {
      background: #1e1e1e;
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 10;
      border-bottom: 1px solid #333;
    }

    .logo {
      display: flex;
      align-items: center;
      font-size: 1.5rem;
      font-weight: bold;
      font-family: 'Anton', sans-serif;
      color: #f1f1f1;
      letter-spacing: 1px;
    }

    .logo img {
      height: 50px;
      margin-right: 0.5rem;
      filter: contrast(1.2);
    }

    nav a {
      margin-left: 2rem;
      text-decoration: none;
      color: #ccc;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    nav a:hover {
      color: #ff5722;
    }
.promo-banner {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  padding: 30px 5%;
  background-color: #111;
  justify-content: center;
}

.promo-image {
  flex: 1 1 45%;
  overflow: hidden;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.4);
  transition: transform 0.3s ease;
}

.promo-image img {
  width: 100%;
  height: auto;
  display: block;
  border-radius: 10px;
}

.promo-image:hover {
  transform: scale(1.02);
}



    .section-title {
      text-align: center;
      font-weight: bold;
      padding: 2rem 0 1rem;
      border-bottom: 1px solid #555;
      margin: 0 2rem;
      color: #fff;
      font-family: 'Anton', sans-serif;
      letter-spacing: 2px;
      font-size: 2rem;
    }

    h2 {
      font-size: 1.5rem;
      margin: 1rem 2rem;
      text-transform: uppercase;
      font-family: 'Anton', sans-serif;
    }

    .products {
      display: flex;
      overflow-x: auto;
      gap: 1rem;
      padding: 2rem;
      scroll-snap-type: x mandatory;
    }

    .product {
      min-width: 300px;
      flex: 0 0 auto;
      background: #1e1e1e;
      padding: 1rem;
      border: 1px solid #333;
      text-align: center;
      scroll-snap-align: start;
      border-radius: 6px;
      transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
    }
    /* Style untuk link di dalam product card agar seluruh area card bisa diklik */
    .product a {
        text-decoration: none; /* Hilangkan garis bawah default */
        color: inherit; /* Warisi warna teks dari parent */
        display: block; /* Buat seluruh area card bisa diklik */
    }

    .product:hover {
      transform: translateY(-6px);
      box-shadow: 0 0 14px #ff5722aa, 0 0 32px #ff572244;
      border-color: #ff5722;
    }

    .product img {
      width: 100%;
      height: 300px;
      object-fit: cover;
      border-radius: 8px;
      border: 1px solid #444;
      filter: contrast(1.2) saturate(1.1);
      transition: filter 0.3s ease;
    }

    .product:hover img {
      filter: contrast(1.4) saturate(1.2);
    }

    .product-name {
      margin-top: 1rem;
      color: #ffffff;
      font-family: 'Anton', sans-serif;
      text-transform: uppercase;
    }

    .product-price {
      color: #ff5722;
      font-weight: bold;
      margin-top: 0.5rem;
    }

    .products-vertical {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 1rem;
      padding: 2rem;
    }

    .fade-in {
      opacity: 0;
      transform: translateY(20px);
      transition: all 0.6s ease;
    }

    .fade-in.show {
      opacity: 1;
      transform: translateY(0);
    }

    .about-section {
      display: flex;
      flex-wrap: wrap;
      max-width: 1200px;
      margin: 3rem auto;
      padding: 0 1rem;
      align-items: center;
      gap: 2rem;
    }

    .about-image {
      flex: 1 1 40%;
    }

    .about-image img {
      width: 100%;
      height: auto;
      display: block;
      border-radius: 6px;
    }

    .about-text {
      flex: 1 1 55%;
      font-size: 1rem;
      line-height: 1.7;
    }

    .about-text h2 {
      font-size: 1.8rem;
      margin-bottom: 1rem;
      color: #ff5722;
      font-family: 'Anton', sans-serif;
    }

    @media (max-width: 768px) {
      .about-section {
        flex-direction: column;
        text-align: center;
      }
      .about-text {
        text-align: center;
      }
      header {
        flex-direction: column;
        align-items: flex-start;
      }
    }

    .products::-webkit-scrollbar {
      height: 8px;
    }
    .products::-webkit-scrollbar-thumb {
      background: #555;
      border-radius: 4px;
    }
  </style>
</head>
<body>

<?php
$conn = new mysqli("localhost", "root", "", "produk");
if ($conn->connect_error) die("Koneksi gagal: " . $conn->connect_error);

$result = $conn->query("SELECT * FROM keranjang ORDER BY id DESC");
?>

<header>
  <div class="logo">
    <img src="img/png/logo.png" alt="VOIDWEAR"> 
  </div>
  <nav>
    <a href="#home">Home</a>
    <a href="#tentang">Tentang</a>
    <a href="simpan-keranjang.php">Keranjang 🛒</a>

  </nav>
</header>




<section id="home">
  <h2 class="section-title">FEATURED PRODUCTS</h2>

  <h2>Produk Terbaru</h2>

  <div class="products">
    <?php if (!empty($products_terbaru)): ?>
        <?php foreach ($products_terbaru as $product): ?>
           <div class="product fade-in">
                <a href="detail-produk.php?id=<?= htmlspecialchars($product['id']) ?>">
                    <img src="img/<?= htmlspecialchars($product['gambar']) ?>" alt="<?= htmlspecialchars($product['nama']) ?>">
                    <div class="product-name"><?= htmlspecialchars($product['nama']) ?></div>
                    <div class="product-price">Rp <?= number_format($product['harga'], 0, ',', '.') ?></div>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align: center; padding: 1rem;">Belum ada produk terbaru yang tersedia.</p>
    <?php endif; ?>
  </div>
  
  <h2>Produk Terlaris</h2>
  <div class="products-vertical">
    <?php if (!empty($products_terlaris)): ?>
        <?php foreach ($products_terlaris as $product): ?>
            <div class="product fade-in">
                <a href="detail-produk.php?id=<?= htmlspecialchars($product['id']) ?>">
                    <img src="img/<?= htmlspecialchars($product['gambar']) ?>" alt="<?= htmlspecialchars($product['nama']) ?>">
                    <div class="product-name"><?= htmlspecialchars($product['nama']) ?></div>
                    <div class="product-price">Rp <?= number_format($product['harga'], 0, ',', '.') ?></div>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align: center; padding: 1rem; grid-column: 1 / -1;">Belum ada produk terlaris yang tersedia.</p>
    <?php endif; ?>
  </div>
</section>

<section id="tentang" class="about-section">
  <div class="about-image">
    <img src="img/PNG/logo.png" alt="Tentang VOIDWEAR" />
  </div>
  <div class="about-text">
    <h2>Tentang VOIDWEAR</h2>
   <p style="text-align: justify;">
      VOIDWEAR adalah toko online fashion yang menghadirkan produk dengan desain modern dan kualitas terbaik.
Kami berkomitmen memberikan pengalaman belanja yang mudah dan menyenangkan bagi setiap pelanggan.
Koleksi kami selalu diperbarui dengan tren terbaru untuk memenuhi kebutuhan gaya Anda.
Setiap produk dipilih dengan cermat agar memenuhi standar kualitas dan kenyamanan.
Kami bangga menjadi pilihan utama bagi pelanggan yang mengutamakan gaya dan kualitas.
    </p>
  </div>
</section>

<script>
  // Animasi fade-in pada produk saat scroll
  document.addEventListener('DOMContentLoaded', () => {
    const fadeElems = document.querySelectorAll('.fade-in');

    function checkFade() {
      fadeElems.forEach(elem => {
        const rect = elem.getBoundingClientRect();
        if (rect.top < window.innerHeight - 100) {
          elem.classList.add('show');
        }
      });
    }

    window.addEventListener('scroll', checkFade);
    checkFade();
  });
</script>

</body>
</html>
