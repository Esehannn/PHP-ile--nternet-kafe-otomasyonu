<?php
include("../includes/config.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Önce bağlı hizmetleri sil
    mysqli_query($conn, "DELETE FROM Oturum_Hizmet WHERE Oturum_ID = $id");

    // Sonra bağlı ödemeleri sil
    mysqli_query($conn, "DELETE FROM Odeme WHERE Oturum_ID = $id");

    // En son oturumu sil
    $sil = mysqli_query($conn, "DELETE FROM Oturum WHERE Oturum_ID = $id");

    if ($sil) {
        echo "<script>alert('Oturum silindi!'); window.location.href='oturumlar.php';</script>";
    } else {
        echo "<script>alert('Silinemedi: " . mysqli_error($conn) . "'); window.location.href='oturumlar.php';</script>";
    }
} else {
    echo "<script>alert('Geçersiz istek!'); window.location.href='oturumlar.php';</script>";
}

?>
