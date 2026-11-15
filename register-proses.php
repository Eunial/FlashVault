<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    $nama =  $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO tb_users (nama, email, password) VALUES('$nama', '$email', '$password')";

    if (empty($nama) || empty($email) || empty($_POST['password'])) {
        echo "
            <script>
                alert('Pastikan Anda Mengisi Semua Data');
                window.location = 'register.php';
            </script>
        ";
    } elseif (mysqli_query($koneksi, $sql)) {
        echo "
            <script>
                alert('Registrasi Berhasil Silahkan login');
                window.location = 'login.php';
            </script>
        ";
    } else {
        // gagal, misalnya email sudah terdaftar
        echo "
            <script>
                alert('Terjadi Kesalahan, email mungkin sudah terdaftar');
                window.location = 'register.php';
            </script>
        ";
    }
}
