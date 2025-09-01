<?php
// Veritabanı bağlantısını içe aktarır
include("../includes/config.php");

// Eğer URL'den 'id' parametresi geldiyse işlem başlar
if (isset($_GET['id'])) {
    $id = $_GET['id']; // Gelen ID değeri alınır

    // Bu ID'ye sahip ödemeyi silmek için sorgu çalıştırılır
    $sil = mysqli_query($conn, "DELETE FROM Odeme WHERE Odeme_ID = $id");

    // Eğer silme başarılıysa kullanıcıya mesaj gösterilir ve odemeler.php sayfasına yönlendirilir
    if ($sil) {
        echo "<script>alert('Ödeme silindi.'); window.location.href='odemeler.php';</script>";
    } 
    // Eğer silme başarısızsa hata mesajı gösterilir
    else {
        echo "<p style='color:red;'>Silme hatası: " . mysqli_error($conn) . "</p>";
    }
}
?>
