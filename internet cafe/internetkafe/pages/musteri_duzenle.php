<?php
// Veritabanı bağlantısını ve üst yapıyı dahil et
include("../includes/config.php");
include("../includes/header.php");

// Eğer URL'de müşteri ID'si yoksa işlem yapılamaz
if (!isset($_GET['id'])) {
    echo "<p style='color:red;'>Geçersiz müşteri ID.</p>";
    exit;
}

$musteri_id = $_GET['id'];

// Belirtilen ID'ye sahip müşterinin bilgileri veritabanından çekilir
$sorgu = mysqli_query($conn, "SELECT * FROM Musteri WHERE Musteri_ID = $musteri_id");
$musteri = mysqli_fetch_assoc($sorgu);

// Eğer müşteri bulunamazsa hata mesajı gösterilir
if (!$musteri) {
    echo "<p style='color:red;'>Müşteri bulunamadı.</p>";
    exit;
}

// Form gönderildiyse (Güncelle butonuna basıldıysa) çalışacak bölüm
if (isset($_POST['guncelle'])) {
    // Formdan gelen veriler alınır
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];
    $telefon = $_POST['telefon'];
    $eposta = $_POST['eposta'];

    // Müşteri bilgilerini güncelleyen SQL sorgusu
    $sql = "UPDATE Musteri SET 
            Ad = '$ad',
            Soyad = '$soyad',
            Telefon_NO = '$telefon',
            E_Posta = '$eposta'
            WHERE Musteri_ID = $musteri_id";

    // Sorgu çalıştırılır ve başarılıysa kullanıcıya bilgi verilir
    if (mysqli_query($conn, $sql)) {
        echo "<p style='color:green;'>Müşteri bilgileri güncellendi!</p>";
        echo "<meta http-equiv='refresh' content='1; url=musteriler.php'>"; // 1 saniye sonra yönlendirme
    } else {
        // Hata oluşursa detaylı hata mesajı yazdırılır
        echo "<p style='color:red;'>Hata: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!-- Müşteri bilgilerini düzenlemek için form -->
<h3>Müşteri Bilgilerini Güncelle</h3>

<form method="post">
    <input type="text" name="ad" value="<?php echo $musteri['Ad']; ?>" placeholder="Ad" required><br>
    <input type="text" name="soyad" value="<?php echo $musteri['Soyad']; ?>" placeholder="Soyad" required><br>
    <input type="text" name="telefon" value="<?php echo $musteri['Telefon_NO']; ?>" placeholder="Telefon"><br>
    <input type="email" name="eposta" value="<?php echo $musteri['E_Posta']; ?>" placeholder="E-Posta"><br>
    <button type="submit" name="guncelle">Güncelle</button>
</form>

<?php include("../includes/footer.php"); ?>
