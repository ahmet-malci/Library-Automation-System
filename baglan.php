<?php
$host = "localhost";
$kullanici = "root";
$sifre = "";
$veritabani = "kutuphane_db";

try {
    $db = new PDO("mysql:host=$host;dbname=$veritabani;charset=utf8mb4", $kullanici, $sifre);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

if(session_status() === PHP_SESSION_NONE){
    session_start();
}
?>
