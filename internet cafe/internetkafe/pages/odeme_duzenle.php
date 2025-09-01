<?php
// Veritabanı bağlantısı ve üst bilgi dosyasını dahil eder
include("../includes/config.php");
include("../includes/header.php");

// GET ile gelen 'id' parametresini kontrol eder
// Eğer ID gelmemişse hata mesajı verilir ve çıkış yapılır
if (!isset($_GET['id'])) {
    echo "<p style='color:red;'>Geçersiz ID.</p>";
    exit();
}

$odeme_id = $_GET['id'];

// ID'ye göre veritabanından ödeme kaydını çeker
$odeme_sorgu = mysqli_query($conn, "
    SELECT * FROM Odeme WHERE Odeme_ID = $odeme_id
");
$odeme = mysqli_fetch_assoc($odeme_sorgu);

// Eğer böyle bir ödeme yoksa kullanıcıya bildirilir
if (!$odeme) {
    echo "<p style='color:red;'>Ödeme bulunamadı.</p>";
    exit();
}

// Eğer form gönderildiyse (POST metodu)
// Formdan gelen veriler alınır ve veritabanında güncelleme yapılır
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tutar = $_POST['tutar'];
    $tarih = $_POST['tarih'];
    $tur = $_POST['tur'];

    // Ödeme bilgilerini güncelleme sorgusu
    $guncelle = mysqli_query($conn, "
        UPDATE Odeme SET 
            Odeme_Tutari = '$tutar',
            Odeme_Tarihi = '$tarih',
            Odeme_Turu = '$tur'
        WHERE Odeme_ID = $odeme_id
    ");

    // Eğer güncelleme başarılıysa kullanıcı bilgilendirilir
    if ($guncelle) {
        echo "<script>alert('Ödeme güncellendi!'); window.location.href='odemeler.php';</script>";
    } else {
        echo "<p style='color:red;'>Güncelleme hatası: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!-- Başlık -->
<h2 style="text-align:center;">Ödeme Düzenle</h2>

<!-- Ödeme düzenleme formu -->
<form method="post" style="max-width:400px; margin:auto; background:#fff; padding:20px; border-radius:8px;">

    <!-- Ödeme tutarı girişi -->
    <label><strong>Tutar (₺):</strong></label><br>
    <input type="number" name="tutar" value="<?php echo $odeme['Odeme_Tutari']; ?>" required><br><br>

    <!-- Ödeme tarihi (datetime-local formatında) -->
    <label><strong>Tarih:</strong></label><br>
    <input type="datetime-local" name="tarih" 
           value="<?php echo date('Y-m-d\TH:i', strtotime($odeme['Odeme_Tarihi'])); ?>" required><br><br>

    <!-- Ödeme türü seçimi -->
    <label><strong>Ödeme Türü:</strong></label><br>
    <select name="tur" required>
        <option value="Nakit" <?php if ($odeme['Odeme_Turu'] == 'Nakit') echo 'selected'; ?>>Nakit</option>
        <option value="Kredi Kartı" <?php if ($odeme['Odeme_Turu'] == 'Kredi Kartı') echo 'selected'; ?>>Kredi Kartı</option>
    </select><br><br>

    <!-- Kaydet ve İptal butonları -->
    <button type="submit" class="btn">Kaydet</button>
    <a href="odemeler.php" class="btn">İptal</a>
</form>

<?php include("../includes/footer.php"); ?>
