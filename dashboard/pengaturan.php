<?php
require_once __DIR__ . '/../includes/auth.php';
require_login('../login.php');
$user = current_user();
$nama = $user['nama'];
$email = $user['email'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaBaru  = trim($_POST['nama'] ?? '');
    $emailBaru = trim($_POST['email'] ?? '');

    if ($namaBaru === '' || $emailBaru === '') {
        $error = 'Nama dan email wajib diisi.';
    } elseif (!filter_var($emailBaru, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } else {
        $_SESSION['user'] = ['nama' => $namaBaru, 'email' => $emailBaru];
        setcookie('fv_nama', $namaBaru, time() + 7 * 24 * 60 * 60, '/');
        setcookie('fv_email', $emailBaru, time() + 7 * 24 * 60 * 60, '/');
        header('Location: ../dashboard.php');
        exit;
    }
    $nama = $namaBaru; // pertahankan isian jika error
    $email = $emailBaru;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun - FlashVault</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <!-- Sidebar Navigasi (Identik dengan sub-halaman dashboard lainnya) -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2>FlashVault</h2>
            <p>Spaced Repetition Platform</p>
        </div>

        <!-- User Profile Card -->
        <div class="user-card">
            <div class="user-avatar">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($nama) ?>&background=2196F3&color=fff" alt="Foto Profil">
            </div>
            <div class="user-details">
                <h4><?= e($nama) ?></h4>
                <span class="user-status">Pelajar / Mahasiswa</span>
            </div>
        </div>

        <!-- Menu Utama -->
        <div class="menu-group">
            <span class="menu-label">MENU UTAMA</span>
            <a href="../dashboard.php" class="nav-link">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Overview</span>
            </a>
            <a href="kelola-deck.php" class="nav-link">
                <i class="fa-solid fa-layer-group"></i>
                <span>Kelola Deck</span>
            </a>
            <a href="kuis.php" class="nav-link">
                <i class="fa-solid fa-graduation-cap"></i>
                <span>Sesi Kuis & Review</span>
            </a>
            <a href="pengaturan.php" class="nav-link active">
                <i class="fa-solid fa-user-gear"></i>
                <span>Pengaturan Akun</span>
            </a>
        </div>

        <!-- Sidebar Bottom / Logout -->
        <div class="sidebar-bottom">
            <a href="../logout.php" class="logout-link">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Keluar</span>
            </a>
        </div>
    </aside>

    <!-- Area Kerja Utama -->
    <main class="main-workspace">

        <!-- Header Workspace -->
        <header class="workspace-header">
            <div class="header-title">
                <h1>Pengaturan Akun ⚙️</h1>
                <p>Kelola profil pengguna, preferensi aplikasi, dan keamanan akun Anda.</p>
            </div>
            <div class="header-actions">
                <a href="../logout.php" class="btn-header-logout">Keluar</a>
            </div>
        </header>

        <!-- Form Pengaturan -->
        <section class="dashboard-section">
            <div class="form-container">
                <?php if ($error): ?>
                    <div style="background:#fdecea;color:#b71c1c;padding:.7rem 1rem;border-radius:8px;margin-bottom:1rem;"><?= e($error) ?></div>
                <?php endif; ?>
                <form action="pengaturan.php" method="POST">

                    <h3
                        style="margin-bottom: 1.2rem; color: var(--text-dark); border-bottom: 1px solid var(--soft-border); padding-bottom: 0.5rem;">
                        <i class="fa-solid fa-user-pen" style="color: var(--primary-blue); margin-right: 0.5rem;"></i>
                        Profil Pengguna
                    </h3>

                    <div class="form-group">
                        <label for="profNama">Nama Lengkap <span style="color: red;">*</span></label>
                        <input type="text" id="profNama" name="nama" class="form-control" value="<?= e($nama) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="profEmail">Alamat Email <span style="color: red;">*</span></label>
                        <input type="email" id="profEmail" name="email" class="form-control" value="<?= e($email) ?>" required>
                    </div>
                    <h3
                        style="margin: 2rem 0 1.2rem; color: var(--text-dark); border-bottom: 1px solid var(--soft-border); padding-bottom: 0.5rem;">
                        <i class="fa-solid fa-lock" style="color: var(--primary-blue); margin-right: 0.5rem;"></i>
                        Keamanan Akun
                    </h3>

                    <div class="form-group">
                        <label for="profPassOld">Kata Sandi Saat Ini</label>
                        <input type="password" id="profPassOld" class="form-control" placeholder="••••••••">
                    </div>

                    <div class="form-group">
                        <label for="profPassNew">Kata Sandi Baru</label>
                        <input type="password" id="profPassNew" class="form-control"
                            placeholder="Kosongkan jika tidak ingin mengubah">
                    </div>

                    <div class="form-actions">
                        <a href="../dashboard.php" class="btn-secondary">Batal</a>
                        <button type="submit" class="btn-primary-sm">Simpan Perubahan</button>
                    </div>

                </form>
            </div>
        </section>
    </main>
    <script src="../js/storage.js"></script>
    <script src="../js/dashboard.js"></script>
</body>
</html>