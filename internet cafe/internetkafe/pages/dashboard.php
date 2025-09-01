<?php
// Veritabanı bağlantısını ve üst menüyü dahil et
include("../includes/config.php");
include("../includes/header.php");

// Ayarlar tablosundan saatlik ücreti çek
$ucret_sorgu = mysqli_query($conn, "SELECT saatlik_ucret FROM Ayarlar LIMIT 1");
$ucret_row = mysqli_fetch_assoc($ucret_sorgu);
$saatlik_ucret = $ucret_row['saatlik_ucret'] ?? 50;

// Formdan güncelleme gelirse işle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['yeni_ucret'])) {
    $yeni_ucret = floatval($_POST['yeni_ucret']);
    if ($yeni_ucret > 0) {
        mysqli_query($conn, "UPDATE Ayarlar SET saatlik_ucret = $yeni_ucret WHERE id = 1");
        echo "<script>alert('Saatlik ücret güncellendi!'); window.location.href='dashboard.php';</script>";
        exit;
    } else {
        echo "<p style='color:red;'>Geçerli bir ücret girin!</p>";
    }
}


// Toplam müşteri sayısını al
$musteri_sorgu = mysqli_query($conn, "SELECT COUNT(*) AS toplam FROM Musteri");
$musteri_say = mysqli_fetch_assoc($musteri_sorgu);

// Tüm ödemelerin toplam tutarını al
$odeme_sorgu = mysqli_query($conn, "SELECT SUM(Odeme_Tutari) AS toplam FROM Odeme");
$odeme_toplam = mysqli_fetch_assoc($odeme_sorgu);

// Durumu "Açık" olan aktif oturum sayısını al
$aktif_sorgu = mysqli_query($conn, "SELECT COUNT(*) AS aktif FROM Oturum WHERE Durum = 'Açık'");
$aktif_say = mysqli_fetch_assoc($aktif_sorgu);

// Bugünün tarihini al
$bugun = date('Y-m-d');

// Sadece bugünkü ödemelerin toplamını al
$bugun_odeme = mysqli_query($conn, "SELECT SUM(Odeme_Tutari) AS toplam FROM Odeme WHERE DATE(Odeme_Tarihi) = '$bugun'");
$bugun_toplam = mysqli_fetch_assoc($bugun_odeme);
?>


<link rel="stylesheet" href="../css/dashboard.css">


<div class="container">
    <h2>📊 Yönetim Paneli</h2>

    <div class="istatistikler">
        <div class="istatistik-kutu">
            <h3>👥 Toplam Müşteri</h3>
            <p><?php echo $musteri_say['toplam']; ?></p>
        </div>
        <div class="istatistik-kutu">
            <h3>💰 Toplam Ödeme</h3>
            <p><?php echo number_format($odeme_toplam['toplam'] ?? 0, 2); ?> ₺</p>
        </div>
        <div class="istatistik-kutu">
            <h3>🟢 Aktif Oturum</h3>
            <p><?php echo $aktif_say['aktif']; ?></p>
        </div>
        <div class="istatistik-kutu">
            <h3>📅 Bugünkü Kazanç</h3>
            <p><?php echo number_format($bugun_toplam['toplam'] ?? 0, 2); ?> ₺</p>
        </div>
    </div>

    <!-- Saatlik Ücreti Güncelleme Formu -->
<div class="saatlik-ucret-card">
    <div class="saatlik-ucret-header">
        <span class="ucret-ikon">⏱️</span>
        <span>Saatlik Ücret Güncelle</span>
    </div>
    <form method="post" class="saatlik-ucret-form">
        <label for="yeni_ucret">Yeni Saatlik Ücret (₺):</label>
        <input type="number" step="0.01" min="1" name="yeni_ucret" id="yeni_ucret"
            value="<?php echo htmlspecialchars($saatlik_ucret); ?>" required>
        <button type="submit">Güncelle</button>
        <span class="su-an-ucret">Şu an: <b><?php echo number_format($saatlik_ucret,2); ?> ₺/saat</b></span>
    </form>
</div>

    
    <h3 class="link-baslik">🔗 Kısayollar</h3>

    <div class="kisayol-kutular">
        <a href="musteriler.php" class="link-kutu">👤 Müşteri Yönetimi</a>
        <a href="masalar.php" class="link-kutu">🪑 Masalar Yönetimi</a>
        <a href="personeller.php" class="link-kutu">💻 Personel Yönetimi</a>
        <a href="odemeler.php" class="link-kutu">💸 Ödemeler</a>
        <a href="hizmetler.php" class="link-kutu">🍽️ Hizmetler</a>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
