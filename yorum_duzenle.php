<?php
require_once 'header.php';


if(!isset($_GET['id'])) {
    echo "<div class='alert alert-danger text-center'>Yorum bulunamadı.</div>";
    require_once 'footer.php';
    exit;
}

$yorum_id = $_GET['id'];


$sorgu = $db->prepare("SELECT * FROM yorumlar WHERE yorum_id = ?");
$sorgu->execute([$yorum_id]);
$yorum = $sorgu->fetch(PDO::FETCH_ASSOC);

if(!$yorum) {
    echo "<div class='alert alert-danger text-center'>Yorum bulunamadı.</div>";
    require_once 'footer.php';
    exit;
}


if(!isset($_SESSION['kullanici_id']) || $_SESSION['kullanici_id'] != $yorum['kullanici_id']) {
    echo "<div class='alert alert-danger text-center'>Bu yorumu düzenleme yetkiniz yok.</div>";
    require_once 'footer.php';
    exit;
}


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $yeni_yorum = trim($_POST['yorum']);
    if(!empty($yeni_yorum)) {
        $guncelle = $db->prepare("UPDATE yorumlar SET yorum = ?, tarih = NOW() WHERE yorum_id = ?");
        $guncelle->execute([$yeni_yorum, $yorum_id]);
        
        header("Location: detay.php?id=" . $yorum['kitap_id']);
        exit;
    } else {
        $hata = "Yorum boş olamaz.";
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">

        <div class="card shadow mt-4">
            <div class="card-header bg-warning text-white">
                <h4 class="mb-0">Yorum Düzenle</h4>
            </div>
            <div class="card-body">

                <?php if(isset($hata)): ?>
                    <div class="alert alert-danger"><?php echo $hata; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <textarea class="form-control" name="yorum" rows="5" required><?php echo htmlspecialchars($yorum['yorum']); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-success">Güncelle</button>
                    <a href="detay.php?id=<?php echo $yorum['kitap_id']; ?>" class="btn btn-secondary">İptal</a>
                </form>

            </div>
        </div>

    </div>
</div>

<?php require_once 'footer.php'; ?>
