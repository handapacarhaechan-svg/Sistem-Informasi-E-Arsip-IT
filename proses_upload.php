<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_folder = $_POST['id_folder'];
    $nama_arsip = $_POST['nama_arsip']; // Ini akan masuk ke judul_dokumen
    
    $nama_file = $_FILES['berkas']['name']; // Ini akan masuk ke file_path
    $tmp_file  = $_FILES['berkas']['tmp_name'];
    $direktori = "uploads/";

    if (!is_dir($direktori)) {
        mkdir($direktori, 0777, true);
    }

    if (move_uploaded_file($tmp_file, $direktori . $nama_file)) {
        // SESUAI STRUKTUR DATABASE KAMU:
        // id_arsip (AI), id_folder, judul_dokumen, file_path, tanggal_upload
        $tanggal = date("Y-m-d H:i:s");
        $query = "INSERT INTO arsip (id_folder, judul_dokumen, file_path, tanggal_upload) 
                  VALUES ('$id_folder', '$nama_arsip', '$nama_file', '$tanggal')";
        
        if(mysqli_query($conn, $query)){
            header("Location: view_folder.php?id=$id_folder&pesan=upload_berhasil");
        } else {
            die("Gagal simpan ke database: " . mysqli_error($conn));
        }
    } else {
        echo "Gagal upload ke folder. Periksa izin folder 'uploads'.";
    }
}
?>