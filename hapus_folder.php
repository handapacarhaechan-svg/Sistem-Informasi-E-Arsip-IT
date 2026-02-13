<?php
session_start();
include 'koneksi.php';

// Cek login
if(!isset($_SESSION['status']) || $_SESSION['status'] != "login"){
    header("location:login.php");
    exit();
}

// Ambil ID dari URL
if(isset($_GET['id'])){
    $id = $_GET['id'];

    // 1. Hapus data folder di database
    // Catatan: Jika kamu sudah mensetting "ON DELETE CASCADE" di database, 
    // maka file di dalam folder ini akan otomatis ikut terhapus di tabel arsip.
    $query = mysqli_query($conn, "DELETE FROM folders WHERE id_folder = '$id'");

    if($query){
        // Berhasil hapus, balik ke index
        header("location:index.php?pesan=hapus_berhasil");
    } else {
        // Gagal hapus
        echo "Gagal menghapus folder: " . mysqli_error($conn);
    }
} else {
    header("location:index.php");
}
?>