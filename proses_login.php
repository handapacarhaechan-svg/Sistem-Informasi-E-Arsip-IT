<?php
session_start();
include 'koneksi.php';

// Mengambil data input tanpa menampilkannya kembali
$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

// Mencari di database
$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");

if(mysqli_num_rows($query) > 0){
    $data = mysqli_fetch_assoc($query);
    $_SESSION['status'] = "login";
    $_SESSION['username'] = $data['username'];
    header("location:index.php");
} else {
    // Jika salah, kirim kode "gagal" saja tanpa menyebutkan password mana yang salah
    header("location:login.php?error=1");
}
?>