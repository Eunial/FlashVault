<?php
require_once __DIR__ . '/includes/auth.php';

if (current_user() !== null) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$nama = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim($_POST['nama'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($nama === '' || $email === '' || $password === '') {
        $error = 'Semua field wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif (strlen($password) < 8) {
        $error = 'Kata sandi minimal 8 karakter.';
    } else {
        // Nama disimpan di SESSION (+ cookie); kata sandi tidak disimpan
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
    <title>Daftar Akun - FlashVault</title>
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
                <h2>Buat Akun Baru</h2>
                <p>Mulai tingkatkan ingatan Anda dengan Spaced Repetition.</p>
            </div>

            <?php if ($error): ?>
                <div class="auth-alert auth-alert-error"><?= e($error) ?></div>
            <?php endif; ?>

            <form action="register.php" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="regNama">Nama Lengkap</label>
                    <input type="text" id="regNama" name="nama" value="<?= e($nama) ?>" class="form-control" placeholder="Contoh: Rama" required />
                </div>

                <div class="form-group">
                    <label for="regEmail">Alamat Email</label>
                    <input type="email" id="regEmail" name="email" value="<?= e($email) ?>" class="form-control" placeholder="nama@email.com" required />
                </div>

                <div class="form-group">
                    <label for="regPassword">Kata Sandi</label>
                    <input type="password" id="regPassword" name="password" class="form-control" placeholder="Minimal 8 karakter"
                        required />
                </div>

                <button type="submit" class="btn-auth-submit">
                    Daftar Akun Gratis
                </button>
            </form>

            <div class="auth-footer">
                Sudah punya akun? <a href="login.php">Masuk di Sini</a>
            </div>

            <a href="index.php" class="back-home-link"><i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda</a>
        </div>
    </div>
    <script src="js/storage.js"></script>
    <script src="js/auth.js"></script>
</body>
</html>