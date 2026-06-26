<?php
require_once 'baglan.php';
session_start();

if(!isset($_SESSION['kullanici_id'])) {
    header("Location: login.php");
    exit;
}

$kullanici_id = $_SESSION['kullanici_id'];
$kitap_id = $_POST['kitap_id'];
$yorum = $_POST['yorum'];

$sorgu = $db->prepare("INSERT INTO yorumlar (kullanici_id, kitap_id, yorum, tarih) VALUES (?, ?, ?, NOW())");
$sorgu->execute([$kullanici_id, $kitap_id, $yorum]);

header("Location: detay.php?id=".$kitap_id);
exit;
