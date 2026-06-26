<?php
require "baglan.php";

$sql = "SELECT y.yorum_id, y.yorum, y.tarih, 
               k.kullanici_adi, 
               kt.kitap_adi
        FROM yorumlar y
        JOIN kullanicilar k ON y.kullanici_id = k.kullanici_id
        JOIN kitaplar kt ON y.kitap_id = kt.kitap_id
        ORDER BY y.yorum_id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Yorumlar</title>
</head>
<body>
<h2>Yorumlar</h2>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Kullanıcı</th>
    <th>Kitap</th>
    <th>Yorum</th>
    <th>Tarih</th>
    <th>Sil</th>
</tr>

<?php while ($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?php echo $row['yorum_id']; ?></td>
    <td><?php echo $row['kullanici_adi']; ?></td>
    <td><?php echo $row['kitap_adi']; ?></td>
    <td><?php echo $row['yorum']; ?></td>
    <td><?php echo $row['tarih']; ?></td>
    <td><a href="yorum_sil.php?id=<?php echo $row['yorum_id']; ?>">Sil</a></td>
</tr>
<?php } ?>

</table>
</body>
</html>
