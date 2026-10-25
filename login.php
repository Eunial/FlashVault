<?php
require_once __DIR__ . '/includes/auth.php';

// Sudah login? langsung ke dashboard
if (current_user() !== null) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$email = $_COOKIE['fv_email'] ?? ''; // cookie: isi otomatis email terakhir
$info  = (($_GET['pesan'] ?? '') === 'logout') ? 'Anda sudah keluar. Sampai jumpa!' : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Email dan kata sandi wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } else {
        // Belum ada database: pakai nama dari cookie jika email cocok, selain itu dari bagian depan email
        if (($_COOKIE['fv_email'] ?? '') === $email && !empty($_COOKIE['fv_nama'])) {
            $nama = $_COOKIE['fv_nama'];
        } else {
            $nama = ucfirst(explode('@', $email)[0]);
        }
        login_user($nama, $email);
        header('Location: dashboard.php');
        exit;
    }
}
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

            <form action="login.php" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="loginEmail">Alamat Email</label>
                    <input type="email" id="loginEmail" name="email" value="<?= e($email) ?>" class="form-control" placeholder="nama@email.com" required />
                </div>

                <div class="form-group">
                    <label for="loginPassword">Kata Sandi</label>
                    <input type="password" id="loginPassword" name="password" class="form-control" placeholder="••••••••" required />
                </div>

                <button type="submit" class="btn-auth-submit">
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