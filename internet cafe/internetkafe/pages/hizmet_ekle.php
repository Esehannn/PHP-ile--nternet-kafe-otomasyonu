<?php
// Veritabanı bağlantısı dahil edilir
include("../includes/config.php");

// Eğer form POST ile gönderildiyse çalışır
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formdan gelen veriler alınır
    $oturum_id = $_POST['oturum_id']; // Hizmetin ekleneceği oturum
    $hizmet_id = $_POST['hizmet_id']; // Hangi hizmet seçildi
    $adet = $_POST['adet'];           // Hizmetin adedi

    /*
      Oturum_Hizmet tablosuna yeni hizmet eklenir.
      Bu tablo oturum ve hizmetleri birbirine bağlar (çoktan çok ilişki).
    */
    $ekle = mysqli_query($conn, "
        INSERT INTO Oturum_Hizmet (Oturum_ID, Hizmet_ID, Adet)
        VALUES ('$oturum_id', '$hizmet_id', '$adet')
    ");

    // Ekleme başarılıysa masalar sayfasına yönlendirilir
    if ($ekle) {
        header("Location: masalar.php");
        exit;
    } else {
        // Hata oluşursa kullanıcıya gösterilir
        echo "Hizmet eklenemedi: " . mysqli_error($conn);
    }
}
?>
