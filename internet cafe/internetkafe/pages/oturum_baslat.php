<?php
include("../includes/config.php"); // Veritabanı bağlantısını dahil et
date_default_timezone_set("Europe/Istanbul"); // Zaman farklarını önlemek için saat dilimi ayarlanıyor

// Formdan POST ile veri geldiyse devam et
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formdan gelen masa ID ve müşteri türü bilgilerini al
    $masa_id = $_POST['masa_id'] ?? null;
    $musteri_turu = $_POST['musteri_turu'] ?? null;

    // Eğer eksik bilgi varsa işlem yapmadan çık
    if (!$masa_id || !$musteri_turu) {
        echo "Eksik veri.";
        exit();
    }

    // Aynı masada daha önce başlatılmış ve açık olan bir oturum olup olmadığını kontrol et
    $kontrol = mysqli_query($conn, "SELECT * FROM Oturum WHERE Masa_ID = $masa_id AND Durum = 'Açık'");
    if (mysqli_num_rows($kontrol) > 0) {
        // Zaten açık bir oturum varsa kullanıcıya uyarı ver ve yönlendir
        echo "<script>alert('Bu masada zaten açık bir oturum var.'); window.location.href='masalar.php';</script>";
        exit;
    }

    // Eğer müşteri türü kayıtlıysa ve müşteri ID geldiyse onu al
    if ($musteri_turu == "kayitli" && !empty($_POST['musteri_id'])) {
        $musteri_id = $_POST['musteri_id'];
    } else {
        // Misafir müşteriyse yeni müşteri kaydı oluştur
        $tarih = date("Ymd_His"); // Özgünleştirmek için tarih saat stringi
        $ad = "Misafir";
        $soyad = "Müşteri_" . $tarih; // Soyad kısmı farklılaştırılır

        // Misafir müşteri veritabanına eklenir
        mysqli_query($conn, "INSERT INTO Musteri (Ad, Soyad, Kayit_Tarihi) VALUES ('$ad', '$soyad', CURDATE())");
        $musteri_id = mysqli_insert_id($conn); // Yeni müşteri ID alınır
    }

    // Oturum başlangıç tarihi
    $baslangic = date("Y-m-d H:i:s");

    // Oturum oluşturulur
    $ekle = mysqli_query($conn, "
        INSERT INTO Oturum (Masa_ID, Musteri_ID, Baslangic_Zamani, Durum)
        VALUES ($masa_id, '$musteri_id', '$baslangic', 'Açık')
    ");

if ($ekle) {
    // sadece başarılıysa yönlendir
    header("Location: masalar.php");
    exit();
}
else {
        // Oturum başlatılamazsa hata mesajı ver
        echo "Oturum başlatılamadı: " . mysqli_error($conn);
    }
} else {
    // POST dışındaki istekleri kabul etmez
    echo "Geçersiz istek yöntemi.";
}
?>
