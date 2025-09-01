<?php
// Veritabanı bağlantısı ve üst menü dahil edilir
include("../includes/config.php");
include("../includes/header.php");

// GET ile gelen 'id' bilgisi alınır, yoksa hata verilir
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "ID eksik.";
    exit;
}

// Belirtilen ID'ye sahip hizmetin bilgileri alınır
$hizmet = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM Hizmet WHERE Hizmet_ID = $id"));

// Eğer form POST ile gönderildiyse güncelleme işlemi yapılır
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adi = $_POST['hizmet_adi']; // Yeni hizmet adı
    $ucret = $_POST['ucret'];    // Yeni hizmet ücreti

    // Veritabanında hizmet bilgileri güncellenir
    mysqli_query($conn, "
        UPDATE Hizmet SET 
            Hizmet_Adi = '$adi', 
            Hizmet_Ucreti = '$ucret'
        WHERE Hizmet_ID = $id
    ");

    // Başarılı güncellemeden sonra hizmetler listesine yönlendirilir
    header("Location: hizmetler.php");
    exit;
}
?>

<link rel="stylesheet" href="../css/hizmetler.css">

<h2 class="ekle-baslik">🛠️ Hizmeti Düzenle</h2>

<div class="ekle-kapsayici">
    <form method="post" class="ekle-formu">
        <label for="hizmet_adi">Hizmet Adı:</label>
        <input type="text" name="hizmet_adi" id="hizmet_adi" value="<?php echo $hizmet['Hizmet_Adi']; ?>" required>

        <label for="ucret">Ücreti (₺):</label>
        <input type="number" step="0.01" name="ucret" id="ucret" value="<?php echo $hizmet['Hizmet_Ucreti']; ?>" required>

        <div class="buton-grup">
            <button type="submit" class="btn-ekle">💾 Kaydet</button>
            <a href="hizmetler.php" class="btn-iptal">İptal</a>
        </div>
    </form>
</div>

<?php include("../includes/footer.php"); ?>