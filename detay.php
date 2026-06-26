<?php 
require_once 'header.php';

if(!isset($_GET['id'])) {
    echo "<div class='alert alert-danger text-center'>Kitap bulunamadı.</div>";
    require_once 'footer.php';
    exit;
}

$kitap_id = $_GET['id'];

$sorgu = $db->prepare("
    SELECT kitaplar.*, kategoriler.kategori_adi 
    FROM kitaplar 
    JOIN kategoriler ON kitaplar.kategori_id = kategoriler.kategori_id 
    WHERE kitap_id = ?
");
$sorgu->execute([$kitap_id]);
$kitap = $sorgu->fetch(PDO::FETCH_ASSOC);

if(!$kitap) {
    echo "<div class='alert alert-danger text-center'>Kitap bulunamadı.</div>";
    require_once 'footer.php';
    exit;
}


$yorumSor = $db->prepare("
    SELECT yorumlar.*, kullanicilar.kullanici_adi 
    FROM yorumlar
    LEFT JOIN kullanicilar ON yorumlar.kullanici_id = kullanicilar.kullanici_id
    WHERE yorumlar.kitap_id = ?
    ORDER BY yorumlar.tarih DESC
");
$yorumSor->execute([$kitap_id]);
$yorumlar = $yorumSor->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row justify-content-center">
    <div class="col-md-8">

        
        <div class="card shadow mt-4">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0"><?php echo htmlspecialchars($kitap['kitap_adi']); ?> - Detaylar</h4>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-md-4">
                        <img src="<?php echo htmlspecialchars($kitap['resim']); ?>" class="img-fluid kitap-resim"  alt="Kitap Resmi">
                    </div>

                    <div class="col-md-8">
                        <h5>Yazar: <?php echo htmlspecialchars($kitap['yazar']); ?></h5>

                        <p>Kategori: 
                            <span class="badge badge-primary">
                                <?php echo htmlspecialchars($kitap['kategori_adi']); ?>
                            </span>
                        </p>

                        <p>Durum: 
                            <?php if($kitap['adet'] > 0): ?>
                                <span class="badge badge-success">Müsait (<?php echo $kitap['adet']; ?> adet)</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Tükendi</span>
                            <?php endif; ?>
                        </p>

                        <p>Özet: <?php echo nl2br(htmlspecialchars($kitap['ozet'])); ?></p>

                        <?php if(isset($_SESSION['kullanici_id'])): ?>
                            <?php if($kitap['adet'] > 0): ?>
                                <a href="odunc_al.php?id=<?php echo $kitap['kitap_id']; ?>" class="btn btn-primary">Ödünç Al</a>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>Alınamaz</button>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-secondary">İşlem İçin Giriş Yap</a>
                        <?php endif; ?>

                    </div>

                </div>
            </div>
        </div>

       
        <div class="card shadow mt-4">
            <div class="card-header bg-secondary text-white">
                <h5>Kullanıcı Yorumları</h5>
            </div>
            <div class="card-body">

                <?php if(count($yorumlar) == 0): ?>
                    <p class="text-muted">Bu kitap için henüz yorum yapılmamış.</p>

                <?php else: ?>
                    <?php foreach($yorumlar as $yorum): ?>
                        <div class="border p-3 rounded mb-3">

                            <strong><?php echo htmlspecialchars($yorum['kullanici_adi']); ?></strong>
                            <small class="text-muted">(<?php echo $yorum['tarih']; ?>)</small>

                            
                            <?php if(
                                (isset($_SESSION['kullanici_id']) && $_SESSION['kullanici_id'] == $yorum['kullanici_id']) ||
                                (isset($_SESSION['rol']) && $_SESSION['rol'] == 'yönetici')
                            ): ?>

                                
                                <?php if(isset($_SESSION['kullanici_id']) && $_SESSION['kullanici_id'] == $yorum['kullanici_id']): ?>
                                    <a href="yorum_duzenle.php?id=<?php echo $yorum['yorum_id']; ?>" 
                                       class="btn btn-warning btn-sm float-right ml-2">
                                        Düzenle
                                    </a>
                                <?php endif; ?>

                              
                                <a href="admin/yorum_sil.php?id=<?php echo $yorum['yorum_id']; ?>&kitap_id=<?php echo $kitap_id; ?>" 
                                   class="btn btn-danger btn-sm float-right"
                                   onclick="return confirm('Yorumu silmek istiyor musun?');">
                                    Sil
                                </a>

                            <?php endif; ?>

                            <p class="mt-2"><?php echo nl2br(htmlspecialchars($yorum['yorum'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>

      
        <?php if(isset($_SESSION['kullanici_id'])): ?>
            <div class="card shadow mt-4">
                <div class="card-header bg-primary text-white">
                    <h5>Yorum Yap</h5>
                </div>
                <div class="card-body">

                    <form action="yorum_ekle.php" method="POST">
                        <input type="hidden" name="kitap_id" value="<?php echo $kitap_id; ?>">
                        <textarea class="form-control" name="yorum" rows="4" required></textarea>
                        <button class="btn btn-success mt-2">Gönder</button>
                    </form>

                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info mt-4">
                Yorum yapmak için <a href="login.php">giriş yapın</a>.
            </div>
        <?php endif; ?>

    </div>
</div>

<?php require_once 'footer.php'; ?>
