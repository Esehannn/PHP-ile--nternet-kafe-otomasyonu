<?php
// Veritabanı bağlantısı dahil edilir
include("../includes/config.php");

// Sayfa POST isteğiyle çağrıldıysa işlem başlar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formdan gelen silinecek masa ID'si alınır
    $masa_id = $_POST['masa_id'];

    // Belirtilen ID'ye sahip masa veritabanından silinir
    $sil = mysqli_query($conn, "DELETE FROM Masa WHERE Masa_ID = $masa_id");

    if ($sil) {
        // Silme başarılıysa kullanıcıya uyarı verilir ve masalar sayfasına yönlendirilir
        echo "<script>alert('Masa başarıyla silindi.'); window.location.href='masalar.php';</script>";
    } else {
        // Silme başarısızsa hata mesajı gösterilir
        echo "<p style='color:red;'>Masa silinemedi: " . mysqli_error($conn) . "</p>";
    }
}
?>
