<?php
require_once 'includes/auth.php';
include 'koneksi.php';

if (isset($_POST['login'])) {
    $requestEmail = mysqli_real_escape_string($koneksi, $_POST['email']);
    $requestPassword = $_POST['password'];

    $sql = "SELECT * FROM tb_users WHERE email = '$requestEmail'";
    $result = mysqli_query($koneksi, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($requestPassword, $row['password'])) {
            login_user($row['id_user'], $row['nama'], $row['email']);
            header('location:dashboard.php');
        } else {
            echo "
            <script>
                alert('email atau password anda salah, Silahkan coba lagi');
                window.location = 'login.php';
            </script>
            ";
        }
    } else {
        echo "
        <script>
            alert('email atau password anda salah, Silahkan coba lagi');
            window.location = 'login.php';
        </script>
        ";
    }
}
