<?php
session_start(); // Tambahkan ini di paling atas
include 'koneksi.php';

// Pastikan hanya yang sudah login yang bisa tambah folder
if($_SESSION['status'] != "login"){
    header("location:login.php");
    exit();
}

// Menangani Simpan Folder
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nama_folder'])) {
    $nama = $_POST['nama_folder'];
    $tipe = $_POST['tipe_arsip'];
    $kat  = $_POST['kategori_anggota'];
    $sem  = $_POST['semester'];
    $thn  = $_POST['tahun_akademik'];

    $query = "INSERT INTO folders (nama_folder, semester, tahun_akademik, tipe_arsip, kategori_anggota) 
              VALUES ('$nama', '$sem', '$thn', '$tipe', '$kat')";

    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
    } else {
        echo "Gagal menyimpan data: " . mysqli_error($conn);
    }
}
?>