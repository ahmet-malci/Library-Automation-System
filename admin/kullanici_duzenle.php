<?php
require_once '../baglan.php';

if (!isset($_GET['id'])) {
    header("Location: /kutuphane/admin/kullanicilari_yonet.php");
    exit;
}

$kullanici_id = intval($_GET['id']);


$sorgu = $db->prepare("SELECT * FROM kullanicilar WHERE kullanici_id = ?");
$sorgu->execute([$kullanici_id]);
$kullanici = $sorgu->fetch(PDO::FETCH_ASSOC);

if (!$kullanici) {
    die("Kullanıcı bulunamadı!");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $ad = $_POST['kullanici_adi'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];

    $update = $db->prepare("UPDATE kullanicilar SET kullanici_adi=?, email=?, rol=? WHERE kullanici_id=?");
    $sonuc = $update->execute([$ad, $email, $rol, $kullanici_id]);

    if ($sonuc) {
        header("Location: /kutuphane/admin/kullanicilari_yonet.php?durum=ok");
        exit;
    } else {
        $hata = "Güncelleme başarısız oldu!";
    }
}

?>

<?php include '../header.php'; ?>

<div class="container mt-4">
    <h3 class="mb-4">Kullanıcı Düzenle</h3>

    <?php if (isset($hata)): ?>
        <div class="alert alert-danger"><?= $hata ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Kullanıcı Adı</label>
            <input type="text" name="kullanici_adi" class="form-control" required
                   value="<?= htmlspecialchars($kullanici['kullanici_adi']) ?>">
        </div>

        <div class="form-group">
            <label>E-mail</label>
            <input type="email" name="email" class="form-control" required
                   value="<?= htmlspecialchars($kullanici['email']) ?>">
        </div>

        <div class="form-group">
            <label>Rol</label>
            <select name="rol" class="form-control">
                <option value="kullanici" <?= $kullanici['rol']=='kullanici'?'selected':'' ?>>Kullanıcı</option>
                <option value="yönetici" <?= $kullanici['rol']=='yönetici'?'selected':'' ?>>Yönetici</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Kaydet</button>
        <a href="/kutuphane/admin/kullanicilari_yonet.php" class="btn btn-secondary">Geri Dön</a>
    </form>
</div>

<?php include '../footer.php'; ?>
