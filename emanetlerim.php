<?php require_once 'header.php'; 

if(!isset($_SESSION['kullanici_id'])){
    header("Location: login.php");
    exit;
}

$kullanici_id = $_SESSION['kullanici_id'];
$emanetler = $db->prepare("
    SELECT odunc.*, kitaplar.kitap_adi, kitaplar.yazar, kitaplar.adet 
    FROM odunc 
    JOIN kitaplar ON odunc.kitap_id = kitaplar.kitap_id 
    WHERE odunc.kullanici_id=? AND odunc.iade_edildi=0
");
$emanetler->execute([$kullanici_id]);
$emanetler = $emanetler->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card shadow mt-4">
    <div class="card-header bg-info text-white">
        <h4>Ödünç Aldığım Kitaplar</h4>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Kitap Adı</th>
                    <th>Yazar</th>
                    <th>Alış Tarihi</th>
                    <th>Durum</th>
                    <th>Adet</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
            <?php if(count($emanetler)==0): ?>
                <tr><td colspan="6" class="text-center">Şu an elinizde ödünç kitap yok.</td></tr>
            <?php else: ?>
                <?php foreach($emanetler as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['kitap_adi']); ?></td>
                        <td><?php echo htmlspecialchars($row['yazar']); ?></td>
                        <td><?php echo $row['alinma_tarihi']; ?></td>
                        <td><span class="badge badge-warning">Okunuyor</span></td>
                        <td><?php echo $row['adet']; ?></td>
                        <td>
                            <a href="iade_et.php?id=<?php echo $row['odunc_id']; ?>&kitap_id=<?php echo $row['kitap_id']; ?>" 
                               class="btn btn-success btn-sm"
                               onclick="return confirm('Kitabı iade etmek istiyor musunuz?')">
                               İade Et
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'footer.php'; ?>
