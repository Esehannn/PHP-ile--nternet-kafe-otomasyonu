<?php
// Veritabanı bağlantısı dahil edilir
include("../includes/config.php");

// Eğer URL'de hizmet ID'si gönderildiyse silme işlemi yapılır
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Belirtilen ID'ye sahip hizmet veritabanından silinir
    mysqli_query($conn, "DELETE FROM Hizmet WHERE Hizmet_ID = $id");
}

// Silme işlemi bittikten sonra hizmetler sayfasına yönlendirme yapılır
header("Location: hizmetler.php");
exit;
?>