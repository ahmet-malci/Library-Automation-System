<?php
require_once "../baglan.php";


if (!isset($_GET['id'])) {
    header("Location: /kutuphane/admin/kullanicilari_yonet.php?durum=hata");
    exit;
}

$kullanici_id = intval($_GET['id']);


$sil = $db->prepare("DELETE FROM kullanicilar WHERE kullanici_id = ?");
$sil->execute([$kullanici_id]);
 
header("Location: /kutuphane/admin/kullanicilari_yonet.php?durum=silindi");
exit;
?>
