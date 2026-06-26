<?php
require_once 'baglan.php';

if(!isset($_SESSION['kullanici_id'])){
    header("Location: login.php");
    exit;
}

if(isset($_GET['id']) && isset($_GET['kitap_id'])){
    $odunc_id = $_GET['id'];
    $kitap_id = $_GET['kitap_id'];

 
    $db->prepare("UPDATE odunc SET iade_edildi=1, iade_tarihi=? WHERE odunc_id=?")
       ->execute([date('Y-m-d'), $odunc_id]);


    $db->prepare("UPDATE kitaplar SET adet = adet + 1 WHERE kitap_id=?")->execute([$kitap_id]);

    $kitap = $db->prepare("SELECT adet FROM kitaplar WHERE kitap_id=?");
    $kitap->execute([$kitap_id]);
    $kitap = $kitap->fetch(PDO::FETCH_ASSOC);

    if($kitap['adet'] > 0){
        $db->prepare("UPDATE kitaplar SET durum='müsait' WHERE kitap_id=?")->execute([$kitap_id]);
    }

    header("Location: emanetlerim.php");
    exit;
}
?>
