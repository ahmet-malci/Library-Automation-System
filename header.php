<?php
require_once 'baglan.php';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kütüphane Otomasyonu</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    

    <link rel="stylesheet" href="/kutuphane/style.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    
    
    <a class="navbar-brand" href="/kutuphane/index.php">
        <i class="fas fa-book"></i> Kütüphane
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#anaMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="anaMenu">
      <ul class="navbar-nav ml-auto">

        <li class="nav-item">
            <a class="nav-link" href="/kutuphane/index.php">Ana Sayfa</a>
        </li>

        <?php if(isset($_SESSION['kullanici_id'])): ?>

            <li class="nav-item">
                <a class="nav-link" href="/kutuphane/emanetlerim.php">Emanetlerim</a>
            </li>

            <?php if($_SESSION['rol'] == 'yönetici'): ?>
                <li class="nav-item">
                    <a class="nav-link text-warning" href="/kutuphane/admin/kitapekle.php">+ Kitap Ekle</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-warning" href="/kutuphane/admin/kullanicilari_yonet.php">Kullanıcıları Yönet</a>
                </li>
            <?php endif; ?>


        <li class="nav-item dropdown">
            <div>
                <a class="nav-link dropdown-toggle text-white" href="#" id="userMenu" data-toggle="dropdown">
                 Merhaba, <?php echo $_SESSION['kullanici_adi']; ?>
                </a>

            <div class="dropdown-menu dropdown-menu-right bg-info border-0 shadow">
            <a class="dropdown-item text-white" href="/kutuphane/profil.php">Profilim</a>
            <a class="dropdown-item text-white" href="/kutuphane/cikis.php">Çıkış Yap</a>
            </div>
        </li>


        <?php else: ?>

            <li class="nav-item">
                <a class="nav-link" href="/kutuphane/login.php">
                    <i class="fas fa-sign-in-alt"></i> Giriş Yap
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link btn btn-primary btn-sm text-white ml-2" href="/kutuphane/kayit.php">
                    Kayıt Ol
                </a>
            </li>

        <?php endif; ?>

      </ul>
    </div>

  </div>
</nav>

<div class="container">
