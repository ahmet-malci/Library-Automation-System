<?php require_once 'header.php'; ?>

<?php
if(isset($_POST['kayit_ol'])){
    $kullanici_adi = trim($_POST['kullanici_adi']);
    $email = trim($_POST['email']);
    $sifre = $_POST['sifre'];

   
    $kontrol = $db->prepare("SELECT * FROM kullanicilar WHERE email = ?");
    $kontrol->execute([$email]);

    if($kontrol->rowCount() > 0){
        $hata = "Bu e-posta zaten kayıtlı!";
    } else {
        $sifre_hash = password_hash($sifre, PASSWORD_DEFAULT);
        $ekle = $db->prepare("INSERT INTO kullanicilar (kullanici_adi,email,sifre) VALUES (?,?,?)");
        $islem = $ekle->execute([$kullanici_adi,$email,$sifre_hash]);

        if($islem){
            echo "<script>alert('Kayıt başarılı! Giriş yapabilirsiniz.'); window.location.href='login.php';</script>";
        } else {
            $hata = "Bir hata oluştu, tekrar deneyin.";
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow mt-4">
            <div class="card-header bg-primary text-white">
                <h4>Kayıt Ol</h4>
            </div>
            <div class="card-body">
                <?php if(isset($hata)): ?>
                    <div class="alert alert-danger"><?php echo $hata; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label>Ad Soyad</label>
                        <input type="text" name="kullanici_adi" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>E-Posta</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Şifre</label>
                        <input type="password" name="sifre" class="form-control" required>
                    </div>
                    <button type="submit" name="kayit_ol" class="btn btn-primary btn-block">Kayıt Ol</button>
                </form>
            </div>
            <div class="card-footer text-center">
                Zaten hesabınız var mı? <a href="login.php">Giriş Yap</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
