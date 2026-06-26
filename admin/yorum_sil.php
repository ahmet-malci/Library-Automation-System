<?php
require_once '../baglan.php'; 


if (!isset($_GET['id']) || !isset($_GET['kitap_id'])) {
    die("Geçersiz istek.");
}

$yorum_id = $_GET['id'];
$kitap_id = $_GET['kitap_id'];


if (!isset($_SESSION['kullanici_id'])) {
    die("Bu işlem için giriş yapmanız gerekiyor.");
}

$kullanici_id = $_SESSION['kullanici_id'];
$rol = $_SESSION['rol'] ?? '';

$yorumSor = $db->prepare("SELECT * FROM yorumlar WHERE yorum_id = ?");
$yorumSor->execute([$yorum_id]);
$yorum = $yorumSor->fetch(PDO::FETCH_ASSOC);

if (!$yorum) {
    die("Yorum bulunamadı.");
}


if ($yorum['kullanici_id'] != $kullanici_id && $rol != 'yönetici') {
    die("Bu yorumu silme yetkiniz yok.");
}

$sil = $db->prepare("DELETE FROM yorumlar WHERE yorum_id = ?");
$sil->execute([$yorum_id]);


header("Location: ../detay.php?id=" . $kitap_id);
exit;
?>
