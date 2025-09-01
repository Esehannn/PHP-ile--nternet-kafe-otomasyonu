<?php
// Veritabanı bağlantısı dahil edilir
include("../includes/config.php");

// URL'de görev ID yoksa hata verip personeller sayfasına yönlendir
if (!isset($_GET['id'])) {
    echo "<script>alert('Geçersiz görev ID.'); window.location.href='personeller.php';</script>";
    exit;
}

$gorev_id = $_GET['id'];

// Önce bu görev ile ilişkili personel var mı kontrol edilir
$kontrol = mysqli_query($conn, "SELECT * FROM Personel WHERE Gorev_ID = $gorev_id");

// Eğer görevle ilişkili personel varsa, görev silinemez (veri bütünlüğü korunur)
if (mysqli_num_rows($kontrol) > 0) {
    echo "<script>alert('Bu göreve ait personeller var. Önce bu personellerin görevini değiştirin.'); window.location.href='personeller.php';</script>";
    exit;
}

// Eğer ilişkili personel yoksa, görev silinir
if (mysqli_query($conn, "DELETE FROM Gorev WHERE Gorev_ID = $gorev_id")) {
    echo "<script>alert('Görev silindi.'); window.location.href='personeller.php';</script>";
} else {
    // Silme başarısız olursa hata mesajı gösterilir
    echo "<script>alert('Silme hatası: " . mysqli_error($conn) . "'); window.location.href='personeller.php';</script>";
}
?>
