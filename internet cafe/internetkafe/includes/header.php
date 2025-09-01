<?php
// Oturum başlatılır (kullanıcı bilgilerini kontrol etmek için)
session_start();

// Eğer kullanıcı oturum açmamışsa login sayfasına yönlendirilir
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>İnternet Kafe Yönetim Paneli</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/header.css">
    <?php
    // Sayfaya özel CSS varsa yükle
    $page = basename($_SERVER['PHP_SELF']);
    if ($page === "dashboard.php") {
        echo '<link rel="stylesheet" href="../dashboard.css">';
    }
    ?>
</head>
<body>

<header class="navbar">
    <div class="navbar-top">
        <h2>☕ İnternet Kafe Yönetimi</h2>
        <p>👋 Hoş Geldin <strong><?php echo $_SESSION['user']; ?></strong> (<?php echo $_SESSION['gorev']; ?>)</p>
    </div>

    <nav class="navbar-links">
        <a href="../pages/dashboard.php">🏠 Anasayfa</a>
        <a href="../pages/musteriler.php">👤 Müşteriler</a>
        <a href="../pages/masalar.php">🪑 Masalar</a>
        <a href="../pages/personeller.php">💻 Personel Yönetimi</a>
        <a href="../pages/oturumlar.php">💻 Oturumlar</a>
        <a href="../pages/odemeler.php">💸 Ödemeler</a>
        <a href="../pages/hizmetler.php">🍽️ Hizmetler</a>
        <a href="../logout.php" class="logout">🚪 Çıkış</a>
    </nav>
</header>

<hr>
