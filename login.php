<?php
require_once __DIR__ . '/includes/auth.php';

// Sudah login? langsung ke dashboard
if (current_user() !== null) {
    header('Location: dashboard.php');
    exit;
}

// Pesan dari login-proses.php / register-proses.php / logout.php
$flash = get_flash();
$error = ($flash['tipe'] ?? '') === 'error' ? $flash['pesan'] : '';
$sukses = ($flash['tipe'] ?? '') === 'success' ? $flash['pesan'] : '';
$info  = (($_GET['pesan'] ?? '') === 'logout') ? 'Anda sudah keluar. Sampai jumpa!' : $sukses;
$email = $_SESSION['old']['email'] ?? ($_COOKIE['fv_email'] ?? '');
unset($_SESSION['old']);
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Masuk - FlashVault</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
    <div class="auth-page-wrapper">
        <div class="auth-card">
            <div class="auth-header">
                <a href="index.php" class="auth-logo">
                    <i class="fa-solid fa-box-archive"></i>
                    <span>FlashVault</span>
                </a>
                <h2>Selamat Datang Kembali!</h2>
                <p>Masuk untuk melanjutkan sesi belajar Anda.</p>
            </div>

            <?php if ($info): ?>
                <div class="auth-alert auth-alert-info"><?= e($info) ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="auth-alert auth-alert-error"><?= e($error) ?></div>
            <?php endif; ?>

            <form action="login-proses.php" method="post" class="auth-form">
                <div class="form-group">
                    <label for="loginEmail">Alamat Email</label>
                    <input type="email" id="loginEmail" name="email" value="<?= e($email) ?>" class="form-control" placeholder="nama@email.com" required />
                </div>

                <div class="form-group">
                    <label for="loginPassword">Kata Sandi</label>
                    <input type="password" id="loginPassword" name="password" class="form-control" placeholder="••••••••" required />
                </div>

                <button type="submit" name="login" class="btn-auth-submit">
                    Masuk ke Dashboard
                </button>
            </form>

            <div class="auth-footer">
                Belum punya akun? <a href="register.php">Daftar Sekarang</a>
            </div>

            <a href="index.php" class="back-home-link"><i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda</a>
        </div>
    </div>
</body>

</html>