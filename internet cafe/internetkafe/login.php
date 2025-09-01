<?php
// Veritabanı bağlantısını dahil ediyoruz
include("includes/config.php");

// Oturum yönetimi başlatılır (kullanıcı giriş durumunu tutmak için)
session_start();

// Form POST ile gönderildiyse çalışır
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $sifre = $_POST['sifre'];

    /*
      Sorgu:
      - Personel ve görev tablosu birleştirilir
      - E-posta ve şifre eşleşen kullanıcı kontrol edilir
      - Sadece "Yönetici" olanların giriş yapmasına izin verilir
    */
    $sql = "SELECT * FROM Personel P 
            JOIN Gorev G ON G.Gorev_ID = P.Gorev_ID
            WHERE P.E_Posta='$email' AND P.Sifre='$sifre' AND G.Gorev_Tanimi='Yönetici'";

    $result = mysqli_query($conn, $sql);

    // Eğer 1 sonuç dönerse, giriş başarılıdır
    if (mysqli_num_rows($result) == 1) {
        $personel = mysqli_fetch_assoc($result);

        // Kullanıcı bilgileri oturuma kaydedilir
        $_SESSION['user'] = $personel['Ad'] . " " . $personel['Soyad'];
        $_SESSION['gorev'] = $personel['Gorev_Tanimi'];

        // Dashboard sayfasına yönlendirilir
        header("Location: pages/dashboard.php");
        exit();
    } else {
        // Giriş başarısızsa hata mesajı gösterilir
        $hata = "E-posta veya şifreyi yanlış girdiniz!";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Personel Girişi</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-container">
        <h2>🔐 Personel Girişi</h2>

        <!-- Eğer hata varsa ekrana yazdırılır -->
        <?php if (isset($hata)) { echo "<p class='hata'>$hata</p>"; } ?>

        <!-- Giriş formu -->
        <form method="post">
            <input type="email" name="email" placeholder="E-Posta" required>
            <input type="password" name="sifre" placeholder="Şifre" required>
            <button type="submit">Giriş Yap</button>
        </form>
    </div>
</body>
</html>
