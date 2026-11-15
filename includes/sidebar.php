<?php
// Sidebar dashboard (dipakai bersama oleh semua halaman dashboard)
// Variabel yang harus diset oleh halaman pemanggil sebelum include:
//   $halaman_aktif : 'dashboard' | 'kelola' | 'kuis' | 'pengaturan'
//   $root          : path ke folder utama project ('' dari dashboard.php, '../' dari folder dashboard/)
//   $nama          : nama user yang sedang login
$menu = [
    'dashboard'  => ['Overview',             'fa-chart-pie',       $root . 'dashboard.php'],
    'kelola'     => ['Kelola Deck',          'fa-layer-group',     $root . 'dashboard/kelola-deck.php'],
    'kuis'       => ['Sesi Kuis & Review',   'fa-graduation-cap',  $root . 'dashboard/kuis.php'],
    'pengaturan' => ['Pengaturan Akun',      'fa-user-gear',       $root . 'dashboard/pengaturan.php'],
];
?>
<aside class="sidebar">
  <div class="sidebar-brand">
    <h2>FlashVault</h2>
    <p>Spaced Repetition Platform</p>
  </div>

  <div class="user-card">
    <div class="user-avatar">
      <img src="https://ui-avatars.com/api/?name=<?= urlencode($nama) ?>&background=2196F3&color=fff" alt="Foto Profil">
    </div>
    <div class="user-details">
      <h4><?= e($nama) ?></h4>
      <span class="user-status">Pelajar / Mahasiswa</span>
    </div>
  </div>

  <div class="menu-group">
    <span class="menu-label">MENU UTAMA</span>
    <?php foreach ($menu as $kunci => [$label, $ikon, $url]): ?>
      <a href="<?= $url ?>" class="nav-link<?= $kunci === $halaman_aktif ? ' active' : '' ?>">
        <i class="fa-solid <?= $ikon ?>"></i>
        <span><?= $label ?></span>
      </a>
    <?php endforeach; ?>
  </div>

  <div class="sidebar-bottom">
    <a href="<?= $root ?>logout.php" class="logout-link">
      <i class="fa-solid fa-right-from-bracket"></i>
      <span>Keluar</span>
    </a>
  </div>
</aside>
