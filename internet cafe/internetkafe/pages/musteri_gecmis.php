<?php
// Veritabanı bağlantı dosyasını ve sayfanın üst (header) kısmını dahil eder
include("../includes/config.php");
include("../includes/header.php");

// Eğer URL'de müşteri ID'si belirtilmemişse işlemi iptal eder
if (!isset($_GET['id'])) {
    echo "<p style='color:red;'>Müşteri ID eksik.</p>";
    exit();
}

$musteri_id = $_GET['id']; // Müşteri ID'si GET ile alınır

// Müşteri bilgileri çekilir
$musteri = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM Musteri WHERE Musteri_ID = $musteri_id
"));

// Bu müşterinin tüm oturum bilgileri çekilir (en yeni oturum en üstte olacak şekilde)
$oturumlar = mysqli_query($conn, "
    SELECT O.*, M.Masa_No 
    FROM Oturum O 
    JOIN Masa M ON M.Masa_ID = O.Masa_ID 
    WHERE O.Musteri_ID = $musteri_id
    ORDER BY O.Baslangic_Zamani DESC
");

// Bu müşterinin yaptığı tüm ödemeler çekilir
$odemeler = mysqli_query($conn, "
    SELECT * FROM Odeme 
    WHERE Oturum_ID IN (
        SELECT Oturum_ID FROM Oturum WHERE Musteri_ID = $musteri_id
    )
    ORDER BY Odeme_Tarihi DESC
");
?>

<!-- Sayfa başlığı (müşteri adıyla birlikte) -->
<h2 style="text-align:center;">
    <?php echo $musteri['Ad'] . ' ' . $musteri['Soyad']; ?> - Geçmiş Kayıtları
</h2>

<?php
// Müşterinin yaptığı tüm ödemelerin toplamını hesaplar
$toplam_odeme_sorgu = mysqli_query($conn, "
    SELECT SUM(Odeme_Tutari) AS toplam 
    FROM Odeme 
    WHERE Oturum_ID IN (
        SELECT Oturum_ID FROM Oturum WHERE Musteri_ID = $musteri_id
    )
");
$toplam_odeme = mysqli_fetch_assoc($toplam_odeme_sorgu)['toplam'] ?? 0;

// Bu müşterinin açtığı toplam oturum sayısını hesaplar
$toplam_oturum_sorgu = mysqli_query($conn, "
    SELECT COUNT(*) AS toplam 
    FROM Oturum 
    WHERE Musteri_ID = $musteri_id
");
$toplam_oturum = mysqli_fetch_assoc($toplam_oturum_sorgu)['toplam'] ?? 0;

// Bu müşterinin yaptığı en son ödeme tarihini çeker
$son_odeme_sorgu = mysqli_query($conn, "
    SELECT MAX(Odeme_Tarihi) AS son 
    FROM Odeme 
    WHERE Oturum_ID IN (
        SELECT Oturum_ID FROM Oturum WHERE Musteri_ID = $musteri_id
    )
");
$son_odeme = mysqli_fetch_assoc($son_odeme_sorgu)['son'] ?? '-';
?>

<!-- Kullanıcıya genel bilgiler gösterilir -->
<div style="text-align:center; margin: 20px 0;">
<?php
// Toplam süreyi hesaplamak için tüm bitmiş oturumlar çekilir
$toplam_saniye = 0;
$oturum_sureleri = mysqli_query($conn, "
    SELECT Baslangic_Zamani, Bitis_Zamani 
    FROM Oturum 
    WHERE Musteri_ID = $musteri_id AND Durum = 'Kapalı'
");

// Oturumlar arasında döner ve her birinin süresini toplar
while ($o = mysqli_fetch_assoc($oturum_sureleri)) {
    if ($o['Bitis_Zamani']) {
        $bas = strtotime($o['Baslangic_Zamani']); // Başlangıç zamanı timestamp'e çevrilir
        $bit = strtotime($o['Bitis_Zamani']);     // Bitiş zamanı timestamp'e çevrilir
        $fark = $bit - $bas;                      // Saniye cinsinden fark hesaplanır
        $toplam_saniye += $fark;
    }
}

// Toplam süre saat-dakika-saniye cinsine çevrilir
$toplam_saat = floor($toplam_saniye / 3600);
$kalan_saniye = $toplam_saniye % 3600;
$toplam_dakika = floor($kalan_saniye / 60);
$toplam_saniye_son = $kalan_saniye % 60;

// Kullanıcıya toplam süre gösterilir
echo "<p><strong>⏳ Toplam Süre:</strong> $toplam_saat saat $toplam_dakika dakika $toplam_saniye_son saniye</p>";
?>

    <p><strong>🧾 Toplam Ödeme:</strong> <?php echo number_format($toplam_odeme, 2); ?> ₺</p>
    <p><strong>🖥️ Toplam Oturum:</strong> <?php echo $toplam_oturum; ?> kez</p>
    <p><strong>⏰ Son Ödeme Tarihi:</strong> <?php echo $son_odeme ?: '-'; ?></p>
</div>

<!-- Son Oturumlar Tablosu -->
<h3 style="text-align:center;">🖥️ Son Oturum</h3>
<table border="1" cellpadding="8" cellspacing="0" style="margin:auto;">
    <tr>
        <th>Masa</th>
        <th>Başlangıç</th>
        <th>Bitiş</th>
        <th>Durum</th>
    </tr>
    <?php while ($o = mysqli_fetch_assoc($oturumlar)) { ?>
    <tr>
        <td><?php echo $o['Masa_No']; ?></td>
        <td><?php echo $o['Baslangic_Zamani']; ?></td>
        <td><?php echo $o['Bitis_Zamani'] ?? "-"; ?></td>
        <td><?php echo $o['Durum']; ?></td>
    </tr>
    <?php } ?>
</table>

<!-- Ödeme Geçmişi Tablosu -->
<h3 style="text-align:center; margin-top:30px;">💰 Ödemeler</h3>
<table border="1" cellpadding="8" cellspacing="0" style="margin:auto;">
    <tr>
        <th>Tarih</th>
        <th>Tutar</th>
        <th>Tür</th>
    </tr>
    <?php while ($o = mysqli_fetch_assoc($odemeler)) { ?>
    <tr>
        <td><?php echo $o['Odeme_Tarihi']; ?></td>
        <td><?php echo $o['Odeme_Tutari']; ?> ₺</td>
        <td><?php echo $o['Odeme_Turu']; ?></td>
    </tr>
    <?php } ?>
</table>

<!-- Geri dön butonu -->
<p style="text-align:center; margin-top:20px;">
    <a href="musteriler.php" class="btn">⬅ Geri Dön</a>
</p>

<!-- Sayfanın alt kısmı -->
<?php include("../includes/footer.php"); ?>
