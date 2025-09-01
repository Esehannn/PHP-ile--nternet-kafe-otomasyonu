<?php
// Veritabanı bağlantısı dahil edilir
include("../includes/config.php");

// Form gönderildiyse çalışır
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kullanıcıdan gelen görev tanımı alınır ve baştaki/sondaki boşluklar temizlenir
    $gorev_tanimi = trim($_POST['gorev_tanimi']);

    // Görev tanımı boş değilse devam edilir
    if (!empty($gorev_tanimi)) {
        // Görev tanımı veritabanına eklenir
        $ekle = mysqli_query($conn, "INSERT INTO Gorev (Gorev_Tanimi) VALUES ('$gorev_tanimi')");

        // Ekleme başarılıysa personeller sayfasına yönlendirilir
        if ($ekle) {
            header("Location: personeller.php");
            exit();
        } else {
            // Veritabanı hatası olursa yazdırılır
            echo "Görev eklenirken hata oluştu: " . mysqli_error($conn);
        }
    } else {
        // Boş görev tanımı gönderildiyse uyarı verilir
        echo "Lütfen görev tanımını boş bırakmayınız.";
    }
} else {
    // Sayfa POST dışında çalıştırılırsa geçersiz istek olarak kabul edilir
    echo "Geçersiz istek.";
}
?>
