<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Escape output agar aman dari XSS
function e($teks)
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

function current_user()
{
    return $_SESSION['user'] ?? null;
}

function login_user($nama, $email)
{
    session_regenerate_id(true); // cegah session fixation
    $_SESSION['user'] = ['nama' => $nama, 'email' => $email];

    // Cookie: ingat nama & email terakhir selama 7 hari (untuk mengisi form login)
    $expire = time() + 7 * 24 * 60 * 60;
    setcookie('fv_nama', $nama, $expire, '/');
    setcookie('fv_email', $email, $expire, '/');
}

// Halaman dashboard hanya untuk pengguna yang sudah login.
// Variabel $kembali = path ke login.php relatif dari halaman pemanggil.
function require_login($kembali = 'login.php')
{
    if (current_user() === null) {
        header('Location: ' . $kembali);
        exit;
    }
}
