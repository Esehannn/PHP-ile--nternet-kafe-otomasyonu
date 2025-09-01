<?php
// Veritabanı bağlantısı dahil edilir
include("../includes/config.php");

// Form POST ile gönderildiyse çalışır
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formdan gelen hizmet adı ve ücreti alınır
    $adi = $_POST['hizmet_adi'];
    $ucret = $_POST['ucret'];

    /*
      Yeni hizmet, Hizmet tablosuna eklenir.
      Örnek: 'Çay', 5.00 ₺ gibi.
    */
    mysqli_query($conn, "
        INSERT INTO Hizmet (Hizmet_Adi, Hizmet_Ucreti)
        VALUES ('$adi', '$ucret')
    ");

    // Ekleme işlemi bittikten sonra kullanıcı hizmetler listesine yönlendirilir
    header("Location: hizmetler.php");
    exit;
}
?>
