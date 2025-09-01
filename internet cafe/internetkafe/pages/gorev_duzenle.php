<?php
// Veritabanı bağlantısı ve üst menü dahil ediliyor
include("../includes/config.php");
include("../includes/header.php");

// URL'de 'id' parametresi yoksa hata verip çık
if (!isset($_GET['id'])) {
    echo "<p style='color:red;'>Geçersiz görev ID.</p>";
    exit;
}

$gorev_id = $_GET['id'];

// Görev bilgisi veritabanından çekiliyor
$sorgu = mysqli_query($conn, "SELECT * FROM Gorev WHERE Gorev_ID = $gorev_id");
$gorev = mysqli_fetch_assoc($sorgu);

// Eğer böyle bir görev yoksa, işlem durduruluyor
if (!$gorev) {
    echo "<p style='color:red;'>Görev bulunamadı.</p>";
    exit;
}

// Form gönderildiyse, güncelleme işlemi başlatılır
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gorev_tanimi = $_POST['gorev_tanimi'];

    // Görev adı veritabanında güncellenir
    $guncelle = mysqli_query($conn, "
        UPDATE Gorev SET Gorev_Tanimi = '$gorev_tanimi'
        WHERE Gorev_ID = $gorev_id
    ");

    // Güncelleme başarılıysa kullanıcıya bilgi verilir ve yönlendirme yapılır
    if ($guncelle) {
        echo "<p style='color:green;'>Görev başarıyla güncellendi!</p>";
        echo "<meta http-equiv='refresh' content='1;url=personeller.php'>";
    } else {
        // Hata varsa detaylı hata mesajı yazdırılır
        echo "<p style='color:red;'>Hata: " . mysqli_error($conn) . "</p>";
    }
}
?>

<link rel="stylesheet" href="../css/personel.css">


<h2 style="text-align:center;">📝 Görev Düzenle</h2>

<form method="post" class="personel-form" style="max-width:400px; margin:auto;">
    <input type="text" name="gorev_tanimi" value="<?= $gorev['Gorev_Tanimi']; ?>" required>
    <button type="submit" class="btn btn-duzenle">Güncelle</button>
</form>

<?php include("../includes/footer.php"); ?>
