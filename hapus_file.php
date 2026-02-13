<?php
include 'koneksi.php';

$id = $_GET['id'];
$id_folder = $_GET['folder'];

// 1. Ambil nama file dari database dulu supaya bisa hapus file fisiknya
$get_file = mysqli_query($conn, "SELECT file_path FROM arsip WHERE id_arsip='$id'");
$data = mysqli_fetch_array($get_file);
$nama_file = $data['file_path'];

// 2. Hapus file fisik dari folder Uploads (U besar sesuai folder kamu)
unlink("Uploads/" . $nama_file);

// 3. Hapus data dari database
$delete = mysqli_query($conn, "DELETE FROM arsip WHERE id_arsip='$id'");

if($delete){
    header("location:view_folder.php?id=$id_folder&pesan=hapus_berhasil");
} else {
    echo "Gagal menghapus data.";
}
?>