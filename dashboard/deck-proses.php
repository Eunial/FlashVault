<?php
// Proses CRUD tabel tb_decks: tambah (Create), ubah (Update), hapus (Delete)
require_once __DIR__ . '/../includes/auth.php';
require_login('../login.php');
include __DIR__ . '/../koneksi.php';

$id_user = current_user()['id'];

function kembali($ke, $tipe, $pesan)
{
    set_flash($tipe, $pesan);
    header('Location: ' . $ke);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: kelola-deck.php');
    exit;
}

$aksi = $_POST['aksi'] ?? '';

// Hapus tidak butuh validasi input lain
if ($aksi === 'hapus') {
    $id_deck = (int) ($_POST['id_deck'] ?? 0);
    // DELETE: hanya milik user yang sedang login (kartu ikut terhapus via ON DELETE CASCADE)
    $stmt = mysqli_prepare($koneksi, 'DELETE FROM tb_decks WHERE id_deck = ? AND id_user = ?');
    mysqli_stmt_bind_param($stmt, 'ii', $id_deck, $id_user);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        kembali('kelola-deck.php', 'success', 'Deck berhasil dihapus.');
    }
    kembali('kelola-deck.php', 'error', 'Deck tidak ditemukan atau gagal dihapus.');
}

if ($aksi !== 'tambah' && $aksi !== 'ubah') {
    kembali('kelola-deck.php', 'error', 'Aksi tidak dikenal.');
}

$id_deck     = (int) ($_POST['id_deck'] ?? 0);
$nama_deck   = trim($_POST['nama_deck'] ?? '');
$id_kategori = (int) ($_POST['id_kategori'] ?? 0);
$deskripsi   = trim($_POST['deskripsi'] ?? '');
$balik_form  = $aksi === 'tambah' ? 'tambah-deck.php' : 'edit-deck.php?id=' . $id_deck;

$_SESSION['old'] = ['nama_deck' => $nama_deck, 'id_kategori' => $id_kategori, 'deskripsi' => $deskripsi];

if ($nama_deck === '' || $id_kategori === 0) {
    kembali($balik_form, 'error', 'Nama deck dan kategori wajib diisi.');
}
if (mb_strlen($nama_deck) > 100) {
    kembali($balik_form, 'error', 'Nama deck maksimal 100 karakter.');
}

// Pastikan kategori valid
$cek = mysqli_prepare($koneksi, 'SELECT 1 FROM tb_categories WHERE id_kategori = ?');
mysqli_stmt_bind_param($cek, 'i', $id_kategori);
mysqli_stmt_execute($cek);
mysqli_stmt_store_result($cek);
if (mysqli_stmt_num_rows($cek) === 0) {
    kembali($balik_form, 'error', 'Kategori tidak valid.');
}
mysqli_stmt_close($cek);

// Nama deck tidak boleh kembar untuk user yang sama
$dup = mysqli_prepare($koneksi, 'SELECT 1 FROM tb_decks WHERE id_user = ? AND nama_deck = ? AND id_deck <> ?');
mysqli_stmt_bind_param($dup, 'isi', $id_user, $nama_deck, $id_deck);
mysqli_stmt_execute($dup);
mysqli_stmt_store_result($dup);
if (mysqli_stmt_num_rows($dup) > 0) {
    kembali($balik_form, 'error', 'Deck bernama "' . $nama_deck . '" sudah ada. Gunakan nama lain.');
}
mysqli_stmt_close($dup);

if ($aksi === 'tambah') {
    // CREATE
    $stmt = mysqli_prepare($koneksi, 'INSERT INTO tb_decks (id_user, id_kategori, nama_deck, deskripsi) VALUES (?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'iiss', $id_user, $id_kategori, $nama_deck, $deskripsi);
    if (mysqli_stmt_execute($stmt)) {
        unset($_SESSION['old']);
        kembali('kelola-deck.php', 'success', 'Deck "' . $nama_deck . '" berhasil disimpan.');
    }
    kembali($balik_form, 'error', 'Gagal menyimpan deck.');
}

// UPDATE: hanya milik user yang sedang login
$stmt = mysqli_prepare($koneksi, 'UPDATE tb_decks SET nama_deck = ?, id_kategori = ?, deskripsi = ? WHERE id_deck = ? AND id_user = ?');
mysqli_stmt_bind_param($stmt, 'sisii', $nama_deck, $id_kategori, $deskripsi, $id_deck, $id_user);
if (mysqli_stmt_execute($stmt)) {
    unset($_SESSION['old']);
    kembali('kelola-deck.php', 'success', 'Deck "' . $nama_deck . '" berhasil diperbarui.');
}
kembali($balik_form, 'error', 'Gagal memperbarui deck.');
