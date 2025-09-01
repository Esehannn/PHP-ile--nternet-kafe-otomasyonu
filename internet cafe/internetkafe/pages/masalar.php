<?php
include("../includes/config.php"); // Veritabanı bağlantısını içerir
include("../includes/header.php"); // Ortak header yapısını dahil eder

// Mevcut tüm masaları veritabanından çeker
$masalar = mysqli_query($conn, "SELECT * FROM Masa");
?>

<!-- Stil dosyası yükleniyor -->
<link rel="stylesheet" href="../css/masalar.css">

<!-- Masa ekleme formu -->
<div class="form-container">
    <form action="masa_ekle.php" method="post" class="masa-form">
        <label for="masa_no"><strong>Masa No:</strong></label>
        <input type="text" name="masa_no" id="masa_no" placeholder="örn: Masa 6" required>
        <button type="submit" class="btn">➕ Ekle</button>
    </form>

    <!-- Masa silme formu -->
    <form action="masa_sil.php" method="post" class="masa-form" onsubmit="return confirm('Bu masayı silmek istediğinize emin misiniz? Bu masaya ait oturum bilgileri de silinecektir.');">
        <label for="masa_id"><strong>Masa Sil:</strong></label>
        <select name="masa_id" id="masa_id" required>
            <option value="">-- Masa Seçin --</option>
            <?php
            // Masa listesini formda göstermek için çeker
            $masa_listesi = mysqli_query($conn, "SELECT * FROM Masa ORDER BY Masa_No ASC");
            while ($m = mysqli_fetch_assoc($masa_listesi)) {
                echo "<option value='{$m['Masa_ID']}'>{$m['Masa_No']}</option>";
            }
            ?>
        </select>
        <button type="submit" class="btn">🗑️ Sil</button>
    </form>
</div>

<hr>

<h2 style="text-align:center;">Masalar</h2>

<div class="masa-grid">
<?php
// Veritabanından gelen tüm masalar üzerinde döner
while ($masa = mysqli_fetch_assoc($masalar)) {
    $masa_id = $masa['Masa_ID'];
    $masa_no = $masa['Masa_No'];

    // Bu masaya ait açık bir oturum var mı kontrol edilir
    $oturum_sorgu = mysqli_query($conn, "SELECT * FROM Oturum WHERE Masa_ID = $masa_id AND Durum = 'Açık' LIMIT 1");
    $oturum = mysqli_fetch_assoc($oturum_sorgu);

    // Masa kutusu başlatılır
    echo "<div class='masa-kutu'>";
    echo "<h3>$masa_no</h3>";

    $ucret_sorgu = mysqli_query($conn, "SELECT saatlik_ucret FROM Ayarlar LIMIT 1");
$ucret_row = mysqli_fetch_assoc($ucret_sorgu);
$saatlik_ucret = $ucret_row['saatlik_ucret'] ?? 50;

    if ($oturum) {
        // Eğer açık bir oturum varsa, süre ve ücret hesaplanır
        $oturum_id = $oturum['Oturum_ID'];
        $baslangic = new DateTime($oturum['Baslangic_Zamani']);
        $simdi = new DateTime();
        $fark = $baslangic->diff($simdi); // Şu an ile başlangıç zamanı arasındaki fark
        $dakika = ($fark->h * 60) + $fark->i; // Toplam geçen dakika
        $tutar = ceil($dakika / 60) * $saatlik_ucret; // Her saat 50₺, yukarı yuvarlanarak hesaplanır

        // Müşteri adı soyadı çekilir, yoksa "Bilinmiyor" yazılır
        $musteri_ad = "Bilinmiyor";
        if (!empty($oturum['Musteri_ID'])) {
            $mid = $oturum['Musteri_ID'];
            $musteri_sorgu = mysqli_query($conn, "SELECT Ad, Soyad FROM Musteri WHERE Musteri_ID = $mid");
            if ($m = mysqli_fetch_assoc($musteri_sorgu)) {
                $musteri_ad = $m['Ad'] . " " . $m['Soyad'];
            }
        }

        // Oturuma eklenen hizmetlerin toplam tutarı hesaplanır
        $hizmet_toplam = 0;
        $hizmetler = mysqli_query($conn, "
            SELECT H.Hizmet_Ucreti, OH.Adet
            FROM Oturum_Hizmet OH
            JOIN Hizmet H ON H.Hizmet_ID = OH.Hizmet_ID
            WHERE OH.Oturum_ID = $oturum_id
        ");
        while ($h = mysqli_fetch_assoc($hizmetler)) {
            $hizmet_toplam += $h['Hizmet_Ucreti'] * $h['Adet'];
        }

        // Toplam tutar = süre ücreti + hizmet ücreti
        $toplam_tutar = $tutar + $hizmet_toplam;

        // Oturum bilgileri ve işlemleri gösterilir
        echo "
        <p style='color:red;'><strong>Oturum Açık</strong></p>
        <p><strong>Müşteri:</strong> $musteri_ad</p>
        <p>Başlangıç: {$oturum['Baslangic_Zamani']}</p>
        <p>Süre: <span id='sure_$masa_id' data-baslangic='{$oturum['Baslangic_Zamani']}'></span></p>
        <p>Ücret (Süre): $tutar ₺</p>
        <p>Ücret (Hizmet): $hizmet_toplam ₺</p>
        <p><strong>Toplam: $toplam_tutar ₺</strong></p>

        <!-- Hizmet ekleme butonu ve formu -->
        <button class='btn' onclick='gosterHizmetForm($masa_id)'>Hizmet Ekle</button>
        <div id='hizmet_form_$masa_id' class='form-kutu' style='display:none;'>
            <form action='hizmet_ekle.php' method='post'>
                <input type='hidden' name='oturum_id' value='$oturum_id'>
                <label>Hizmet:</label>
                <select name='hizmet_id' required>";

        // Tüm hizmetler liste olarak getirilir
        $hizmetler = mysqli_query($conn, "SELECT * FROM Hizmet ORDER BY Hizmet_Adi ASC");
        while ($h = mysqli_fetch_assoc($hizmetler)) {
            echo "<option value='{$h['Hizmet_ID']}'>{$h['Hizmet_Adi']} - {$h['Hizmet_Ucreti']} ₺</option>";
        }

        // Adet ve ekle butonu
        echo "</select>
                <label>Adet:</label>
                <input type='number' name='adet' min='1' value='1' required>
                <button type='submit' class='btn'>➕ Ekle</button>
            </form>
        </div>

        <!-- Oturumu bitirme (ödeme alma) butonu ve formu -->
        <button class='btn' onclick='gosterOdemeForm($masa_id)'>Oturumu Bitir</button>
        <div id='odeme_form_$masa_id' class='form-kutu' style='display:none;'>
            <form action='oturum_bitir.php' method='post'>
                <input type='hidden' name='oturum_id' value='$oturum_id'>
                <label><input type='radio' name='odeme_turu' value='Nakit' checked> Nakit</label>
                <label><input type='radio' name='odeme_turu' value='Kredi Kartı'> Kredi Kartı</label>
                <button type='submit' class='btn'>✅ Kaydet ve Bitir</button>
            </form>
        </div>";
    } else {
        // Eğer açık oturum yoksa (masa boşsa), oturum başlatma seçeneği sunulur
        echo "<p style='color:green;'><strong>Boş</strong></p>
              <button class='btn' onclick='gosterForm($masa_id)'>Oturumu Başlat</button>
              <div id='form_$masa_id' class='form-kutu' style='display:none;'>
                  <form action='oturum_baslat.php' method='post'>
                      <input type='hidden' name='masa_id' value='$masa_id'>
                      
                      <!-- Müşteri türü seçimi: Kayıtlı veya Misafir -->
                      <label><input type='radio' name='musteri_turu' value='kayitli' checked onclick='toggleSelect($masa_id, true)'> Kayıtlı</label>
                      <label><input type='radio' name='musteri_turu' value='misafir' onclick='toggleSelect($masa_id, false)'> Misafir</label>

                      <!-- Kayıtlı müşteri arama kutusu -->
                      <input type='text' onkeyup='filtreleMusteri($masa_id)' id='arama_$masa_id' placeholder='Müşteri ara...'>
                      
                      <!-- Kayıtlı müşteri seçimi -->
                      <div id='select_$masa_id'>
                          <select name='musteri_id'>
                              <option value=''>-- Müşteri Seç --</option>";

        // Müşteri listesini çek
        $musteriler = mysqli_query($conn, "SELECT * FROM Musteri WHERE Ad != 'Misafir' ORDER BY Ad ASC");
        while ($m = mysqli_fetch_assoc($musteriler)) {
            echo "<option value='{$m['Musteri_ID']}'>{$m['Ad']} {$m['Soyad']}</option>";
        }

        // Oturum başlatma butonu
        echo "</select>
                      </div>
                      <button type='submit' class='btn'>Başlat</button>
                  </form>
              </div>";
    }

    // Masa kutusunu kapat
    echo "</div>";
}
?>

</div>

<!-- JavaScript: Formları açma/kapatma, sayaç -->
<script>

// Oturum başlatma formunu aç/kapat
function gosterForm(masaID) {
    // İlgili formu ID'sine göre bul
    const form = document.getElementById("form_" + masaID);
    // Eğer form kapalıysa aç, açıksa kapa
    form.style.display = (form.style.display === "none") ? "block" : "none";
}

// Hizmet ekleme formunu aç/kapat
function gosterHizmetForm(masaID) {
    const form = document.getElementById("hizmet_form_" + masaID);
    form.style.display = (form.style.display === "none") ? "block" : "none";
}

// Oturumu bitirme (ödeme) formunu aç/kapat
function gosterOdemeForm(masaID) {
    const form = document.getElementById("odeme_form_" + masaID);
    form.style.display = (form.style.display === "none") ? "block" : "none";
}

// Müşteri türüne göre (kayıtlı/misafir) müşteri seçim kutusunu göster/gizle
function toggleSelect(masaID, goster) {
    const selectDiv = document.getElementById("select_" + masaID); // Müşteri seçim alanı
    const selectBox = selectDiv.querySelector("select"); // Select (dropdown) kutusu

    if (goster) {
        // Kayıtlı müşteri seçildiyse seçim kutusunu göster
        selectDiv.style.display = "block";
        selectBox.setAttribute("required", "required");
    } else {
        // Misafir seçildiyse seçim kutusunu gizle
        selectDiv.style.display = "none";
        selectBox.removeAttribute("required");
        selectBox.value = ""; // Seçimi temizle
    }
}

// Müşteri arama kutusu: yazdıkça select kutusunu filtreler
function filtreleMusteri(masaID) {
    const input = document.getElementById("arama_" + masaID); // Arama inputu
    const filtre = input.value.toLowerCase(); // Küçük harfe çevir
    const select = document.querySelector("#select_" + masaID + " select");
    const options = select.options;

    // Müşteri adında yazılan kelime geçenleri göster, diğerlerini gizle
    for (let i = 0; i < options.length; i++) {
        const text = options[i].text.toLowerCase();
        options[i].style.display = (text.includes(filtre) || options[i].value === "") ? "" : "none";
    }
}

// Her masa için sayaç başlat: süreyi dakika-saniye olarak göster
function baslatSayac(masaID) {
    const span = document.getElementById("sure_" + masaID);
    const baslangicZaman = new Date(span.getAttribute("data-baslangic")).getTime();

    // Süreyi her saniye güncelle
    function guncelle() {
        const simdi = new Date().getTime();
        const fark = simdi - baslangicZaman;

        const saniyeToplam = Math.floor(fark / 1000); // Geçen toplam saniye
        const dakika = Math.floor(saniyeToplam / 60);
        const saniye = saniyeToplam % 60;

        // Ekranda dakika:saniye şeklinde göster
        span.innerText = `${dakika} dakika ${saniye.toString().padStart(2, '0')} saniye`;
    }

    guncelle(); // İlk yüklemede çağır
    setInterval(guncelle, 1000); // 1 saniyede bir süreyi güncelle
}

// Sayfa yüklendiğinde aktif oturumlar için sayaçları başlat
window.addEventListener("DOMContentLoaded", () => {
    const spanlar = document.querySelectorAll("[id^='sure_']"); // Tüm sayaç span'larını al
    spanlar.forEach(span => {
        const id = span.id.replace("sure_", "");
        baslatSayac(id); // Her masa için sayaç başlat
    });
});

</script>


<?php include("../includes/footer.php"); ?>
