<?php
require_once __DIR__ . '/../includes/auth.php';
require_login('../login.php');
$user = current_user();
$nama = $user['nama'];

include __DIR__ . '/../koneksi.php';
$flash = get_flash();

// READ: seluruh deck milik user beserta kategori dan jumlah kartunya
$stmt = mysqli_prepare($koneksi, 'SELECT d.id_deck, d.nama_deck, d.deskripsi, k.nama_kategori, COUNT(c.id_kartu) AS jumlah_kartu
    FROM tb_decks d
    JOIN tb_categories k ON k.id_kategori = d.id_kategori
    LEFT JOIN tb_cards c ON c.id_deck = d.id_deck
    WHERE d.id_user = ?
    GROUP BY d.id_deck, d.nama_deck, d.deskripsi, k.nama_kategori
    ORDER BY d.created_at DESC, d.id_deck DESC');
mysqli_stmt_bind_param($stmt, 'i', $user['id']);
mysqli_stmt_execute($stmt);
$decks = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Deck - FlashVault</title>
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
  <?php $halaman_aktif = 'kelola'; $root = '../'; include __DIR__ . '/../includes/sidebar.php'; ?>

  <!-- Area Kerja Utama -->
  <main class="main-workspace">

    <header class="workspace-header">
      <div class="header-title">
        <h1>Kelola Koleksi Deck 📚</h1>
        <p>Atur, tambah, dan modifikasi seluruh flashcard Anda di sini.</p>
      </div>
      <div class="header-actions">
        <a href="tambah-deck.php" class="btn-primary-sm">+ Buat Deck Baru</a>
        <a href="../logout.php" class="btn-header-logout">Keluar</a>
      </div>
    </header>

    <section class="dashboard-section">
      <?php if ($flash): ?>
        <div class="flash flash-<?= e($flash['tipe']) ?>"><?= e($flash['pesan']) ?></div>
      <?php endif; ?>

      <div class="grid-2" id="deckGrid">
        <?php if (count($decks) === 0): ?>
          <div class="empty-state">
            <p>Belum ada deck. Yuk buat deck pertama Anda!</p>
            <a href="tambah-deck.php" class="btn-primary-sm">+ Buat Deck Baru</a>
          </div>
        <?php endif; ?>

        <?php foreach ($decks as $d): ?>
          <div class="card-item">
            <div>
              <h4><?= e($d['nama_deck']) ?></h4>
              <p><?= (int) $d['jumlah_kartu'] ?> Kartu • Kategori: <?= e($d['nama_kategori']) ?></p>
            </div>
            <div class="card-actions">
              <a href="kuis.php" class="btn-sm btn-edit"><i class="fa-solid fa-folder-open"></i> Buka</a>
              <a href="edit-deck.php?id=<?= (int) $d['id_deck'] ?>" class="btn-sm btn-edit"><i class="fa-solid fa-pen"></i> Edit</a>
              <form action="deck-proses.php" method="post" class="form-hapus" data-nama="<?= e($d['nama_deck']) ?>" style="display:inline">
                <input type="hidden" name="aksi" value="hapus">
                <input type="hidden" name="id_deck" value="<?= (int) $d['id_deck'] ?>">
                <button type="submit" class="btn-sm btn-delete"><i class="fa-solid fa-trash"></i> Hapus</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  </main>

  <script src="../js/dashboard.js"></script>
</body>
</html>