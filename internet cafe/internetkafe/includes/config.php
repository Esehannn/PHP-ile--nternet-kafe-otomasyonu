<?php
// Veritabanı bağlantı bilgileri
$host = "localhost";      // Sunucu adresi (genellikle localhost)
$user = "root";           // Veritabanı kullanıcı adı
$pass = "";               // Veritabanı şifresi (varsayılan olarak boş)
$db   = "internet_kafe";  // Kullanılacak veritabanı adı
$port = 3307;             // MySQL portun

// MySQL veritabanına bağlantı kurulur
$conn = mysqli_connect($host, $user, $pass, $db, $port);

// Eğer bağlantı başarısızsa hata mesajı gösterilir ve işlem durdurulur
if (!$conn) {
    die("Bağlantı hatası: " . mysqli_connect_error());
}
?>
