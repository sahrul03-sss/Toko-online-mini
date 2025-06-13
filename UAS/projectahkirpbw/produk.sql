-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2025 at 04:14 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `produk`
--

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` int(11) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `nama`, `deskripsi`, `harga`, `gambar`) VALUES
(1, 'VOIDWEAR SHE RUNS THE WORLD', 'Dominasi feminim dalam dunia gelap—gambar bumi, televisi, dan pose berani berpadu dalam desain yang menantang patriarki. Powerful dan provokatif.', 125000, 'png/101.jpg'),
(2, ' VOIDWEAR RELEASE', 'Saat beban terlalu berat, lo harus belajar melepaskan. Desain malaikat membawa pesan kebebasan dan pencerahan, buat lo yang ingin tampil stylish dengan sentuhan makna.', 125000, 'png/201.jpg'),
(3, 'VOIDWEAR THE DREAM STARTED CHASING ME', 'Bukan lo yang kejar mimpi, sekarang mimpi yang ngejar lo. Nuansa gelap dengan visual glitch membuat kaos ini cocok buat lo yang hidupnya edgy tapi tetap visioner.', 125000, 'png/202.jpg'),
(4, 'VOIDWEAR PRESSURE', 'Simbol tekanan batin dan kekuatan jiwa. Desain wajah yang retak menggambarkan perjuangan dalam diam, namun tetap berdiri tegar. Streetwear ini cocok untuk lo yang tahan banting tapi tetap bergaya.', 125000, 'png/203.jpg'),
(5, ' VOIDWEAR VRFRDE PANEL', 'kaos ini seperti cerita grafis yang berjalan dalam diam, penuh enigma dan identitas tersembunyi', 125000, 'png/204.jpg'),
(6, 'VOIDWEAR RISE', 'Luka, amarah, dan kebangkitan jadi satu dalam desain ini. Ekspresi wajah yang berteriak menggambarkan perlawanan lo terhadap tekanan dunia. Bangkit dan bersuara!', 125000, 'png/205.jpg'),
(7, 'VOIDWEAR PROTECTION', 'Di dunia yang keras, lo butuh pelindung. Kaos ini hadir dengan visual dua malaikat penjaga, cocok buat lo yang punya prinsip tapi tetap tampil brutal.', 125000, 'png/206.jpg'),
(8, 'VOIDWEAR NIGHTFUSE REFLECTOR', 'Celana streetwear dengan aksen garis reflektif menyala. Terinspirasi dari dunia futuristik dan malam kota—lo bakal bersinar, literally.', 150000, 'png/301.jpg'),
(9, 'VOIDWEAR DUSK UTILITY PANTS', 'Cargo longgar dengan tali serut di bagian bawah dan saku multifungsi. Siap nemenin lo dari pagi sampai malam, nyaman tapi tetap tajam.', 150000, 'png/302.jpg'),
(10, 'VOIDWEAR CORE OPS CARGO', 'Celana cargo clean look dengan siluet simple. Buat kamu yang suka outfit minimal tapi tetap aktif dan versatile.', 150000, 'png/303.jpg'),
(11, 'VOIDWEAR VOIDTACTICAL DROP', 'Loose utility pants dengan banyak kantong dan potongan jatuh—desain fungsional buat aktivitas harian yang padat dan fleksibel.', 150000, 'png/304.jpg'),
(12, 'VOIDWEAR SHADOWLINE CARGO', 'Celana cargo hitam dengan jahitan putih kontras—silhouette tegas dan bold, cocok buat lo yang suka tampilan street dominan tapi tetap clean.', 150000, 'png/305.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
