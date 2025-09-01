<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oturumlar</title>
    <link rel="stylesheet" href="../css/oturumlar.css">
</head>
<body>
<?php
// Gerekli dosyaları sayfaya dahil et: veritabanı bağlantısı ve sayfa üst bilgisi (header).
include("../includes/config.php");
include("../includes/header.php");

// --- FİLTRE DEĞERLERİNİ AL ---
// URL'den gelen 'ad' parametresini al. Eğer yoksa boş string ata. trim() ile başındaki/sonundaki boşlukları temizle.
$filtre_ad = isset($_GET['ad']) ? trim($_GET['ad']) : '';
// URL'den gelen 'masa' parametresini al.
$filtre_masa = isset($_GET['masa']) ? $_GET['masa'] : '';
// URL'den gelen 'baslangic_tarih' parametresini al.
$filtre_baslangic = isset($_GET['baslangic_tarih']) ? $_GET['baslangic_tarih'] : '';
// URL'den gelen 'bitis_tarih' parametresini al.
$filtre_bitis = isset($_GET['bitis_tarih']) ? $_GET['bitis_tarih'] : '';


// --- VERİTABANINDAN VERİ ÇEKME ---
// Filtreleme formundaki 'Masa' seçme kutusunu (select) doldurmak için tüm masaları çek.
$masalar_sorgu = mysqli_query($conn, "SELECT Masa_ID, Masa_No FROM Masa ORDER BY Masa_No ASC");


// --- SAYFALAMA DEĞİŞKENLERİ ---
$kayit_sayisi = 20; // Her sayfada gösterilecek kayıt sayısı.
// URL'den 'sayfa' parametresini al. Eğer yoksa veya geçersizse 1. sayfa olarak kabul et.
$sayfa = isset($_GET['sayfa']) ? max(1, intval($_GET['sayfa'])) : 1;
// Sorgudaki LIMIT başlangıç değerini hesapla. Örn: 1. sayfa için 0, 2. sayfa için 20.
$baslangic = ($sayfa - 1) * $kayit_sayisi;


// --- FİLTRELİ SORGULARI OLUŞTUR ---
$kosullar = []; // SQL sorgusunun WHERE bölümü için koşulları tutacak dizi.

// Eğer 'ad' filtresi doluysa...
if ($filtre_ad) {
    $filtre_ad = trim($filtre_ad); // Tekrar boşlukları temizle.
    $ad_soyad = explode(' ', $filtre_ad, 2); // Gelen değeri boşluktan iki parçaya böl (ad ve soyad).

    // Eğer ad ve soyad olarak iki parça geldiyse...
    if (count($ad_soyad) == 2) {
        // SQL Injection'a karşı güvenli hale getir.
        $ad = mysqli_real_escape_string($conn, $ad_soyad[0]);
        $soyad = mysqli_real_escape_string($conn, $ad_soyad[1]);
        $tam_adsoyad = mysqli_real_escape_string($conn, $filtre_ad);
        // Hem ad ve soyad sütunlarında ayrı ayrı, hem de birleşik olarak arama yap.
        $kosullar[] = "( (M.Ad LIKE '%$ad%' AND M.Soyad LIKE '%$soyad%') OR CONCAT(M.Ad, ' ', M.Soyad) LIKE '%$tam_adsoyad%' )";
    } else { // Eğer tek bir kelime geldiyse...
        $adsoyad = mysqli_real_escape_string($conn, $filtre_ad);
        // Bu kelimeyi hem ad, hem soyad, hem de birleşik ad-soyad içinde ara.
        $kosullar[] = "(M.Ad LIKE '%$adsoyad%' OR M.Soyad LIKE '%$adsoyad%' OR CONCAT(M.Ad, ' ', M.Soyad) LIKE '%$adsoyad%')";
    }
}
// Eğer 'masa' filtresi seçilmişse...
if ($filtre_masa) {
    // Masa ID'sine göre koşul ekle. intval() ile sadece sayısal değer alınmasını sağla.
    $kosullar[] = "Ma.Masa_ID = " . intval($filtre_masa);
}
// Eğer başlangıç tarihi filtresi seçilmişse...
if ($filtre_baslangic) {
    $bas_tarih = mysqli_real_escape_string($conn, $filtre_baslangic);
    // Oturum başlangıç zamanının, seçilen tarihten büyük veya eşit olmasını sağla.
    $kosullar[] = "DATE(O.Baslangic_Zamani) >= '$bas_tarih'";
}
// Eğer bitiş tarihi filtresi seçilmişse...
if ($filtre_bitis) {
    $bit_tarih = mysqli_real_escape_string($conn, $filtre_bitis);
    // Oturum başlangıç zamanının, seçilen tarihten küçük veya eşit olmasını sağla.
    $kosullar[] = "DATE(O.Baslangic_Zamani) <= '$bit_tarih'";
}


// Eğer en az bir koşul varsa, bunları " AND " ile birleştirerek WHERE sorgusunu oluştur.
$kosul_sql = count($kosullar) ? "WHERE " . implode(" AND ", $kosullar) : "";

// Sayfalama linkleri için URL parametrelerini oluştur.
$url_param = "";
if ($filtre_ad) $url_param .= "&ad=" . urlencode($filtre_ad);
if ($filtre_masa) $url_param .= "&masa=" . urlencode($filtre_masa);
if ($filtre_baslangic) $url_param .= "&baslangic_tarih=" . urlencode($filtre_baslangic);
if ($filtre_bitis) $url_param .= "&bitis_tarih=" . urlencode($filtre_bitis);


// --- TOPLAM KAYIT SAYISINI HESAPLA ---
// Filtre koşullarına uyan toplam kayıt sayısını al (sayfalama için gerekli).
$toplam_sorgu = mysqli_query($conn, "
    SELECT COUNT(*) as toplam
    FROM Oturum O
    LEFT JOIN Musteri M ON O.Musteri_ID = M.Musteri_ID
    LEFT JOIN Masa Ma ON O.Masa_ID = Ma.Masa_ID
    $kosul_sql
");
$toplam = mysqli_fetch_assoc($toplam_sorgu)['toplam'];
// Toplam sayfa sayısını hesapla. ceil() ile sonucu yukarı yuvarla.
$toplam_sayfa = ceil($toplam / $kayit_sayisi);


// --- OTURUM VERİLERİNİ ÇEK ---
// Filtrelenmiş ve sayfalanmış oturum verilerini veritabanından çek.
$oturumlar = mysqli_query($conn, "
    SELECT O.*, M.Ad, M.Soyad, Ma.Masa_No
    FROM Oturum O
    LEFT JOIN Musteri M ON O.Musteri_ID = M.Musteri_ID
    LEFT JOIN Masa Ma ON O.Masa_ID = Ma.Masa_ID
    $kosul_sql
    ORDER BY O.Baslangic_Zamani DESC  -- Oturumları en yeniden en eskiye doğru sırala
    LIMIT $baslangic, $kayit_sayisi   -- Sadece mevcut sayfanın kayıtlarını al
");
?>

<div class="oturumlar-container">
    <h2 class="oturumlar-baslik">🕓 Tüm Oturumlar</h2>

    <!-- FİLTRE FORMU -->
    <form method="get" class="oturumlar-filtre-form" style="text-align:center; margin-bottom:30px;">
        <label for="ad">Müşteri Adı:</label>
        <!-- htmlspecialchars() ile kullanıcı girdisini güvenli bir şekilde ekrana yazdır. -->
        <input type="text" name="ad" id="ad" value="<?= htmlspecialchars($filtre_ad) ?>" placeholder="Müşteri adı veya soyadı" style="padding:6px 10px; border-radius:6px; border:1px solid #ccc; margin-right:10px;">
        
        <label for="masa">Masa:</label>
        <select name="masa" id="masa" style="padding:6px 10px; border-radius:6px; border:1px solid #ccc; margin-right:10px;">
            <option value="">Tümü</option>
            <?php // Masaları döngü ile <option> olarak ekle
            while($masa = mysqli_fetch_assoc($masalar_sorgu)): ?>
                <!-- Eğer bu masa mevcut filtredeki masaysa 'selected' olarak işaretle -->
                <option value="<?= $masa['Masa_ID'] ?>" <?= ($filtre_masa == $masa['Masa_ID']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($masa['Masa_No']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="baslangic_tarih">Başlangıç:</label>
        <!-- ?? operatörü ile $_GET['baslangic_tarih'] yoksa hata vermesini engelle, boş string ata. -->
        <input type="date" name="baslangic_tarih" id="baslangic_tarih" value="<?= htmlspecialchars($_GET['baslangic_tarih'] ?? '') ?>" style="padding:6px 10px; border-radius:6px; border:1px solid #ccc; margin-right:10px;">

        <label for="bitis_tarih">Bitiş:</label>
        <input type="date" name="bitis_tarih" id="bitis_tarih" value="<?= htmlspecialchars($_GET['bitis_tarih'] ?? '') ?>" style="padding:6px 10px; border-radius:6px; border:1px solid #ccc; margin-right:10px;">
        
        <button type="submit" class="btn-filtre" style="padding:7px 15px; background:#1976d2; color:#fff; border:none; border-radius:7px; font-weight:500;">Filtrele</button>
        <!-- Tüm filtreleri temizlemek için link -->
        <a href="oturumlar.php" style="margin-left:14px; color:#1976d2; text-decoration:underline;">Tümünü Göster</a>
    </form>

    <!-- OTURUM LİSTESİ TABLOSU -->
    <table class="oturumlar-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Masa</th>
            <th>Müşteri</th>
            <th>Başlangıç</th>
            <th>Bitiş</th>
            <th>Durum</th>
            <th>Toplam Tutar</th>
            <th>Hizmetler</th>
            <th>Ödeme Türü</th>
            <th>İşlem</th>
        </tr>
    </thead>
    <tbody>
<?php
// Çekilen oturum verilerini döngüye alarak tablo satırlarını oluştur.
while($o = mysqli_fetch_assoc($oturumlar)) {

    // --- HER OTURUM İÇİN EK BİLGİLERİ HESAPLA ---

    // O anki oturuma ait tüm ödemelerin toplamını al.
    $odeme_toplam_sorgu = mysqli_query($conn, "SELECT SUM(Odeme_Tutari) as toplam_odeme FROM Odeme WHERE Oturum_ID = {$o['Oturum_ID']}");
    $odeme_toplam_sonuc = mysqli_fetch_assoc($odeme_toplam_sorgu);
    // Eğer hiç ödeme yoksa toplamı 0 olarak ayarla.
    $toplam = $odeme_toplam_sonuc['toplam_odeme'] ?? 0;

    // O anki oturuma ait en son yapılan ödemenin türünü al.
    $odeme = mysqli_fetch_assoc(
        mysqli_query($conn, "SELECT Odeme_Turu FROM Odeme WHERE Oturum_ID = {$o['Oturum_ID']} ORDER BY Odeme_Tarihi DESC LIMIT 1")
    );
    // Eğer hiç ödeme yoksa '-' olarak göster.
    $odeme_turu = $odeme['Odeme_Turu'] ?? '-';

    // O anki oturumda alınan hizmetleri ve adetlerini çek.
    $hizmetler_sorgu = mysqli_query($conn, "
        SELECT H.Hizmet_Adi, OH.Adet
        FROM Oturum_Hizmet OH
        JOIN Hizmet H ON H.Hizmet_ID = OH.Hizmet_ID
        WHERE OH.Oturum_ID = {$o['Oturum_ID']}
    ");
    $hizmetler = []; // Hizmetleri bir diziye topla.
    while ($h = mysqli_fetch_assoc($hizmetler_sorgu)) {
        $hizmetler[] = $h['Hizmet_Adi'] . " (" . $h['Adet'] . ")"; // "Hizmet Adı (Adet)" formatında ekle.
    }
    // Eğer hizmet varsa virgülle ayırarak listele, yoksa '-' göster.
    $hizmet_liste = count($hizmetler) ? implode(", ", $hizmetler) : "-";
    ?>
    <tr>
        <td><?= $o['Oturum_ID'] ?></td>
        <td><?= htmlspecialchars($o['Masa_No']) ?></td>
        <td><?= htmlspecialchars($o['Ad'] . " " . $o['Soyad']) ?></td>
        <td><?= $o['Baslangic_Zamani'] ?></td>
        <td><?= $o['Bitis_Zamani'] ?? '-' ?></td> <!-- Bitiş zamanı null ise '-' göster -->
        <td>
            <!-- Oturum durumuna göre renkli etiket göster -->
            <?php if ($o['Durum'] == "Açık"): ?>
                <span class="oturum-acik">Açık</span>
            <?php else: ?>
                <span class="oturum-kapali">Kapalı</span>
            <?php endif; ?>
        </td>
        <!-- number_format() ile para birimini formatla -->
        <td><?= number_format($toplam, 2) ?> ₺</td>
        <td><?= $hizmet_liste ?></td>
        <td><?= htmlspecialchars($odeme_turu) ?></td>
        <td>
            <!-- Silme işlemi için link ve JavaScript onayı -->
            <a href="oturum_sil.php?id=<?= $o['Oturum_ID'] ?>" 
               onclick="return confirm('Bu oturumu silmek istediğinize emin misiniz?')"
               class="btn-sil">
               Sil
            </a>
        </td>
    </tr>
    <?php
} // while döngüsü sonu
?>
    </tbody>
</table>

    <!-- Sayfalama Linkleri -->
    <div class="oturumlar-sayfalama">
        <?php
        // Sayfa linklerinin sonuna mevcut filtre parametrelerini ekle.
        // Bu, sayfa değiştirildiğinde filtrelerin kaybolmamasını sağlar.
        $url_param_pagination = "";
        if ($filtre_ad) $url_param_pagination .= "&ad=" . urlencode($filtre_ad);
        if ($filtre_masa) $url_param_pagination .= "&masa=" . urlencode($filtre_masa);
        if ($filtre_baslangic) $url_param_pagination .= "&baslangic_tarih=" . urlencode($filtre_baslangic);
        if ($filtre_bitis) $url_param_pagination .= "&bitis_tarih=" . urlencode($filtre_bitis);
        ?>

        <!-- Eğer ilk sayfada değilsek "Önceki" butonunu göster -->
        <?php if ($sayfa > 1): ?>
            <a href="?sayfa=<?= $sayfa-1 . $url_param_pagination ?>" class="sayfa-btn">◀ Önceki</a>
        <?php endif; ?>

        <!-- Tüm sayfa numaralarını döngü ile yazdır -->
        <?php for($i=1; $i<=$toplam_sayfa; $i++): ?>
            <?php if ($i == $sayfa): // Mevcut sayfa ise link verme, aktif olarak işaretle ?>
                <span class="sayfa-btn aktif"><?= $i ?></span>
            <?php else: // Diğer sayfalar için link ver ?>
                <a href="?sayfa=<?= $i . $url_param_pagination ?>" class="sayfa-btn"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <!-- Eğer son sayfada değilsek "Sonraki" butonunu göster -->
        <?php if ($sayfa < $toplam_sayfa): ?>
            <a href="?sayfa=<?= $sayfa+1 . $url_param_pagination ?>" class="sayfa-btn">Sonraki ▶</a>
        <?php endif; ?>
    </div>
</div>

<?php include("../includes/footer.php"); // Sayfa alt bilgisini (footer) dahil et ?>
</body>
</html>
