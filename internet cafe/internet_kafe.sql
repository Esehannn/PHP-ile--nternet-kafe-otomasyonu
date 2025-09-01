-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3307
-- Üretim Zamanı: 01 Eyl 2025, 12:03:56
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `internet_kafe`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ayarlar`
--

CREATE TABLE `ayarlar` (
  `id` int(11) NOT NULL,
  `saatlik_ucret` decimal(10,2) NOT NULL DEFAULT 50.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `ayarlar`
--

INSERT INTO `ayarlar` (`id`, `saatlik_ucret`) VALUES
(1, 150.00);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `bilgisayar`
--

CREATE TABLE `bilgisayar` (
  `Bilgisayar_ID` int(11) NOT NULL,
  `Masa_ID` int(11) NOT NULL,
  `Ekran_Karti` varchar(50) DEFAULT NULL,
  `Islemci` varchar(50) DEFAULT NULL,
  `Calisma_Durumu` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `bilgisayar`
--

INSERT INTO `bilgisayar` (`Bilgisayar_ID`, `Masa_ID`, `Ekran_Karti`, `Islemci`, `Calisma_Durumu`) VALUES
(1, 1, 'NVIDIA GTX 1650', 'Intel i5', 'Çalışıyor'),
(2, 2, 'AMD Radeon RX 580', 'Ryzen 5', 'Çalışıyor'),
(3, 3, 'Intel UHD', 'Intel i3', 'Bakımda'),
(4, 4, 'NVIDIA RTX 3060', 'Intel i7', 'Çalışıyor'),
(5, 5, 'AMD Vega 8', 'Ryzen 3', 'Arızalı');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `gorev`
--

CREATE TABLE `gorev` (
  `Gorev_ID` int(11) NOT NULL,
  `Gorev_Tanimi` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `gorev`
--

INSERT INTO `gorev` (`Gorev_ID`, `Gorev_Tanimi`) VALUES
(1, 'Yönetici'),
(2, 'Destek Personeli');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hizmet`
--

CREATE TABLE `hizmet` (
  `Hizmet_ID` int(11) NOT NULL,
  `Hizmet_Adi` varchar(50) DEFAULT NULL,
  `Hizmet_Ucreti` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `hizmet`
--

INSERT INTO `hizmet` (`Hizmet_ID`, `Hizmet_Adi`, `Hizmet_Ucreti`) VALUES
(1, 'Çay', 5.00),
(2, 'Kahve', 8.00),
(3, 'Sandviç', 25.00),
(4, 'Kola', 7.00),
(5, 'Kurabiye', 6.00),
(6, 'mocha', 55.00);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `masa`
--

CREATE TABLE `masa` (
  `Masa_ID` int(11) NOT NULL,
  `Masa_No` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `masa`
--

INSERT INTO `masa` (`Masa_ID`, `Masa_No`) VALUES
(1, 'Masa 1'),
(2, 'Masa 2'),
(3, 'Masa 3'),
(4, 'Masa 4'),
(5, 'Masa 5'),
(9, 'Masa 6'),
(10, 'Masa 7'),
(11, 'Masa 8');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `musteri`
--

CREATE TABLE `musteri` (
  `Musteri_ID` int(11) NOT NULL,
  `Ad` varchar(50) NOT NULL,
  `Soyad` varchar(50) NOT NULL,
  `Telefon_NO` varchar(15) DEFAULT NULL,
  `E_Posta` varchar(50) DEFAULT NULL,
  `Kayit_Tarihi` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `musteri`
--

INSERT INTO `musteri` (`Musteri_ID`, `Ad`, `Soyad`, `Telefon_NO`, `E_Posta`, `Kayit_Tarihi`) VALUES
(1, 'Ali', 'Yılmaz', '05551234567', 'ali@gmail.com', '2024-03-01'),
(2, 'Ayşe', 'Demir', '05559876543', 'ayse@gmail.com', '2024-03-02'),
(3, 'Mehmet', 'Kaya', '05557775555', 'mehmet@gmail.com', '2024-03-03'),
(4, 'Zeynep', 'Şahin', '05556667788', 'zeynep@gmail.com', '2024-03-04'),
(5, 'Hasan', 'Çelik', '05553334444', 'hasan@gmail.com', '2024-03-05'),
(22, 'ahmet', 'salih', '05443332211', 'ese@hotmail.com', '2025-03-30'),
(24, 'kamil', 'kamil', '05223334411', 'ese@hotmail.com', '2025-03-30'),
(37, 'mehmet', 'demiralp', '0564343224', 'mehmet@hotmail.com', '2025-04-11'),
(38, 'ahmet', 'ket', '0545334354', 'ahhmet@hotmail.com', '2025-04-11'),
(39, 'Salih', 'Muratlıoğlu', '0542343243', 'salihh@hotmail.com', '2025-04-11'),
(41, 'esehan', 'pekoldu', '054323443', 'ese@hotmail.com', '2025-04-11'),
(43, 'Misafir', 'Müşteri_20250411_122706', NULL, NULL, '2025-04-11'),
(44, 'Misafir', 'Müşteri_20250411_122710', NULL, NULL, '2025-04-11'),
(45, 'Misafir', 'Müşteri_20250411_122712', NULL, NULL, '2025-04-11'),
(46, 'Misafir', 'Müşteri_20250411_122718', NULL, NULL, '2025-04-11'),
(47, 'Misafir', 'Müşteri_20250411_122719', NULL, NULL, '2025-04-11'),
(48, 'Misafir', 'Müşteri_20250411_122721', NULL, NULL, '2025-04-11'),
(49, 'Misafir', 'Müşteri_20250411_122725', NULL, NULL, '2025-04-11'),
(50, 'Misafir', 'Müşteri_20250411_122728', NULL, NULL, '2025-04-11'),
(51, 'Misafir', 'Müşteri_20250411_124735', NULL, NULL, '2025-04-11'),
(52, 'Misafir', 'Müşteri_20250411_142350', NULL, NULL, '2025-04-11'),
(53, 'Misafir', 'Müşteri_20250601_221927', NULL, NULL, '2025-06-01'),
(54, 'Misafir', 'Müşteri_20250602_104805', NULL, NULL, '2025-06-02'),
(55, 'Misafir', 'Müşteri_20250602_104913', NULL, NULL, '2025-06-02'),
(56, 'Misafir', 'Müşteri_20250604_145859', NULL, NULL, '2025-06-04'),
(57, 'Misafir', 'Müşteri_20250604_155010', NULL, NULL, '2025-06-04'),
(58, 'Misafir', 'Müşteri_20250604_155013', NULL, NULL, '2025-06-04'),
(59, 'Misafir', 'Müşteri_20250604_155021', NULL, NULL, '2025-06-04'),
(60, 'Misafir', 'Müşteri_20250609_175842', NULL, NULL, '2025-06-09'),
(61, 'Misafir', 'Müşteri_20250609_180103', NULL, NULL, '2025-06-09'),
(62, 'Misafir', 'Müşteri_20250610_110105', NULL, NULL, '2025-06-10');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `odeme`
--

CREATE TABLE `odeme` (
  `Odeme_ID` int(11) NOT NULL,
  `Oturum_ID` int(11) NOT NULL,
  `Odeme_Tarihi` date DEFAULT NULL,
  `Odeme_Tutari` decimal(10,2) DEFAULT NULL,
  `Odeme_Turu` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `odeme`
--

INSERT INTO `odeme` (`Odeme_ID`, `Oturum_ID`, `Odeme_Tarihi`, `Odeme_Tutari`, `Odeme_Turu`) VALUES
(2, 2, '2024-03-11', 75.00, 'Nakit'),
(5, 5, '2024-03-14', 80.00, 'Kredi Kartı'),
(8, 2, '2025-03-29', 250.00, 'Nakit'),
(11, 2, '2025-03-29', 0.00, 'Nakit'),
(12, 2, '2025-03-29', 0.00, 'Nakit'),
(14, 5, '2025-03-29', 600.00, 'Nakit'),
(17, 2, '2025-03-29', 0.00, 'Nakit'),
(18, 2, '2025-03-29', 50.00, 'Nakit'),
(21, 2, '2025-03-29', 50.00, 'Nakit'),
(23, 5, '2025-03-29', 50.00, 'Nakit'),
(34, 2, '2025-03-29', 0.00, 'Nakit'),
(37, 5, '2025-03-29', 0.00, 'Nakit'),
(51, 2, '2025-04-03', 105.00, 'Kredi Kartı'),
(57, 2, '2025-04-09', 100.00, 'Nakit'),
(58, 15, '2025-04-09', 562.00, 'Nakit'),
(61, 5, '2025-04-09', 600.00, 'Nakit'),
(70, 28, '2025-04-11', 0.00, 'Nakit'),
(71, 29, '2025-04-11', 0.00, 'Nakit'),
(72, 30, '2025-04-11', 0.00, 'Nakit'),
(73, 31, '2025-04-11', 0.00, 'Nakit'),
(74, 32, '2025-04-11', 0.00, 'Nakit'),
(75, 33, '2025-04-11', 0.00, 'Nakit'),
(76, 34, '2025-04-11', 0.00, 'Nakit'),
(77, 35, '2025-04-11', 0.00, 'Nakit'),
(78, 38, '2025-04-11', 8.00, 'Nakit'),
(79, 36, '2025-04-11', 74.00, 'Nakit'),
(80, 39, '2025-04-11', 105.00, 'Nakit'),
(81, 40, '2025-04-11', 0.00, 'Nakit'),
(82, 37, '2025-04-11', 50.00, 'Nakit'),
(83, 41, '2025-04-11', 50.00, 'Nakit'),
(84, 41, '2025-04-11', 50.00, 'Nakit'),
(85, 42, '2025-04-11', 50.00, 'Nakit'),
(86, 43, '2025-04-11', 50.00, 'Nakit'),
(87, 42, '2025-04-11', 215.00, 'Kredi Kartı'),
(88, 43, '2025-04-11', 50.00, 'Nakit'),
(89, 44, '2025-06-01', 50.00, 'Nakit'),
(90, 44, '2025-06-01', 50.00, 'Nakit'),
(91, 45, '2025-06-02', 50.00, 'Nakit'),
(92, 45, '2025-06-02', 56.00, 'Kredi Kartı'),
(93, 46, '2025-06-02', 50.00, 'Nakit'),
(94, 46, '2025-06-02', 81.00, 'Kredi Kartı'),
(95, 47, '2025-06-04', 50.00, 'Nakit'),
(96, 47, '2025-06-04', 100.00, 'Nakit'),
(97, 48, '2025-06-04', 50.00, 'Nakit'),
(98, 49, '2025-06-04', 50.00, 'Nakit'),
(99, 50, '2025-06-04', 50.00, 'Nakit'),
(100, 51, '2025-06-04', 50.00, 'Nakit'),
(101, 52, '2025-06-04', 50.00, 'Nakit'),
(102, 53, '2025-06-04', 50.00, 'Nakit'),
(103, 54, '2025-06-04', 50.00, 'Nakit'),
(104, 55, '2025-06-04', 50.00, 'Nakit'),
(105, 48, '2025-06-04', 100.00, 'Nakit'),
(106, 49, '2025-06-04', 100.00, 'Nakit'),
(107, 50, '2025-06-04', 100.00, 'Nakit'),
(108, 51, '2025-06-04', 155.00, 'Nakit'),
(109, 52, '2025-06-04', 100.00, 'Kredi Kartı'),
(110, 53, '2025-06-04', 100.00, 'Nakit'),
(111, 54, '2025-06-04', 265.00, 'Nakit'),
(112, 55, '2025-06-04', 775.00, 'Nakit'),
(113, 56, '2025-06-09', 50.00, 'Nakit'),
(114, 56, '2025-06-09', 125.00, 'Kredi Kartı'),
(115, 57, '2025-06-09', 50.00, 'Nakit'),
(116, 57, '2025-06-09', 100.00, 'Kredi Kartı'),
(117, 58, '2025-06-09', 50.00, 'Nakit'),
(119, 58, '2025-06-09', 100.00, 'Kredi Kartı'),
(121, 61, '2025-06-10', 150.00, 'Kredi Kartı'),
(122, 62, '2025-06-30', 1800.00, 'Nakit'),
(123, 63, '2025-07-01', 205.00, 'Nakit');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `oturum`
--

CREATE TABLE `oturum` (
  `Oturum_ID` int(11) NOT NULL,
  `Musteri_ID` int(11) DEFAULT NULL,
  `Masa_ID` int(11) NOT NULL,
  `Baslangic_Zamani` datetime DEFAULT NULL,
  `Bitis_Zamani` datetime DEFAULT NULL,
  `Durum` varchar(20) DEFAULT 'Açık'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `oturum`
--

INSERT INTO `oturum` (`Oturum_ID`, `Musteri_ID`, `Masa_ID`, `Baslangic_Zamani`, `Bitis_Zamani`, `Durum`) VALUES
(2, 3, 2, '2025-04-08 21:55:22', '2025-04-09 00:21:01', 'Kapalı'),
(5, 4, 5, '2025-04-08 22:47:00', '2025-04-09 11:02:34', 'Kapalı'),
(15, 3, 1, '2025-04-08 23:22:39', '2025-04-09 11:02:21', 'Kapalı'),
(28, 43, 1, '2025-04-11 12:27:06', '2025-04-11 13:27:35', 'Kapalı'),
(29, 44, 2, '2025-04-11 12:27:10', '2025-04-11 13:27:39', 'Kapalı'),
(30, 45, 3, '2025-04-11 12:27:12', '2025-04-11 13:27:41', 'Kapalı'),
(31, 46, 4, '2025-04-11 12:27:18', '2025-04-11 13:27:44', 'Kapalı'),
(32, 47, 5, '2025-04-11 12:27:19', '2025-04-11 13:27:47', 'Kapalı'),
(33, 48, 9, '2025-04-11 12:27:21', '2025-04-11 13:27:50', 'Kapalı'),
(34, 49, 10, '2025-04-11 12:27:26', '2025-04-11 13:27:53', 'Kapalı'),
(35, 50, 11, '2025-04-11 12:27:28', '2025-04-11 13:27:57', 'Kapalı'),
(36, 1, 1, '2025-04-11 12:44:46', '2025-04-11 14:13:55', 'Kapalı'),
(37, 51, 2, '2025-04-11 12:47:35', '2025-04-11 14:16:26', 'Kapalı'),
(38, 22, 3, '2025-04-11 13:12:34', '2025-04-11 14:12:45', 'Kapalı'),
(39, 1, 1, '2025-04-11 13:14:01', '2025-04-11 14:15:17', 'Kapalı'),
(40, 1, 1, '2025-04-11 13:15:28', '2025-04-11 14:16:22', 'Kapalı'),
(41, 1, 1, '2025-04-11 14:18:28', '2025-04-11 14:18:34', 'Kapalı'),
(42, 1, 1, '2025-04-11 14:22:50', '2025-04-11 14:31:03', 'Kapalı'),
(43, 52, 2, '2025-04-11 14:23:50', '2025-04-11 14:38:09', 'Kapalı'),
(44, 53, 1, '2025-06-01 22:19:27', '2025-06-01 22:20:14', 'Kapalı'),
(45, 54, 1, '2025-06-02 10:48:05', '2025-06-02 10:48:43', 'Kapalı'),
(46, 55, 1, '2025-06-02 10:49:13', '2025-06-02 10:49:28', 'Kapalı'),
(47, 56, 1, '2025-06-04 14:58:59', '2025-06-04 15:16:47', 'Kapalı'),
(48, 41, 1, '2025-06-04 15:50:02', '2025-06-04 16:00:57', 'Kapalı'),
(49, 37, 2, '2025-06-04 15:50:05', '2025-06-04 16:01:00', 'Kapalı'),
(50, 4, 3, '2025-06-04 15:50:07', '2025-06-04 16:01:02', 'Kapalı'),
(51, 57, 4, '2025-06-04 15:50:10', '2025-06-04 16:01:10', 'Kapalı'),
(52, 58, 5, '2025-06-04 15:50:13', '2025-06-04 16:01:13', 'Kapalı'),
(53, 2, 9, '2025-06-04 15:50:17', '2025-06-04 16:01:16', 'Kapalı'),
(54, 59, 10, '2025-06-04 15:50:21', '2025-06-04 16:06:24', 'Kapalı'),
(55, 22, 11, '2025-06-04 15:50:32', '2025-06-04 16:06:41', 'Kapalı'),
(56, 5, 1, '2025-06-09 17:53:03', '2025-06-09 17:53:11', 'Kapalı'),
(57, 60, 1, '2025-06-09 17:58:42', '2025-06-09 18:00:57', 'Kapalı'),
(58, 61, 1, '2025-06-09 18:01:03', '2025-06-09 18:08:41', 'Kapalı'),
(61, 39, 1, '2025-06-10 10:59:28', '2025-06-10 10:59:31', 'Kapalı'),
(62, 62, 1, '2025-06-10 11:01:05', '2025-06-30 23:25:08', 'Kapalı'),
(63, 24, 1, '2025-07-01 10:32:11', '2025-07-01 10:32:24', 'Kapalı');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `oturum_hizmet`
--

CREATE TABLE `oturum_hizmet` (
  `ID` int(11) NOT NULL,
  `Oturum_ID` int(11) DEFAULT NULL,
  `Hizmet_ID` int(11) DEFAULT NULL,
  `Adet` int(11) DEFAULT 1,
  `Eklenme_Tarihi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `oturum_hizmet`
--

INSERT INTO `oturum_hizmet` (`ID`, `Oturum_ID`, `Hizmet_ID`, `Adet`, `Eklenme_Tarihi`) VALUES
(11, 15, 3, 1, '2025-04-09 00:23:06'),
(17, 36, 2, 3, '2025-04-11 14:12:00'),
(18, 38, 2, 1, '2025-04-11 14:12:38'),
(19, 39, 6, 1, '2025-04-11 14:14:08'),
(20, 42, 6, 3, '2025-04-11 14:24:52'),
(21, 45, 5, 1, '2025-06-02 10:48:36'),
(22, 46, 5, 1, '2025-06-02 10:49:17'),
(23, 46, 3, 1, '2025-06-02 10:49:20'),
(24, 51, 6, 1, '2025-06-04 16:01:07'),
(25, 54, 6, 3, '2025-06-04 16:05:50'),
(26, 55, 1, 10, '2025-06-04 16:06:32'),
(27, 55, 3, 25, '2025-06-04 16:06:38'),
(28, 56, 3, 1, '2025-06-09 17:53:07'),
(31, 63, 6, 1, '2025-07-01 10:32:19');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `personel`
--

CREATE TABLE `personel` (
  `Personel_ID` int(11) NOT NULL,
  `Gorev_ID` int(11) DEFAULT NULL,
  `Ad` varchar(50) NOT NULL,
  `Soyad` varchar(50) NOT NULL,
  `Telefon_No` varchar(15) DEFAULT NULL,
  `E_Posta` varchar(50) DEFAULT NULL,
  `Sifre` varchar(50) DEFAULT NULL,
  `Calisma_Saatleri` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `personel`
--

INSERT INTO `personel` (`Personel_ID`, `Gorev_ID`, `Ad`, `Soyad`, `Telefon_No`, `E_Posta`, `Sifre`, `Calisma_Saatleri`) VALUES
(1, 1, 'Esehan', 'Pekdoğan', '05555555555', 'admin@internetkafe.com', '123456', '09:00 - 18:00'),
(2, 2, 'Ahmet', 'Yılmaz', '05556667788', 'ahmet@internetkafe.com', '123456', '10:00 - 19:00'),
(5, 2, 'Ahmet', 'Demir', '0545454545', '', '', '10:00 - 19:00');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `satis`
--

CREATE TABLE `satis` (
  `Satis_ID` int(11) NOT NULL,
  `Hizmet_ID` int(11) NOT NULL,
  `Musteri_ID` int(11) NOT NULL,
  `Tarih` date DEFAULT NULL,
  `Adet` int(11) DEFAULT NULL,
  `Toplam_Tutar` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `satis`
--

INSERT INTO `satis` (`Satis_ID`, `Hizmet_ID`, `Musteri_ID`, `Tarih`, `Adet`, `Toplam_Tutar`) VALUES
(1, 1, 1, '2024-03-10', 2, 10.00),
(2, 2, 2, '2024-03-11', 1, 8.00),
(3, 3, 3, '2024-03-12', 1, 12.00),
(4, 4, 4, '2024-03-13', 2, 14.00),
(5, 5, 5, '2024-03-14', 3, 18.00);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `ayarlar`
--
ALTER TABLE `ayarlar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `bilgisayar`
--
ALTER TABLE `bilgisayar`
  ADD PRIMARY KEY (`Bilgisayar_ID`),
  ADD KEY `Masa_ID` (`Masa_ID`);

--
-- Tablo için indeksler `gorev`
--
ALTER TABLE `gorev`
  ADD PRIMARY KEY (`Gorev_ID`);

--
-- Tablo için indeksler `hizmet`
--
ALTER TABLE `hizmet`
  ADD PRIMARY KEY (`Hizmet_ID`);

--
-- Tablo için indeksler `masa`
--
ALTER TABLE `masa`
  ADD PRIMARY KEY (`Masa_ID`);

--
-- Tablo için indeksler `musteri`
--
ALTER TABLE `musteri`
  ADD PRIMARY KEY (`Musteri_ID`);

--
-- Tablo için indeksler `odeme`
--
ALTER TABLE `odeme`
  ADD PRIMARY KEY (`Odeme_ID`),
  ADD KEY `odeme_ibfk_1` (`Oturum_ID`);

--
-- Tablo için indeksler `oturum`
--
ALTER TABLE `oturum`
  ADD PRIMARY KEY (`Oturum_ID`),
  ADD KEY `Musteri_ID` (`Musteri_ID`),
  ADD KEY `oturum_ibfk_2` (`Masa_ID`);

--
-- Tablo için indeksler `oturum_hizmet`
--
ALTER TABLE `oturum_hizmet`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Oturum_ID` (`Oturum_ID`),
  ADD KEY `Hizmet_ID` (`Hizmet_ID`);

--
-- Tablo için indeksler `personel`
--
ALTER TABLE `personel`
  ADD PRIMARY KEY (`Personel_ID`),
  ADD KEY `Gorev_ID` (`Gorev_ID`);

--
-- Tablo için indeksler `satis`
--
ALTER TABLE `satis`
  ADD PRIMARY KEY (`Satis_ID`),
  ADD KEY `Hizmet_ID` (`Hizmet_ID`),
  ADD KEY `Musteri_ID` (`Musteri_ID`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `ayarlar`
--
ALTER TABLE `ayarlar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `bilgisayar`
--
ALTER TABLE `bilgisayar`
  MODIFY `Bilgisayar_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `gorev`
--
ALTER TABLE `gorev`
  MODIFY `Gorev_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `hizmet`
--
ALTER TABLE `hizmet`
  MODIFY `Hizmet_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Tablo için AUTO_INCREMENT değeri `masa`
--
ALTER TABLE `masa`
  MODIFY `Masa_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Tablo için AUTO_INCREMENT değeri `musteri`
--
ALTER TABLE `musteri`
  MODIFY `Musteri_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- Tablo için AUTO_INCREMENT değeri `odeme`
--
ALTER TABLE `odeme`
  MODIFY `Odeme_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- Tablo için AUTO_INCREMENT değeri `oturum`
--
ALTER TABLE `oturum`
  MODIFY `Oturum_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- Tablo için AUTO_INCREMENT değeri `oturum_hizmet`
--
ALTER TABLE `oturum_hizmet`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Tablo için AUTO_INCREMENT değeri `personel`
--
ALTER TABLE `personel`
  MODIFY `Personel_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `satis`
--
ALTER TABLE `satis`
  MODIFY `Satis_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `bilgisayar`
--
ALTER TABLE `bilgisayar`
  ADD CONSTRAINT `bilgisayar_ibfk_1` FOREIGN KEY (`Masa_ID`) REFERENCES `masa` (`Masa_ID`);

--
-- Tablo kısıtlamaları `odeme`
--
ALTER TABLE `odeme`
  ADD CONSTRAINT `odeme_ibfk_1` FOREIGN KEY (`Oturum_ID`) REFERENCES `oturum` (`Oturum_ID`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `oturum`
--
ALTER TABLE `oturum`
  ADD CONSTRAINT `oturum_ibfk_1` FOREIGN KEY (`Musteri_ID`) REFERENCES `musteri` (`Musteri_ID`),
  ADD CONSTRAINT `oturum_ibfk_2` FOREIGN KEY (`Masa_ID`) REFERENCES `masa` (`Masa_ID`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `oturum_hizmet`
--
ALTER TABLE `oturum_hizmet`
  ADD CONSTRAINT `oturum_hizmet_ibfk_1` FOREIGN KEY (`Oturum_ID`) REFERENCES `oturum` (`Oturum_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `oturum_hizmet_ibfk_2` FOREIGN KEY (`Hizmet_ID`) REFERENCES `hizmet` (`Hizmet_ID`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `personel`
--
ALTER TABLE `personel`
  ADD CONSTRAINT `personel_ibfk_1` FOREIGN KEY (`Gorev_ID`) REFERENCES `gorev` (`Gorev_ID`);

--
-- Tablo kısıtlamaları `satis`
--
ALTER TABLE `satis`
  ADD CONSTRAINT `satis_ibfk_1` FOREIGN KEY (`Hizmet_ID`) REFERENCES `hizmet` (`Hizmet_ID`),
  ADD CONSTRAINT `satis_ibfk_2` FOREIGN KEY (`Musteri_ID`) REFERENCES `musteri` (`Musteri_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
