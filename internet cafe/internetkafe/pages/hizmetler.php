<?php
// Veritabanı bağlantısı ve üst menü dahil edilir
include("../includes/config.php");
include("../includes/header.php");

// Hizmetler tablosundaki veriler alfabetik olarak çekilir
$hizmetler = mysqli_query($conn, "SELECT * FROM Hizmet ORDER BY Hizmet_Adi ASC");
?>

<!-- Sayfaya özel stil dosyası -->
<link rel="stylesheet" href="../css/hizmetler.css">

<!-- Sayfa başlığı -->
<h2 style="text-align:center;">Hizmet Listesi</h2>

<!-- Hizmetlerin listelendiği tablo -->
<table border="1" cellpadding="10" style="margin:auto;">
    <tr style="background-color:#f2f2f2;">
        <th>ID</th>
        <th>Ad</th>
        <th>Ücret (₺)</th>
        <th>İşlem</th>
    </tr>

    <!-- Her hizmet için satır oluşturulur -->
    <?php while ($h = mysqli_fetch_assoc($hizmetler)) { ?>
    <tr>
        <td><?php echo $h['Hizmet_ID']; ?></td>
        <td><?php echo $h['Hizmet_Adi']; ?></td>
        <td><?php echo number_format($h['Hizmet_Ucreti'], 2); ?></td>
        <td>
            <!-- Hizmeti düzenleme ve silme bağlantıları -->
            <a href="hizmet_guncelle.php?id=<?php echo $h['Hizmet_ID']; ?>">Düzenle</a> |
            <a href="hizmet_kaldir.php?id=<?php echo $h['Hizmet_ID']; ?>" onclick="return confirm('Silmek istediğine emin misin?')">Sil</a>
        </td>
    </tr>
    <?php } ?>
</table>

<hr>

<!-- Yeni hizmet ekleme formu -->
<h3 class="ekle-baslik">➕ Yeni Hizmet Ekle</h3>

<div class="ekle-kapsayici">
    <form method="post" action="hizmet_yeni.php" class="ekle-formu">
        <label for="hizmet_adi">Hizmet Adı:</label>
        <!-- Hizmet adı inputu -->
        <input type="text" name="hizmet_adi" id="hizmet_adi" placeholder="Örn: Çay" required>

        <label for="ucret">Ücreti (₺):</label>
        <!-- Hizmet ücreti inputu -->
        <input type="number" step="0.01" name="ucret" id="ucret" placeholder="Örn: 5.00" required>

        <!-- Form gönderme butonu -->
        <button type="submit" class="btn-ekle">💾 Hizmet Ekle</button>
    </form>
</div>

<!-- Sayfa altlığı -->
<?php include("../includes/footer.php"); ?>
