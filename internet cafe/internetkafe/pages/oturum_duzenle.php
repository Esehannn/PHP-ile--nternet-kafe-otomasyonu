<?php
// Veritabanı bağlantısını ve ortak header'ı dahil et
include("../includes/config.php");
include("../includes/header.php");

// URL'den gelen oturum ID'sini kontrol et
if (!isset($_GET['id'])) {
    echo "Geçersiz ID";
    exit;
}
$oturum_id = $_GET['id'];

// İlgili oturum kaydını veritabanından al
$oturum = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT * 
        FROM Oturum 
        WHERE Oturum_ID = $oturum_id
    ")
);

// Eğer oturum bulunamazsa kullanıcıyı bilgilendir ve çık
if (!$oturum) {
    echo "Oturum bulunamadı.";
    exit;
}

// Form gönderildiyse (POST isteği) müşteri güncelleme işlemini yap
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $yeni_musteri = $_POST['musteri_id']; // Yeni müşteri ID'si

    // Oturum kaydını güncelle: yeni müşteri ID'sini ata
    $guncelle = mysqli_query($conn, "
        UPDATE Oturum 
        SET Musteri_ID = '$yeni_musteri' 
        WHERE Oturum_ID = $oturum_id
    ");

    if ($guncelle) {
        // Başarılıysa kullanıcıya bilgi verip oturumlar listesine dön
        echo "<script>
                alert('Müşteri güncellendi.');
                window.location.href='oturumlar.php';
              </script>";
    } else {
        // Hata oluştuysa hata mesajını göster
        echo "<p style='color:red;'>Hata: " . mysqli_error($conn) . "</p>";
    }
}
?>

<h2 style="text-align:center;">
    Oturumu Düzenle (ID: <?php echo $oturum_id; ?>)
</h2>

<!-- 
    Kullanıcının mevcut oturum için yeni müşteri seçebileceği form 
    - Açık oturum ID'si gizli alanda tutulur 
    - Mevcut müşteri seçili olarak gelir
-->
<form method="post" style="max-width:400px; margin:auto; background:#fff; padding:20px; border-radius:10px;">
    <label><strong>Yeni Müşteri Seç:</strong></label><br>
    <select name="musteri_id" required>
        <option value="">-- Seçin --</option>
        <?php
        // Tüm müşterileri isim sırasına göre listele
        $musteriler = mysqli_query($conn, "SELECT * FROM Musteri ORDER BY Ad ASC");
        while ($m = mysqli_fetch_assoc($musteriler)) {
            // Mevcut oturumun müşteri ID'si ile eşleşeni seçili olarak işaretle
            $selected = ($oturum['Musteri_ID'] == $m['Musteri_ID']) ? "selected" : "";
            echo "<option value='{$m['Musteri_ID']}' $selected>
                    {$m['Ad']} {$m['Soyad']}
                  </option>";
        }
        ?>
    </select><br><br>

    <!-- Kaydet ve İptal butonları -->
    <button type="submit" class="btn">Kaydet</button>
    <a href="oturumlar.php" class="btn">İptal</a>
</form>

<?php include("../includes/footer.php"); ?>
