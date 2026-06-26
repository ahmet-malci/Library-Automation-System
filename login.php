<?php require_once 'header.php'; ?>

<?php
if(isset($_POST['giris_yap'])){
    $email = trim($_POST['email']);
    $sifre = $_POST['sifre'];

    $sorgu = $db->prepare("SELECT * FROM kullanicilar WHERE email = ?");
    $sorgu->execute([$email]);
    $kullanici = $sorgu->fetch(PDO::FETCH_ASSOC);

    if($kullanici && password_verify($sifre,$kullanici['sifre'])){
        $_SESSION['kullanici_id'] = $kullanici['kullanici_id'];
        $_SESSION['kullanici_adi'] = $kullanici['kullanici_adi'];
        $_SESSION['rol'] = $kullanici['rol'];

        header("Location: index.php");
        exit;
    } else {
        $hata = "E-posta veya şifre hatalı!";
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow mt-5">
            <div class="card-header bg-success text-white">
                <h4>Giriş Yap</h4>
            </div>
            <div class="card-body">
                <?php if(isset($hata)): ?>
                    <div class="alert alert-danger"><?php echo $hata; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label>E-Posta</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Şifre</label>
                        <input type="password" name="sifre" class="form-control" required>
                    </div>
                    <button type="submit" name="giris_yap" class="btn btn-success btn-block">Giriş Yap</button>
                </form>
            </div>
            <div class="card-footer text-center">
                Hesabınız yok mu? <a href="kayit.php">Kayıt Ol</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
