<?php require_once '../header.php'; 


if(!isset($_SESSION['kullanici_id']) || $_SESSION['rol'] != 'yönetici'){
    die("<div class='alert alert-danger'>Bu sayfayı görüntüleme yetkiniz yok.</div>");
}

if(isset($_POST['kitap_kaydet'])){
    $kitap_adi = trim($_POST['kitap_adi']);
    $yazar = trim($_POST['yazar']);
    $kategori_id = $_POST['kategori_id'];
    $ozet = trim($_POST['ozet']);
    $resim = trim($_POST['resim']);
    $adet = intval($_POST['adet']); 

    $ekle = $db->prepare("INSERT INTO kitaplar (kategori_id, kitap_adi, yazar, ozet, resim, adet) VALUES (?,?,?,?,?,?)");
    $islem = $ekle->execute([$kategori_id,$kitap_adi,$yazar,$ozet,$resim,$adet]);

    if($islem){
        echo "<div class='alert alert-success'>Kitap başarıyla eklendi!</div>";
    } else {
        echo "<div class='alert alert-danger'>Hata oluştu!</div>";
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow mt-4">
            <div class="card-header bg-warning">
                <h4>Yeni Kitap Ekle</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Kitap Adı</label>
                            <input type="text" name="kitap_adi" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Yazar</label>
                            <input type="text" name="yazar" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="kategori_id" class="form-control">
                            <?php
                            $kategoriler = $db->query("SELECT * FROM kategoriler")->fetchAll(PDO::FETCH_ASSOC);
                            foreach($kategoriler as $kat){
                                echo "<option value='".$kat['kategori_id']."'>".$kat['kategori_adi']."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Adet</label>
                            <input type="number" name="adet" class="form-control" value="1" min="1" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Resim URL</label>
                            <input type="text" name="resim" class="form-control" value="https://via.placeholder.com/150">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Özet</label>
                        <textarea name="ozet" class="form-control" rows="4"></textarea>
                    </div>

                    <button type="submit" name="kitap_kaydet" class="btn btn-warning btn-block">Kaydet</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../footer.php'; ?>
