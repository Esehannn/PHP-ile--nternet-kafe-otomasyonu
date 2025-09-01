<?php
// Veritabanı bağlantısını dahil et
include("../includes/config.php");

// Sadece POST isteğiyle gelen talepleri işler
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formdan gelen personel bilgilerini al
    $ad       = $_POST['ad'];
    $soyad    = $_POST['soyad'];
    $telefon  = $_POST['telefon'];
    $eposta   = $_POST['eposta']  ?? null;
    $sifre    = $_POST['sifre']   ?? null;
    $saat     = $_POST['saat'];
    $gorev_id = $_POST['gorev_id'];

    // Yönetici (Gorev_ID = 1) değilse e-posta ve şifre zorunlu değil, boş bırak
    if ($gorev_id != 1) {
        $eposta = '';
        $sifre  = '';
    }

    // Yeni personel kaydı eklemek için SQL sorgusu
    $sql = "
        INSERT INTO Personel
            (Ad, Soyad, Telefon_No, E_Posta, Sifre, Calisma_Saatleri, Gorev_ID)
        VALUES
            ('$ad', '$soyad', '$telefon', '$eposta', '$sifre', '$saat', '$gorev_id')
    ";

    // Sorguyu çalıştır ve sonucu kontrol et
    if (mysqli_query($conn, $sql)) {
        // Başarılı eklemeden sonra personeller listesine yönlendir
        header("Location: personeller.php");
        exit();
    } else {
        // Hata oluşursa mesaj göster
        echo "Hata: " . mysqli_error($conn);
    }
} else {
    // POST dışındaki istekleri kabul etmez
    echo "Geçersiz istek.";
}
