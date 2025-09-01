<?php
// Veritabanı bağlantısı dahil edilir
include("../includes/config.php");

// Form gönderildiyse işlem başlatılır
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kullanıcının girdiği masa numarası alınır
    $masa_no = $_POST['masa_no'];

    // 1. Adım: Masa tablosuna yeni masa kaydı eklenir
    $masa_ekle = mysqli_query($conn, "INSERT INTO Masa (Masa_No) VALUES ('$masa_no')");

    if ($masa_ekle) {
        // Yeni eklenen masanın otomatik üretilen ID'si alınır
        $masa_id = mysqli_insert_id($conn);

        // 2. Adım: Bu masa için varsayılan bir oturum oluşturulur (başlangıçta 'Kapalı')
        $oturum_ekle = mysqli_query($conn, "
            INSERT INTO Oturum (Masa_ID, Durum)
            VALUES ($masa_id, 'Kapalı')
        ");

        // Her iki işlem başarılıysa kullanıcıya bilgi verilir ve masalar sayfasına yönlendirilir
        if ($oturum_ekle) {
            echo "<script>alert('Masa ve oturum başarıyla eklendi!'); window.location.href='masalar.php';</script>";
        } else {
            // Oturum eklenemezse hata gösterilir
            echo "<p style='color:red;'>Oturum eklenemedi: " . mysqli_error($conn) . "</p>";
        }
    } else {
        // Masa eklenemezse hata gösterilir
        echo "<p style='color:red;'>Masa eklenemedi: " . mysqli_error($conn) . "</p>";
    }
}
?>
