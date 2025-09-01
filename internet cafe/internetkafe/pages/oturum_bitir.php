<?php
// Veritabanı bağlantısını dahil et
include("../includes/config.php");

// Sadece POST isteğiyle gelen talepleri işler
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formdan gelen oturum ID ve ödeme türünü al
    $oturum_id = $_POST['oturum_id'];
    $odeme_turu = $_POST['odeme_turu'];

    // Veritabanından, henüz kapatılmamış (Durum = 'Açık') oturum bilgisini çek
    $oturum = mysqli_fetch_assoc(
        mysqli_query($conn, "
            SELECT * 
            FROM Oturum 
            WHERE Oturum_ID = $oturum_id 
              AND Durum = 'Açık'
        ")
    );

    // Saatlik ücreti Ayarlar tablosundan çek
$ucret_sorgu = mysqli_query($conn, "SELECT saatlik_ucret FROM Ayarlar LIMIT 1");
$ucret_row = mysqli_fetch_assoc($ucret_sorgu);
$saatlik_ucret = $ucret_row['saatlik_ucret'] ?? 50;

    // Eğer geçerli bir açık oturum bulunursa
    if ($oturum) {
        // Oturum başlangıç zamanı ile şu an arasındaki farkı hesapla
        $baslangic = new DateTime($oturum['Baslangic_Zamani']);
        $bitis     = new DateTime();
        $fark      = $baslangic->diff($bitis);
        $dakika    = ($fark->h * 60) + $fark->i;
        $tutar = ceil($dakika / 60) * $saatlik_ucret;

        // Oturuma eklenen hizmetlerin toplam ücretini hesapla
        $hizmet_sorgu = mysqli_query($conn, "
            SELECT H.Hizmet_Ucreti, OH.Adet
            FROM Oturum_Hizmet OH
            JOIN Hizmet H ON H.Hizmet_ID = OH.Hizmet_ID
            WHERE OH.Oturum_ID = $oturum_id
        ");
        $hizmet_toplam = 0;
        while ($h = mysqli_fetch_assoc($hizmet_sorgu)) {
            $hizmet_toplam += $h['Hizmet_Ucreti'] * $h['Adet'];
        }

        // Toplam ödeme = süre ücreti + hizmet ücreti
        $toplam = $tutar + $hizmet_toplam;

        // Oturumu kapat: bitiş zamanını şimdi olarak güncelle ve Durum = 'Kapalı' yap
        mysqli_query($conn, "
            UPDATE Oturum 
            SET 
                Bitis_Zamani = NOW(),
                Durum = 'Kapalı'
            WHERE Oturum_ID = $oturum_id
        ");

        // Hesaplanan toplam tutarı ve seçilen ödeme türünü kaydet
        mysqli_query($conn, "
            INSERT INTO Odeme 
                (Oturum_ID, Odeme_Tarihi, Odeme_Tutari, Odeme_Turu)
            VALUES 
                ($oturum_id, NOW(), $toplam, '$odeme_turu')
        ");

        // İşlem tamamlandıktan sonra kullanıcıyı bilgilendir ve masalar sayfasına döndür
        echo "<script>alert('Oturum kapatıldı.'); window.location.href='masalar.php';</script>";
    } else {
        // Eğer oturum geçersizse veya zaten kapalıysa hata ver
        echo "Geçersiz veya kapalı oturum.";
    }
} else {
    // POST dışındaki istekleri reddet
    echo "Geçersiz istek.";
}
?>
