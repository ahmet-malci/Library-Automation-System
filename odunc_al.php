<?php
require_once 'header.php'; 

if(!isset($_SESSION['kullanici_id'])){
    header("Location: login.php");
    exit;
}

if(isset($_GET['id'])){
    $kitap_id = $_GET['id'];
    $kullanici_id = $_SESSION['kullanici_id'];

    
    $kontrolKullanici = $db->prepare("SELECT * FROM odunc WHERE kullanici_id=? AND kitap_id=? AND iade_edildi=0");
    $kontrolKullanici->execute([$kullanici_id, $kitap_id]);
    if($kontrolKullanici->rowCount() > 0){
        echo "<div class='container mt-4'><div class='alert alert-info text-center'>Zaten bu kitaptan ödünç almışsınız.</div></div>";
        require_once 'footer.php';
        exit;
    }

    
    $kontrolKitap = $db->prepare("SELECT durum, adet FROM kitaplar WHERE kitap_id=?");
    $kontrolKitap->execute([$kitap_id]);
    $kitap = $kontrolKitap->fetch(PDO::FETCH_ASSOC);

    if($kitap && $kitap['durum'] == 'müsait' && $kitap['adet'] > 0){
        $tarih = date('Y-m-d');

     
        $db->prepare("INSERT INTO odunc (kullanici_id,kitap_id,alinma_tarihi) VALUES (?,?,?)")
           ->execute([$kullanici_id,$kitap_id,$tarih]);

        
        $db->prepare("UPDATE kitaplar SET adet=adet-1 WHERE kitap_id=?")->execute([$kitap_id]);

        
        if($kitap['adet'] - 1 == 0){
            $db->prepare("UPDATE kitaplar SET durum='ödünçte' WHERE kitap_id=?")->execute([$kitap_id]);
        }

        echo "<div class='container mt-4'><div class='alert alert-success text-center'>Kitap başarıyla ödünç alındı!</div></div>";
        require_once 'footer.php';
        exit;
    } else {
        echo "<div class='container mt-4'><div class='alert alert-warning text-center'>Bu kitap şu anda mevcut değil veya tükenmiş.</div></div>";
        require_once 'footer.php';
        exit;
    }
}
?>
