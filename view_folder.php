<?php 
session_start();
if(!isset($_SESSION['status']) || $_SESSION['status'] != "login"){
    header("location:login.php");
    exit();
}
include 'koneksi.php'; 

$id_folder = mysqli_real_escape_string($conn, $_GET['id']);
$query_folder = mysqli_query($conn, "SELECT * FROM folders WHERE id_folder = '$id_folder'");
$data_folder = mysqli_fetch_array($query_folder);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Isi Folder | E-Arsip TI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; }
        .content-card { background: white; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: none; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-primary shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php"><i class="fas fa-arrow-left me-2"></i> KEMBALI</a>
    </div>
</nav>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold m-0"><i class="fas fa-folder-open text-warning me-2"></i><?= $data_folder['nama_folder']; ?></h2>
            <small class="text-muted">Kategori: <?= $data_folder['kategori_anggota']; ?> | Semester <?= $data_folder['semester']; ?></small>
        </div>
        <button class="btn btn-primary rounded-pill px-4 shadow" data-bs-toggle="modal" data-bs-target="#modalUpload">
            <i class="fas fa-upload me-1"></i> Unggah File Baru
        </button>
    </div>

    <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'upload_berhasil'): ?>
        <div class="alert alert-success rounded-4 border-0 shadow-sm">File berhasil diunggah!</div>
    <?php endif; ?>

    <div class="card content-card p-4">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Judul Dokumen</th>
                    <th>Nama File Fisik</th>
                    <th>Tgl Upload</th>
                    <th width="15%" class="text-center">Aksi</th>
                </tr>
            </thead>
           <tbody>
    <?php
    $no = 1;
    $sql_file = mysqli_query($conn, "SELECT * FROM arsip WHERE id_folder = '$id_folder'");
    while($row = mysqli_fetch_array($sql_file)) {
        $path_fisik = "Uploads/" . $row['file_path'];
        $ekstensi = strtolower(pathinfo($row['file_path'], PATHINFO_EXTENSION));
        // URL untuk Google Viewer (Hanya jalan kalau sudah online)
        $url_hosting = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/" . $path_fisik;
    ?>
    <tr>
        <td><?= $no++; ?></td>
        <td class="fw-bold"><?= $row['judul_dokumen']; ?></td>
        <td class="small text-muted"><?= $row['file_path']; ?></td>
        <td class="small"><?= date('d/m/Y', strtotime($row['tanggal_upload'])); ?></td>
        <td class="text-center">
            <div class="btn-group">
                <button class="btn btn-info btn-sm text-white rounded-pill px-3 me-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#previewModal<?= $row['id_arsip']; ?>"><i class="fas fa-eye"></i></button>
                <a href="download.php?file=<?= urlencode($row['file_path']); ?>" class="btn btn-success btn-sm rounded-pill px-3 me-1 shadow-sm"><i class="fas fa-download"></i></a>
                <a href="hapus_file.php?id=<?= $row['id_arsip']; ?>&folder=<?= $id_folder; ?>" class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm" onclick="return confirm('Hapus file?')"><i class="fas fa-trash"></i></a>
            </div>
        </td>
    </tr>

    <div class="modal fade" id="previewModal<?= $row['id_arsip']; ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="height: 85vh;">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">Preview: <?= $row['judul_dokumen']; ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0 bg-dark">
                    <?php if (in_array($ekstensi, ['pdf', 'jpg', 'jpeg', 'png', 'gif'])): ?>
                        <iframe src="<?= $path_fisik; ?>" width="100%" height="100%" style="border:none;"></iframe>
                    <?php elseif (in_array($ekstensi, ['doc', 'docx', 'xls', 'xlsx'])): ?>
                        <?php if($_SERVER['HTTP_HOST'] == 'localhost'): ?>
                            <div class="text-center text-white mt-5 p-5">
                                <i class="fas fa-laptop-code fa-4x mb-3 text-warning"></i>
                                <h4>Mode Localhost Terdeteksi</h4>
                                <p>File <strong>.<?= $ekstensi ?></strong> hanya bisa di-preview jika sudah Online.<br>Silakan download untuk melihat isi file sekarang.</p>
                                <a href="<?= $path_fisik; ?>" class="btn btn-success btn-lg rounded-pill" download>Download File</a>
                            </div>
                        <?php else: ?>
                            <iframe src="https://docs.google.com/viewer?url=<?= urlencode($url_hosting); ?>&embedded=true" width="100%" height="100%" style="border:none;"></iframe>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalUpload" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="proses_upload.php" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold">Unggah Dokumen</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="id_folder" value="<?= $id_folder; ?>">
                <div class="mb-3">
                    <label class="form-label fw-bold small">PILIH BERKAS</label>
                    <input type="file" name="berkas" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">JUDUL DOKUMEN</label>
                    <input type="text" name="nama_arsip" class="form-control" placeholder="Contoh: KHS Semester 1" required>
                </div>
            </div>
            <div class="modal-footer border-0 p-4">
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold rounded-pill shadow">MULAI UNGGAH</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>