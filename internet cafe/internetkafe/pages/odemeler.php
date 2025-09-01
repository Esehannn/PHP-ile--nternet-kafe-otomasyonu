<?php
// Veritabanı bağlantısı ve üst sayfa yapısı dahil ediliyor
include("../includes/config.php");
include("../includes/header.php");

// GET ile gelen filtreleme değerleri alınıyor (boşsa '' atanıyor)
$baslangic_tarih = $_GET['baslangic_tarih'] ?? '';
$bitis_tarih = $_GET['bitis_tarih'] ?? '';

// Sayfalama için limit ve hangi sayfada olunduğu ayarlanıyor
$limit = 10; // Her sayfada 10 kayıt gösterilecek
$sayfa = isset($_GET['sayfa']) ? (int)$_GET['sayfa'] : 1;
$baslangic = ($sayfa - 1) * $limit; // SQL LIMIT için başlangıç noktası

// Filtreleme varsa WHERE koşulu hazırlanıyor
$kosul = "";
if (!empty($baslangic_tarih) && !empty($bitis_tarih)) {
    $kosul = "WHERE O.Odeme_Tarihi BETWEEN '$baslangic_tarih' AND '$bitis_tarih'";
}

// Toplam ödeme sayısı çekiliyor (sayfalama için gerekli)
$toplam_sorgu = mysqli_query($conn, "
    SELECT COUNT(*) AS toplam 
    FROM Odeme O 
    JOIN Oturum OT ON OT.Oturum_ID = O.Oturum_ID
    JOIN Masa M ON M.Masa_ID = OT.Masa_ID
    $kosul
");
$toplam_kayit = mysqli_fetch_assoc($toplam_sorgu)['toplam'];
$toplam_sayfa = ceil($toplam_kayit / $limit); // Toplam sayfa sayısı hesaplanıyor

// Ödemeler çekiliyor (masa ve müşteri bilgileri ile birlikte)
$odemeler = mysqli_query($conn, "
    SELECT O.Odeme_ID, O.Odeme_Tarihi, O.Odeme_Tutari, O.Odeme_Turu,
           M.Masa_No, MU.Ad, MU.Soyad
    FROM Odeme O
    JOIN Oturum OT ON OT.Oturum_ID = O.Oturum_ID
    JOIN Masa M ON M.Masa_ID = OT.Masa_ID
    LEFT JOIN Musteri MU ON MU.Musteri_ID = OT.Musteri_ID
    $kosul
    ORDER BY O.Odeme_ID ASC
    LIMIT $baslangic, $limit
");
?>

<!-- Sayfaya özel CSS dosyası ekleniyor -->
<link rel="stylesheet" href="../css/odemeler.css">

<h2 class="baslik-odeme">💳 Ödeme Kayıtları</h2>

<!-- 🔍 Tarihe göre filtreleme formu -->
<form method="get" class="filtre-formu">
    <label>Başlangıç Tarihi:</label>
    <input type="date" name="baslangic_tarih" value="<?php echo $baslangic_tarih; ?>">

    <label>Bitiş Tarihi:</label>
    <input type="date" name="bitis_tarih" value="<?php echo $bitis_tarih; ?>">

    <button type="submit" class="btn btn-filtrele">Filtrele</button>
    <a href="odemeler.php" class="btn btn-sifirla">Tümünü Göster</a>
</form>

<!-- 📋 Ödeme kayıtlarının listelendiği tablo -->
<table class="odeme-tablosu">
    <tr>
        <th>ID</th>
        <th>Masa</th>
        <th>Müşteri</th>
        <th>Tarih</th>
        <th>Tutar (₺)</th>
        <th>Ödeme Türü</th>
        <th>İşlem</th>
    </tr>
    <?php while ($odeme = mysqli_fetch_assoc($odemeler)) { ?>
    <tr>
        <!-- Ödeme bilgileri tabloya yazdırılıyor -->
        <td><?php echo $odeme['Odeme_ID']; ?></td>
        <td><?php echo $odeme['Masa_No']; ?></td>
        <td>
            <?php 
            // Eğer müşteri varsa ad soyad yazılır, yoksa "Bilinmiyor"
            if ($odeme['Ad']) {
                echo $odeme['Ad'] . ' ' . $odeme['Soyad'];
            } else {
                echo "Bilinmiyor";
            }
            ?>
        </td>
        <td><?php echo $odeme['Odeme_Tarihi']; ?></td>
        <td><?php echo number_format($odeme['Odeme_Tutari'], 2); ?> ₺</td>
        <td><?php echo $odeme['Odeme_Turu']; ?></td>
        <td>
            <!-- Ödeme düzenleme ve silme butonları -->
            <a href="odeme_duzenle.php?id=<?php echo $odeme['Odeme_ID']; ?>" class="btn btn-duzenle">Düzenle</a>
            <a href="odeme_sil.php?id=<?php echo $odeme['Odeme_ID']; ?>" class="btn btn-sil" onclick="return confirm('Bu ödemeyi silmek istediğine emin misiniz?')">Sil</a>
        </td>
    </tr>
    <?php } ?>
</table>

<!-- 📄 Sayfalama bağlantıları -->
<div class="sayfalama">
    <?php for ($i = 1; $i <= $toplam_sayfa; $i++): ?>
        <a class="<?php echo ($i == $sayfa) ? 'aktif' : ''; ?>" 
           href="?sayfa=<?php echo $i; ?>&baslangic_tarih=<?php echo $baslangic_tarih; ?>&bitis_tarih=<?php echo $bitis_tarih; ?>">
           <?php echo $i; ?>
        </a>
    <?php endfor; ?>
</div>

<!-- Sayfa sonu: footer dosyası dahil ediliyor -->
<?php include("../includes/footer.php"); ?>
