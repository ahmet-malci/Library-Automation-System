<?php require_once 'header.php'; ?>

<div class="container mt-4">
    <div class="jumbotron bg-info text-white p-4 rounded shadow-sm text-center mb-3">
        <h1 class="display-5">Hoşgeldiniz!</h1>
        <p class="lead">Bu kütüphane arşivinde giriş yaptıktan sonra kitapları ödünç alabilir ve yorum yapabilirsiniz.</p>
        <hr class="my-2">
    </div>
</div>


<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="index.php" class="form-inline justify-content-center">

           
            <input type="text" name="ara" class="form-control mr-2 w-50"
                placeholder="Kitap adı veya yazara göre ara..."
                value="<?php echo isset($_GET['ara']) ? htmlspecialchars($_GET['ara']) : ''; ?>">

            
            <select name="kategori" class="form-control mr-2">
                <option value="">Tüm Kategoriler</option>
                <?php
                $katSorgu = $db->query("SELECT * FROM kategoriler ORDER BY kategori_adi ASC");
                foreach($katSorgu as $kat):
                ?>
                <option value="<?php echo $kat['kategori_id']; ?>"
                    <?php echo (isset($_GET['kategori']) && $_GET['kategori'] == $kat['kategori_id']) ? 'selected' : ''; ?>>
                    <?php echo $kat['kategori_adi']; ?>
                </option>
                <?php endforeach; ?>
            </select>

           
            <select name="durum" class="form-control mr-2">
                <option value="">Tüm Kitaplar</option>
                <option value="müsait" <?php echo (isset($_GET['durum']) && $_GET['durum'] == 'müsait') ? 'selected' : ''; ?>>
                    Sadece Müsait
                </option>
                <option value="ödünçte" <?php echo (isset($_GET['durum']) && $_GET['durum'] == 'ödünçte') ? 'selected' : ''; ?>>
                    Sadece Tükenen
                </option>
            </select>

            <button type="submit" class="btn btn-info">Filtrele</button>
        </form>
    </div>
</div>

<?php


$arama = isset($_GET['ara']) ? trim($_GET['ara']) : "";
$kategori = isset($_GET['kategori']) ? trim($_GET['kategori']) : "";
$durum = isset($_GET['durum']) ? trim($_GET['durum']) : "";

$sql = "
    SELECT kitaplar.*, kategoriler.kategori_adi 
    FROM kitaplar 
    JOIN kategoriler ON kitaplar.kategori_id = kategoriler.kategori_id
    WHERE 1
";

$params = [];


if ($arama != "") {
    echo "<div class='alert alert-info text-center'>Arama sonucu: <strong>$arama</strong></div>";
    $sql .= " AND (kitap_adi LIKE ? OR yazar LIKE ?)";
    $params[] = "%$arama%";
    $params[] = "%$arama%";
}


if ($kategori != "") {
    $sql .= " AND kitaplar.kategori_id = ?";
    $params[] = $kategori;
}


if ($durum != "") {
    $sql .= " AND kitaplar.durum = ?";
    $params[] = $durum;
}


$sql .= " ORDER BY kitaplar.kitap_id DESC";

$sorgu = $db->prepare($sql);
$sorgu->execute($params);
$kitaplar = $sorgu->fetchAll(PDO::FETCH_ASSOC);
?>


<div class="row">
    <?php
    if (count($kitaplar) == 0) {
        echo "<div class='col-12'>
                <div class='alert alert-warning text-center'>
                    Aradığınız kriterlere uygun kitap bulunamadı.
                </div>
              </div>";
    }

    foreach($kitaplar as $kitap):
    ?>
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm">
                
              
          <div class="kitap-resim-kutu">
                <img src="<?php echo $kitap['resim']; ?>" class="kitap-resim" alt="Kitap Resmi">
                </div>


                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-primary"><?php echo $kitap['kitap_adi']; ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $kitap['yazar']; ?></h6>

                    <p class="card-text text-secondary" style="font-size: 14px;">
                        <span class="badge badge-info">
                            <?php echo $kitap['kategori_adi']; ?>
                        </span>
                        <br>
                        <?php echo substr($kitap['ozet'], 0, 50); ?>...
                    </p>

                 
                    <?php if($kitap['durum'] == 'müsait'): ?>
                        <span class="badge badge-success mb-2" style="font-size:12px;">Müsait</span>
                    <?php else: ?>
                        <span class="badge badge-danger mb-2" style="font-size:12px;">Ödünçte</span>
                    <?php endif; ?>

                   
                    <div class="btn-group w-100 mt-auto">

                        <?php if(isset($_SESSION['kullanici_id'])): ?>

                            <?php if($kitap['durum'] == 'müsait'): ?>
                                <a href="odunc_al.php?id=<?php echo $kitap['kitap_id']; ?>" 
                                   class="btn btn-outline-primary btn-sm">
                                   Ödünç Al
                                </a>
                            <?php else: ?>
                                <button class="btn btn-secondary btn-sm" disabled>Alınamaz</button>
                            <?php endif; ?>

                            <a href="detay.php?id=<?php echo $kitap['kitap_id']; ?>" 
                               class="btn btn-outline-info btn-sm">
                               Detay
                            </a>

                            <?php if($_SESSION['rol'] == 'yönetici'): ?>
                                <a href="admin/kitap_duzenle.php?id=<?php echo $kitap['kitap_id']; ?>" 
                                   class="btn btn-outline-warning btn-sm">
                                   Düzenle
                                </a>
                            <?php endif; ?>

                        <?php else: ?>
                            <a href="login.php" 
                               class="btn btn-outline-secondary btn-sm w-100">
                               İşlem İçin Giriş Yap
                            </a>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once 'footer.php'; ?>
