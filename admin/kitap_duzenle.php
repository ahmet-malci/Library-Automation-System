<?php 
require_once '../header.php'; 


if(!isset($_SESSION['kullanici_id']) || $_SESSION['rol'] != 'yönetici') {
    die("<div class='alert alert-danger text-center'>Bu sayfayı görüntüleme yetkiniz yok.</div>");
}


if(!isset($_GET['id'])) {
    die("<div class='alert alert-danger text-center'>Geçersiz kitap ID.</div>");
}

$kitap_id = $_GET['id'];


$sorgu = $db->prepare("SELECT * FROM kitaplar WHERE kitap_id = ?");
$sorgu->execute([$kitap_id]);
$kitap = $sorgu->fetch(PDO::FETCH_ASSOC);

if(!$kitap) {
    die("<div class='alert alert-danger text-center'>Kitap bulunamadı.</div>");
}


$kategoriler = $db->query("SELECT * FROM kategoriler")->fetchAll();


if(isset($_POST['guncelle'])) {
    $kitap_adi = $_POST['kitap_adi'];
    $yazar = $_POST['yazar'];
    $kategori_id = $_POST['kategori_id'];
    $ozet = $_POST['ozet'];
    $resim = $_POST['resim'];
    $adet = intval($_POST['adet']); 

    $guncelle = $db->prepare("UPDATE kitaplar SET kitap_adi=?, yazar=?, kategori_id=?, ozet=?, resim=?, adet=? WHERE kitap_id=?");
    $guncelle->execute([$kitap_adi, $yazar, $kategori_id, $ozet, $resim, $adet, $kitap_id]);

    echo "<div class='alert alert-success'>Kitap başarıyla güncellendi!</div>";

  
    $sorgu->execute([$kitap_id]);
    $kitap = $sorgu->fetch(PDO::FETCH_ASSOC);
}


if(isset($_POST['sil'])) {
    $sil = $db->prepare("DELETE FROM kitaplar WHERE kitap_id=?");
    $sil->execute([$kitap_id]);
    echo "<div class='alert alert-success'>Kitap silindi! <a href='../index.php'>Ana sayfaya dön</a></div>";
    exit;
}
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow mt-4">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0"><i class="fas fa-edit"></i> Kitap Düzenle</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Kitap Adı</label>
                            <input type="text" name="kitap_adi" class="form-control" value="<?php echo htmlspecialchars($kitap['kitap_adi']); ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Yazar</label>
                            <input type="text" name="yazar" class="form-control" value="<?php echo htmlspecialchars($kitap['yazar']); ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Kategori</label>
                            <select name="kategori_id" class="form-control">
                                <?php foreach($kategoriler as $kat): ?>
                                    <option value="<?php echo $kat['kategori_id']; ?>" <?php if($kat['kategori_id']==$kitap['kategori_id']) echo 'selected'; ?>>
                                        <?php echo $kat['kategori_adi']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Adet</label>
                            <input type="number" name="adet" class="form-control" value="<?php echo htmlspecialchars($kitap['adet']); ?>" min="1" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Resim URL</label>
                        <input type="text" name="resim" class="form-control" value="<?php echo htmlspecialchars($kitap['resim']); ?>">
                    </div>

                    <div class="form-group">
                        <label>Özet</label>
                        <textarea name="ozet" class="form-control" rows="4"><?php echo htmlspecialchars($kitap['ozet']); ?></textarea>
                    </div> 

                    <button type="submit" name="guncelle" class="btn btn-warning">Güncelle</button>
                    <button type="submit" name="sil" class="btn btn-danger" onclick="return confirm('Bu kitabı silmek istediğinizden emin misiniz?')">Sil</button>
                    <a href="../index.php" class="btn btn-secondary">İptal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../footer.php'; ?>
