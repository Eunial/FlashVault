<?php
require_once __DIR__ . '/includes/auth.php';
require_login('login.php');
$user = current_user();
$nama = $user['nama'];

include __DIR__ . '/koneksi.php';
// READ: statistik koleksi dari database
$stmt = mysqli_prepare($koneksi, 'SELECT COUNT(DISTINCT d.id_deck) AS total_deck, COUNT(c.id_kartu) AS total_kartu
    FROM tb_decks d LEFT JOIN tb_cards c ON c.id_deck = d.id_deck WHERE d.id_user = ?');
mysqli_stmt_bind_param($stmt, 'i', $user['id']);
mysqli_stmt_execute($stmt);
$stat = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - FlashVault</title>
  <link rel="stylesheet" href="css/dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
  <?php $halaman_aktif = 'dashboard'; $root = ''; include __DIR__ . '/includes/sidebar.php'; ?>

  <!-- Area Kerja Utama -->
  <main class="main-workspace">

    <header class="workspace-header">
      <div class="header-title">
        <h1>Selamat Datang Kembali, <span id="namaPengguna"><?= e($nama) ?></span>! 👋</h1>
        <p>Berikut adalah ringkasan aktivitas dan progres belajar Anda.</p>
      </div>
      <div class="header-actions">
        <a href="dashboard/tambah-deck.php" class="btn-primary-sm">+ Buat Deck Baru</a>
        <a href="logout.php" class="btn-header-logout">Keluar</a>
      </div>
    </header>

    <!-- SECTION 1: OVERVIEW -->
    <section id="overview" class="dashboard-section">
      <div class="stats-row">
        <div class="stat-box">
          <div class="stat-icon-wrapper bg-orange">
            <i class="fa-solid fa-fire text-orange"></i>
          </div>
          <span class="stat-caption">RENTETAN BELAJAR</span>
          <div class="stat-number">7 Hari</div>
          <span class="stat-subtext">Pertahankan konsistensi!</span>
        </div>

        <div class="stat-box">
          <div class="stat-icon-wrapper bg-green">
            <i class="fa-solid fa-layer-group" style="color: #2e7d32;"></i>
          </div>
          <span class="stat-caption">TOTAL KOLEKSI</span>
          <div class="stat-number" id="statTotalDeck"><?= (int) $stat['total_deck'] ?> Deck</div>
          <span class="stat-subtext" id="statTotalKartu"><?= (int) $stat['total_kartu'] ?> total kartu</span>
        </div>

        <div class="stat-box">
          <div class="stat-icon-wrapper bg-purple">
            <i class="fa-solid fa-clock-rotate-left" style="color: #7b1fa2;"></i>
          </div>
          <span class="stat-caption">ANTREAN REVIEW</span>
          <div class="stat-number text-orange">45 Kartu</div>
          <span class="stat-subtext">Jadwal hari ini</span>
        </div>

        <div class="stat-box">
          <div class="stat-icon-wrapper bg-pink">
            <i class="fa-solid fa-bullseye text-red"></i>
          </div>
          <span class="stat-caption">AKURASI JAWABAN</span>
          <div class="stat-number">88%</div>
          <span class="stat-subtext">+2% dari minggu lalu</span>
        </div>
      </div>

      <div class="panels-row">
        <div class="content-panel">
          <div class="panel-header">
            <h3>Deck Terakhir Dipelajari</h3>
          </div>
          <ul class="deck-list">
            <li class="deck-item">
              <div class="deck-info">
                <span class="dot dot-blue"></span>
                <div>
                  <div class="deck-name">Fundamental Laravel & Blade</div>
                  <div class="deck-cards-count">24 dari 50 kartu dikuasai</div>
                </div>
              </div>
              <a href="dashboard/kuis.php" class="btn-sm btn-edit">Lanjutkan</a>
            </li>
            <li class="deck-item">
              <div class="deck-info">
                <span class="dot dot-green"></span>
                <div>
                  <div class="deck-name">Komponen React Native</div>
                  <div class="deck-cards-count">18 dari 30 kartu dikuasai</div>
                </div>
              </div>
              <a href="dashboard/kuis.php" class="btn-sm btn-edit">Lanjutkan</a>
            </li>
          </ul>
        </div>

        <div class="content-panel">
          <div class="panel-header">
            <h3>Antrean Ulasan Hari Ini</h3>
          </div>
          <ul class="review-list">
            <li class="review-item">
              <div class="review-info">
                <span class="review-title">Pemrograman Web Modern</span>
                <span class="review-due">Jatuh tempo hari ini</span>
              </div>
              <span class="badge-count badge-blue">25 Kartu</span>
            </li>
            <li class="review-item">
              <div class="review-info">
                <span class="review-title">Basis Data Lanjut</span>
                <span class="review-due">Jatuh tempo hari ini</span>
              </div>
              <span class="badge-count badge-blue">20 Kartu</span>
            </li>
          </ul>
        </div>
      </div>
    </section>
  </main>

  <script src="js/dashboard.js"></script>
</body>

</html>