<?php
// Veritabanı bağlantısını ve ortak header'ı dahil et
include("../includes/config.php");
include("../includes/header.php");

// URL'den gelecek Personel_ID kontrolü
if (!isset($_GET['id'])) {
    echo "<p style='color:red;'>Geçersiz ID.</p>";
    exit;
}
$id = $_GET['id'];

// İlgili personel kaydını veritabanından al
$sorgu = mysqli_query($conn, "SELECT * FROM Personel WHERE Personel_ID = $id");
$personel = mysqli_fetch_assoc($sorgu);

// Eğer personel bulunamazsa hata mesajı göster
if (!$personel) {
    echo "<p style='color:red;'>Personel bulunamadı.</p>";
    exit;
}

// Form post edildiğinde güncelleme işlemini yap
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formdan gelen değerleri al
    $ad       = $_POST['ad'];
    $soyad    = $_POST['soyad'];
    $telefon  = $_POST['telefon'];
    $eposta   = $_POST['eposta']  ?? null;
    $sifre    = $_POST['sifre']   ?? null;
    $saat     = $_POST['saat'];
    $gorev_id = $_POST['gorev_id'];

    // Eğer görev "Yönetici" değilse e-posta ve şifre alanlarını temizle
    if ($gorev_id != 1) {
        $eposta = '';
        $sifre  = '';
    }

    // Güncelleme sorgusunu oluştur
    $sql = "UPDATE Personel SET
                Ad               = '$ad',
                Soyad            = '$soyad',
                Telefon_No       = '$telefon',
                E_Posta          = '$eposta',
                Sifre            = '$sifre',
                Calisma_Saatleri = '$saat',
                Gorev_ID         = '$gorev_id'
            WHERE Personel_ID = $id";

    // Sorguyu çalıştır ve sonucu kontrol et
    if (mysqli_query($conn, $sql)) {
        // Başarılıysa bilgi mesajı ve yönlendirme
        echo "<p style='color:green;'>Personel güncellendi!</p>";
        echo "<meta http-equiv='refresh' content='1;url=personeller.php'>";
    } else {
        // Hata varsa ekrana yazdır
        echo "<p style='color:red;'>Hata: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!-- Sayfaya özel stil dosyasını ekle -->
<link rel="stylesheet" href="../css/personel.css">

<h2 style="text-align:center;">🔧 Personel Düzenle</h2>

<!-- Personel düzenleme formu -->
<form method="post" class="personel-form">
    <!-- Personelin mevcut bilgilerini önceden doldur -->
    <input type="text" name="ad"    value="<?= $personel['Ad']; ?>"               placeholder="Ad"            required>
    <input type="text" name="soyad" value="<?= $personel['Soyad']; ?>"            placeholder="Soyad"         required>
    <input type="text" name="telefon" value="<?= $personel['Telefon_No']; ?>"     placeholder="Telefon"       required>
    <input type="email" name="eposta" value="<?= $personel['E_Posta']; ?>"        placeholder="E-Posta">
    <input type="text" name="sifre"   value="<?= $personel['Sifre']; ?>"          placeholder="Şifre">
    <input type="text" name="saat"    value="<?= $personel['Calisma_Saatleri']; ?>" placeholder="Çalışma Saatleri" required>

    <!-- Görev seçimi: mevcut görev seçili olarak geliyor -->
    <select name="gorev_id" required>
        <option value="">-- Görev Seçin --</option>
        <?php
        // Tüm görevleri çek ve döngü ile seçenek olarak ekle
        $gorevler = mysqli_query($conn, "SELECT * FROM Gorev");
        while ($g = mysqli_fetch_assoc($gorevler)) {
            // Mevcut personelin görev ID'si ile eşleşene 'selected' ekle
            $selected = ($personel['Gorev_ID'] == $g['Gorev_ID']) ? "selected" : "";
            echo "<option value='{$g['Gorev_ID']}' $selected>{$g['Gorev_Tanimi']}</option>";
        }
        ?>
    </select>

    <!-- Formu gönderme butonu -->
    <button type="submit" class="btn btn-ekle">Güncelle</button>
</form>

<?php include("../includes/footer.php"); ?>
