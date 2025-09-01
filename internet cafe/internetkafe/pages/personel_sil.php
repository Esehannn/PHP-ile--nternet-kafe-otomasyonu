<?php
// Veritabanı bağlantısını dahil et
include("../includes/config.php");

// URL'den gelen Personel_ID parametresini kontrol et
if (!isset($_GET['id'])) {
    // Geçersiz veya eksik ID varsa uyar ve personeller sayfasına dön
    echo "<script>
            alert('Geçersiz ID');
            window.location.href='personeller.php';
          </script>";
    exit;
}

$id = $_GET['id']; // Silinecek personelin ID'si

// Silme sorgusunu hazırla ve çalıştır
$sql = "DELETE FROM Personel WHERE Personel_ID = $id";
if (mysqli_query($conn, $sql)) {
    // Başarılı silme durumunda kullanıcıyı bilgilendir ve listeye dön
    echo "<script>
            alert('Personel silindi!');
            window.location.href='personeller.php';
          </script>";
} else {
    // Hata oluşursa hata mesajını gösterip listeye dön
    echo "<script>
            alert('Silme hatası: " . mysqli_error($conn) . "');
            window.location.href='personeller.php';
          </script>";
}
