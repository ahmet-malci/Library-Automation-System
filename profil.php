<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'baglan.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['kullanici_id'])) {
    header("Location: login.php");
    exit;
}

$kullanici_id = $_SESSION['kullanici_id'];

$stmt = $db->prepare("SELECT * FROM kullanicilar WHERE kullanici_id = ?");
$stmt->execute([$kullanici_id]);
$kullanici = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$kullanici) {
    die("<div class='container mt-4'><div class='alert alert-danger'>Kullanıcı bulunamadı.</div></div>");
}

$mesaj = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guncelle'])) {

    $kullanici_adi = trim($_POST['kullanici_adi']);
    $email = trim($_POST['email']);
    $sifre = $_POST['sifre'];
    $sifre2 = $_POST['sifre2'];

    if ($kullanici_adi === "" || $email === "") {
        $mesaj = "<div class='alert alert-danger'>Kullanıcı adı ve e-posta boş bırakılamaz.</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mesaj = "<div class='alert alert-danger'>Geçerli bir e-posta adresi girin.</div>";
    } else {

        $sql = "UPDATE kullanicilar SET kullanici_adi = ?, email = ?, guncellenme_tarihi = NOW()";
        $params = [$kullanici_adi, $email];

        
        if (!empty($sifre)) {
            if ($sifre !== $sifre2) {
                $mesaj = "<div class='alert alert-danger'>Şifreler uyuşmuyor!</div>";
            } else {
                $hash = password_hash($sifre, PASSWORD_DEFAULT);
                $sql .= ", sifre = ?";
                $params[] = $hash;
            }
        }

        if ($mesaj === "") {
            $sql .= " WHERE kullanici_id = ?";
            $params[] = $kullanici_id;

            $upd = $db->prepare($sql);
            $upd->execute($params);

           
            $_SESSION['kullanici_adi'] = $kullanici_adi;

            $mesaj = "<div class='alert alert-success'>Bilgiler başarıyla güncellendi.</div>";
            header("Location: index.php");
            exit;

        }
    }
}

require_once 'header.php';
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">Profil Bilgilerim</h4>
        </div>

        <div class="card-body">
            <?php if ($mesaj) echo $mesaj; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Kullanıcı Adı</label>
                    <input type="text" name="kullanici_adi" class="form-control"
                           value="<?php echo htmlspecialchars($kullanici['kullanici_adi']); ?>" required>
                </div>

                <div class="form-group">
                    <label>E-posta</label>
                    <input type="email" name="email" class="form-control"
                           value="<?php echo htmlspecialchars($kullanici['email']); ?>" required>
                </div>

                <hr>
                <p class="text-muted small">Şifreyi değiştirmek istemiyorsanız boş bırakınız.</p>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Yeni Şifre</label>
                        <input type="password" name="sifre" class="form-control" placeholder="Yeni şifre">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Yeni Şifre (Tekrar)</label>
                        <input type="password" name="sifre2" class="form-control" placeholder="Yeni şifre tekrar">
                    </div>
                </div>

                <button type="submit" name="guncelle" class="btn btn-info btn-block">
                    Bilgileri Güncelle
                </button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
