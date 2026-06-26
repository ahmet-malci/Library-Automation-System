<?php 
require_once '../header.php';

if(!isset($_SESSION['kullanici_id']) || $_SESSION['rol'] != 'yönetici'){
    die("<div class='alert alert-danger text-center'>Bu sayfayı görüntüleme yetkiniz yok.</div>");
}

$kullanicilar = $db->query("SELECT * FROM kullanicilar ORDER BY kullanici_id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0">Kullanıcılar</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ad Soyad</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($kullanicilar as $k): ?>
                        <tr>
                            <td><?php echo $k['kullanici_id']; ?></td>
                            <td><?php echo $k['kullanici_adi']; ?></td>
                            <td><?php echo $k['email']; ?></td>
                            <td><?php echo $k['rol']; ?></td>
                            <td>
                                <a href="/kutuphane/admin/kullanici_duzenle.php?id=<?php echo $k['kullanici_id']; ?>" class="btn btn-warning btn-sm">Düzenle</a>
                                <a href="/kutuphane/admin/kullanici_sil.php?id=<?php echo $k['kullanici_id']; ?>" class="btn btn-danger btn-sm"
                                   onclick="return confirm('Kullanıcıyı silmek istiyor musunuz?')">Sil</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../footer.php'; ?>
