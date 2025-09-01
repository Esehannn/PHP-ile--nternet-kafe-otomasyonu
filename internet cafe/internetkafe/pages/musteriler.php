<?php
// Veritabanı bağlantı dosyasını ve üst menüyü dahil eder
include("../includes/config.php");
include("../includes/header.php");


// 1) MÜŞTERİ EKLEME İŞLEMİ

if (isset($_POST['ekle'])) {
    // Formdan gelen veriler alınır
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];
    $telefon = $_POST['telefon'];
    $eposta = $_POST['eposta'];
    $kayit_tarihi = date('Y-m-d'); // Sistemden bugünün tarihi alınır

    // Yeni müşteri veritabanına eklenir
    $sql = "INSERT INTO Musteri (Ad, Soyad, Telefon_NO, E_Posta, Kayit_Tarihi) 
            VALUES ('$ad', '$soyad', '$telefon', '$eposta', '$kayit_tarihi')";
    
    if (mysqli_query($conn, $sql)) {
        echo "<p style='color:green;'>Müşteri eklendi!</p>";
    } else {
        echo "<p style='color:red;'>Hata: " . mysqli_error($conn) . "</p>";
    }
}


// 2) MÜŞTERİ SİLME İŞLEMİ

if (isset($_GET['sil'])) {
    $sil_id = $_GET['sil'];

    // Silinecek müşterinin oturumlardaki bağlantısı kaldırılır (boş bırakılır)
    mysqli_query($conn, "UPDATE Oturum SET Musteri_ID = NULL WHERE Musteri_ID = $sil_id");

    // Müşteri tablosundan kayıt tamamen silinir
    $sql = "DELETE FROM Musteri WHERE Musteri_ID = $sil_id";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Müşteri başarıyla silindi.'); window.location.href='musteriler.php';</script>";
        exit();
    } else {
        echo "<script>alert('Hata: " . mysqli_error($conn) . "');</script>";
    }
}


// 3) ARAMA ve FİLTRELEME

$ara = $_GET['ara'] ?? ''; // Ad-Soyad arama kutusu
$tur = $_GET['tur'] ?? ''; // Müşteri türü: normal / misafir
$kosul = []; // SQL koşulları dizisi

// Arama yapılmışsa koşula eklenir
if (!empty($ara)) {
    $kosul[] = "(Ad LIKE '%$ara%' OR Soyad LIKE '%$ara%')";
}

// Tür filtresi yapılmışsa koşula eklenir
if ($tur === "misafir") {
    $kosul[] = "Ad = 'Misafir'";
} elseif ($tur === "normal") {
    $kosul[] = "Ad != 'Misafir'";
}

// Eğer koşul varsa WHERE ile birleştirilir
$where = "";
if (!empty($kosul)) {
    $where = "WHERE " . implode(" AND ", $kosul);
}


// 4) SAYFALAMA İŞLEMİ

$limit = 10; // Sayfa başına 10 kayıt gösterilecek
$sayfa = isset($_GET['sayfa']) ? (int)$_GET['sayfa'] : 1;
$baslangic = ($sayfa - 1) * $limit;

// Toplam müşteri sayısı hesaplanır
$toplam_kayit = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as toplam FROM Musteri $where
"))['toplam'];

$toplam_sayfa = ceil($toplam_kayit / $limit); // Sayfa sayısı

// İlgili sayfanın müşteri kayıtları çekilir
$musteriler = mysqli_query($conn, "
    SELECT * FROM Musteri $where 
    ORDER BY Musteri_ID ASC 
    LIMIT $baslangic, $limit
");
?>

<!-- CSS dosyası -->
<link rel="stylesheet" href="../css/musteri.css">

<!-- Başlık -->
<h3 class="musteri-baslik">Müşteri Listesi</h3>

<!-- Arama ve Filtre Formu -->
<form method="get" class="musteri-filtre-form">
    <input type="text" name="ara" placeholder="Ad / Soyad ara..." value="<?php echo $ara; ?>" class="filtre-input">
    
    <select name="tur" class="filtre-select">
        <option value="">Tümü</option>
        <option value="normal" <?php if ($tur === 'normal') echo 'selected'; ?>>Normal</option>
        <option value="misafir" <?php if ($tur === 'misafir') echo 'selected'; ?>>Misafir</option>
    </select>

    <button type="submit" class="btn">Filtrele</button>
    <a href="musteriler.php" class="btn btn-sifirla">Sıfırla</a>
</form>

<!-- Müşteri Tablosu -->
<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Ad</th>
        <th>Soyad</th>
        <th>Telefon</th>
        <th>E-Posta</th>
        <th>Kayıt Tarihi</th>
        <th>İşlemler</th>
    </tr>

    <!-- Müşteri kayıtları listelenir -->
    <?php while ($musteri = mysqli_fetch_assoc($musteriler)) { ?>
    <tr>
        <td><?php echo $musteri['Musteri_ID']; ?></td>
        <td><?php echo $musteri['Ad']; ?></td>
        <td><?php echo $musteri['Soyad']; ?></td>
        <td><?php echo $musteri['Telefon_NO']; ?></td>
        <td><?php echo $musteri['E_Posta']; ?></td>
        <td><?php echo $musteri['Kayit_Tarihi']; ?></td>
        <td>
            <!-- Butonlar: geçmiş, düzenle, sil -->
            <a href="musteri_gecmis.php?id=<?php echo $musteri['Musteri_ID']; ?>" class="btn btn-gecmis">Geçmiş</a>
            <a href="musteri_duzenle.php?id=<?php echo $musteri['Musteri_ID']; ?>" class="btn btn-duzenle">Düzenle</a>
            <a href="musteriler.php?sil=<?= $musteri['Musteri_ID']; ?>" 
               onclick="return confirm('Bu müşteriyi silmek istediğinize emin misiniz?')" 
               class="btn btn-sil">Sil</a>
        </td>
    </tr>
    <?php } ?>
</table>

<!-- Sayfalama Linkleri -->
<div class="sayfalama">
    <?php for ($i = 1; $i <= $toplam_sayfa; $i++): ?>
        <a href="?sayfa=<?php echo $i; ?>&ara=<?php echo $ara; ?>&tur=<?php echo $tur; ?>"
           class="<?php echo ($i == $sayfa) ? 'aktif' : ''; ?>">
           <?php echo $i; ?>
        </a>
    <?php endfor; ?>
</div>

<!-- Yeni Müşteri Ekleme Formu -->
<hr>
<h3 class="musteri-baslik">Yeni Müşteri Ekle</h3>
<form method="post" class="musteri-ekle-form">
    <input type="text" name="ad" placeholder="Ad" required><br>
    <input type="text" name="soyad" placeholder="Soyad" required><br>
    <input type="text" name="telefon" placeholder="Telefon"><br>
    <input type="email" name="eposta" placeholder="E-Posta"><br>
    <button type="submit" name="ekle" class="btn">Ekle</button>
</form>

<!-- Sayfa sonu -->
<?php include("../includes/footer.php"); ?>
