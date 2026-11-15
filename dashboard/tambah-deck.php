<?php
require_once __DIR__ . '/../includes/auth.php';
require_login('../login.php');
$user = current_user();
$nama = $user['nama'];

include __DIR__ . '/../koneksi.php';
$flash = get_flash();
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
$kategori = mysqli_fetch_all(mysqli_query($koneksi, 'SELECT id_kategori, nama_kategori FROM tb_categories ORDER BY id_kategori'), MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Deck Baru - FlashVault</title>
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
  <?php $halaman_aktif = 'kelola'; $root = '../'; include __DIR__ . '/../includes/sidebar.php'; ?>

  <!-- Area Kerja Utama -->
  <main class="main-workspace">

    <header class="workspace-header">
      <div class="header-title">
        <h1>Tambah Deck Baru ➕</h1>
        <p>Buat wadah materi baru sebelum menambahkan kartu flashcard.</p>
      </div>
      <div class="header-actions">
        <a href="kelola-deck.php" class="btn-secondary">
          <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
      </div>
    </header>

    <section class="dashboard-section">
      <div class="form-container">
        <?php if ($flash): ?>
          <div class="flash flash-<?= e($flash['tipe']) ?>"><?= e($flash['pesan']) ?></div>
        <?php endif; ?>
        <form action="deck-proses.php" method="post">
          <input type="hidden" name="aksi" value="tambah">

          <div class="form-group">
            <label for="namaDeck">Nama Deck <span style="color: red;">*</span></label>
            <input type="text" id="namaDeck" name="nama_deck" class="form-control" maxlength="100"
              value="<?= e($old['nama_deck'] ?? '') ?>" placeholder="Contoh: Fundamental React Native" required>
          </div>

          <div class="form-group">
            <label for="kategoriDeck">Kategori <span style="color: red;">*</span></label>
            <select id="kategoriDeck" name="id_kategori" class="form-control" required>
              <option value="" disabled <?= empty($old['id_kategori']) ? 'selected' : '' ?>>-- Pilih Kategori --</option>
              <?php foreach ($kategori as $k): ?>
                <option value="<?= (int) $k['id_kategori'] ?>" <?= ((int) ($old['id_kategori'] ?? 0)) === (int) $k['id_kategori'] ? 'selected' : '' ?>><?= e($k['nama_kategori']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Pratinjau Deck Langsung (event change) -->
          <div class="deck-preview" id="deckPreview" data-empty="true">
            <span class="deck-preview-label">PRATINJAU DECK</span>
            <div class="deck-preview-card">
              <div class="deck-preview-icon" id="previewIcon"><i class="fa-solid fa-layer-group"></i></div>
              <div>
                <h4 id="previewName">Nama deck Anda</h4>
                <span class="deck-preview-badge" id="previewBadge">Belum ada kategori</span>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="deskripsiDeck">Deskripsi / Catatan Deck</label>
            <textarea id="deskripsiDeck" name="deskripsi" class="form-control"
              placeholder="Rangkuman singkat tentang topik deck ini..."><?= e($old['deskripsi'] ?? '') ?></textarea>
          </div>

          <div class="form-actions">
            <a href="kelola-deck.php" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary-sm">Simpan Deck</button>
          </div>

        </form>
      </div>
    </section>
  </main>
  
  <script src="../js/dashboard.js"></script>
</body>
</html>