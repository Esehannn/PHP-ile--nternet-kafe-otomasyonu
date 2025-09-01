<?php
// Veritabanı bağlantısını ve ortak header'ı dahil et
include("../includes/config.php");
include("../includes/header.php");

// Personel ve görev bilgilerini çek: her çalışan için göreviyle birlikte
$personeller = mysqli_query($conn, "
    SELECT P.*, G.Gorev_Tanimi 
    FROM Personel P 
    LEFT JOIN Gorev G ON P.Gorev_ID = G.Gorev_ID
");
?>

<!-- Sayfaya özel stil dosyasını ekle -->
<link rel="stylesheet" href="../css/personel.css">

<!-- Başlık -->
<h2 style="text-align:center;">👥 Personel Listesi</h2>

<!-- Personel tablosu: ID, Ad, Soyad, Görev, iletişim, şifre ve çalışma saatleri -->
<table class="personel-tablosu">
    <tr>
        <th>ID</th>
        <th>Ad</th>
        <th>Soyad</th>
        <th>Görev</th>
        <th>Telefon</th>
        <th>E-Posta</th>
        <th>Şifre</th>
        <th>Çalışma Saatleri</th>
        <th>İşlem</th>
    </tr>
    <?php while ($p = mysqli_fetch_assoc($personeller)) { ?>
    <tr>
        <!-- Her personel kaydı bir satır olarak listelenir -->
        <td><?= $p['Personel_ID']; ?></td>
        <td><?= $p['Ad']; ?></td>
        <td><?= $p['Soyad']; ?></td>
        <td><?= $p['Gorev_Tanimi']; ?></td>
        <td><?= $p['Telefon_No']; ?></td>
        <td><?= $p['E_Posta']; ?></td>
        <td><?= $p['Sifre']; ?></td>
        <td><?= $p['Calisma_Saatleri']; ?></td>
        <td>
            <!-- Düzenle ve Sil işlemleri -->
            <a href="personel_duzenle.php?id=<?= $p['Personel_ID']; ?>" class="btn btn-duzenle">Düzenle</a>
            <a href="personel_sil.php?id=<?= $p['Personel_ID']; ?>" class="btn btn-sil" onclick="return confirm('Silmek istediğine emin misin?')">Sil</a>
        </td>
    </tr>
    <?php } ?>
</table>



<hr>

<!-- Yeni personel ekleme formu -->
<h3 style="text-align:center;">➕ Yeni Personel Ekle</h3>
<form method="post" action="personel_ekle.php" class="personel-form">
    <!-- Temel bilgileri al -->
    <input type="text" name="ad" placeholder="Ad" required>
    <input type="text" name="soyad" placeholder="Soyad" required>
    <input type="text" name="telefon" placeholder="Telefon No" required>
    <!-- Yönetici seçilirse zorunlu; JS ile işlenecek -->
    <input type="email" name="eposta" placeholder="E-Posta">
    <input type="password" name="sifre" placeholder="Şifre">
    <input type="text" name="saat" placeholder="Çalışma Saatleri" required>

    <!-- Görev seçimi: yöneticiler e-posta/şifre girer, diğerleri boş kalır -->
    <select name="gorev_id" required id="gorevSec">
        <option value="">-- Görev Seçin --</option>
        <?php
        // Tüm görevleri çek ve dropdown'a ekle
        $gorevler = mysqli_query($conn, "SELECT * FROM Gorev");
        while ($g = mysqli_fetch_assoc($gorevler)) {
            echo "<option value='{$g['Gorev_ID']}'>{$g['Gorev_Tanimi']}</option>";
        }
        ?>
    </select>

    <button type="submit" class="btn btn-ekle">Kaydet</button>
</form>

<!--
    JS: Görev seçimine göre e-posta ve şifre alanlarını gereklilik/isteğe bağlı hale getirir
    Eğer Yönetici (ID=1) seçilirse inputlar required olur, aksi halde kaldırılır.
-->
<script>
document.getElementById('gorevSec').addEventListener('change', function () {
    const eposta = document.querySelector("input[name='eposta']");
    const sifre = document.querySelector("input[name='sifre']");
    if (this.value === "1") {
        eposta.setAttribute("required", "required");
        sifre.setAttribute("required", "required");
    } else {
        eposta.removeAttribute("required");
        sifre.removeAttribute("required");
    }
});
</script>

<hr>

<!-- Görev listesi: ID ve tanımı ile birlikte düzenle/sil seçenekleri -->
<h3 style="text-align:center;">🧩 Görevler</h3>
<table class="gorev-tablosu">
    <tr>
        <th>ID</th>
        <th>Görev Tanımı</th>
        <th>İşlem</th>
    </tr>
    <?php
    // Tüm görevleri çek ve tabloya ekle
    $gorevler = mysqli_query($conn, "SELECT * FROM Gorev");
    while ($g = mysqli_fetch_assoc($gorevler)) {
        echo "<tr>
                <td>{$g['Gorev_ID']}</td>
                <td>{$g['Gorev_Tanimi']}</td>
                <td>
                    <a href='gorev_duzenle.php?id={$g['Gorev_ID']}' class='btn btn-duzenle'>Düzenle</a>
                    <a href='gorev_sil.php?id={$g['Gorev_ID']}' class='btn btn-sil' onclick=\"return confirm('Görev silinsin mi?')\">Sil</a>
                </td>
              </tr>";
    }
    ?>
</table>

<!-- Yeni görev ekleme formu -->
<form method="post" action="gorev_ekle.php" class="gorev-form">
    <input type="text" name="gorev_tanimi" placeholder="Görev Tanımı" required>
    <button type="submit" class="btn btn-ekle">Görev Ekle</button>
</form>

<?php include("../includes/footer.php"); ?>
