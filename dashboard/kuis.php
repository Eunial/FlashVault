<?php
require_once __DIR__ . '/../includes/auth.php';
require_login('../login.php');
$user = current_user();
$nama = $user['nama'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sesi Kuis & Review - FlashVault</title>
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
  <?php $halaman_aktif = 'kuis'; $root = '../'; include __DIR__ . '/../includes/sidebar.php'; ?>

  <!-- Area Kerja Utama -->
  <main class="main-workspace">

    <header class="workspace-header">
      <div class="header-title">
        <h1>Sesi Kuis & Review 🧠</h1>
        <p>Mengulas deck: <strong>Fundamental Laravel & Blade</strong></p>
      </div>
      <div class="header-actions">
        <a href="kelola-deck.php" class="btn-primary-sm">Ganti Deck</a>
        <a href="../logout.php" class="btn-header-logout">Keluar</a>
      </div>
    </header>

    <section class="dashboard-section">
      <div style="max-width: 600px; margin: 0 auto;">
        <div
          style="display: flex; justify-content: space-between; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-muted);">
          <span>Kartu 7 dari 20</span>
          <span>Sisa: 13 Kartu</span>
        </div>
        <div class="progress-bar">
          <div class="progress-fill"></div>
        </div>
      </div>

      <!-- 3D Flashcard Structure -->
      <div class="flashcard-container">
        <div class="flashcard-3d">
          <div class="card-face card-front">
            <span class="card-label">PERTANYAAN</span>
            <div class="card-question">
              Apa fungsi utama dari sintaks <code>{{ $variable }}</code> di dalam sistem templating Blade Laravel?
            </div>
            <p class="card-hint"><i class="fa-solid fa-rotate"></i> Arahkan kursor untuk melihat jawaban</p>
          </div>
          <div class="card-face card-back">
            <span class="card-label">JAWABAN</span>
            <div class="card-answer-text">
              Berfungsi untuk me-render data ke HTML, yang secara otomatis diproses menggunakan fungsi
              <code>htmlspecialchars</code> PHP untuk mencegah serangan XSS.
            </div>
          </div>
        </div>
      </div>

      <div class="quiz-controls">
        <button class="btn-rating btn-hard">Sulit<br><small style="font-weight: 400; font-size: 0.7rem;">Ulangi &lt;
            1m</small></button>
        <button class="btn-rating btn-good">Lumayan<br><small style="font-weight: 400; font-size: 0.7rem;">Review
            10m</small></button>
        <button class="btn-rating btn-easy">Mudah<br><small style="font-weight: 400; font-size: 0.7rem;">Review 4
            Hari</small></button>
      </div>
    </section>
  </main>

  <script src="../js/dashboard.js"></script>
</body>
</html>