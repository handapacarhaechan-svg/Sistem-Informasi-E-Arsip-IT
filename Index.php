<?php 
session_start();

// 1. PROTEKSI LOGIN
if(!isset($_SESSION['status']) || $_SESSION['status'] != "login"){
    header("location:login.php?pesan=belum_login");
    exit();
}

include 'koneksi.php'; 

// 2. DEFINISI VARIABEL AGAR TIDAK ERROR
$tipe = isset($_GET['tipe']) ? mysqli_real_escape_string($conn, $_GET['tipe']) : '';
$kat  = isset($_GET['kat']) ? mysqli_real_escape_string($conn, $_GET['kat']) : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | E-Arsip Prodi TI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; }
        .sidebar { background: white; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: none; }
        .nav-link { color: #555; border-radius: 10px; margin-bottom: 5px; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { background: #0d6efd; color: white !important; shadow: 0 4px 10px rgba(13, 110, 253, 0.3); }
        .folder-card { border: none; border-radius: 20px; transition: 0.3s; background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .folder-card:hover { transform: translateY(-8px); box-shadow: 0 12px 25px rgba(0,0,0,0.1); }
        .btn-delete { position: absolute; top: 15px; right: 15px; color: #dc3545; opacity: 0.5; transition: 0.3s; }
        .folder-card:hover .btn-delete { opacity: 1; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-primary shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php"><i class="fas fa-university me-2"></i>E-ARSIP TI</a>
        <div class="d-flex align-items-center text-white">
            <span class="me-3 d-none d-md-block">Halo, <strong>Admin</strong></span>
            <a href="logout.php" class="btn btn-sm btn-danger rounded-pill px-3">Keluar</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="card sidebar p-3 mb-4">
                <h6 class="text-muted fw-bold mb-3 px-2 small text-uppercase">Menu Utama</h6>
                <nav class="nav flex-column">
                    <a class="nav-link <?= ($tipe == '' && $kat == '') ? 'active' : '' ?>" href="index.php">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                    <a class="nav-link <?= ($tipe == 'Dosen') ? 'active' : '' ?>" href="index.php?tipe=Dosen">
                        <i class="fas fa-chalkboard-teacher me-2"></i> Arsip Dosen
                    </a>
                    <hr>
                    <h6 class="text-muted fw-bold mb-2 px-2 small text-uppercase">Angkatan</h6>
                    <?php 
                    $list_angkatan = ['2025', '2024', '2023', 'Alumni']; 
                    foreach($list_angkatan as $thn): ?>
                        <a class="nav-link <?= ($kat == $thn) ? 'active' : '' ?>" href="index.php?tipe=Mahasiswa&kat=<?= $thn ?>">
                            <i class="fas fa-user-graduate me-2"></i> <?= $thn ?>
                        </a>
                    <?php endforeach; ?>
                </nav>
            </div>
        </div>

        <div class="col-md-9">
            <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'hapus_berhasil'): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i> Folder berhasil dihapus!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold m-0">
                    <?php 
                        if($tipe == 'Dosen') echo "Arsip Dosen";
                        elseif($kat != '') echo "Mahasiswa Angkatan " . $kat;
                        else echo "Semua Arsip Digital";
                    ?>
                </h4>
                <button class="btn btn-primary rounded-pill px-4 shadow" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="fas fa-plus-circle me-1"></i> Tambah Folder
                </button>
            </div>

            <div class="row">
                <?php
                // Logika Query Database
                $query = "SELECT * FROM folders WHERE 1=1";
                if($tipe != '') $query .= " AND tipe_arsip='$tipe'";
                if($kat != '') $query .= " AND kategori_anggota='$kat'";
                $query .= " ORDER BY id_folder DESC";
                
                $sql = mysqli_query($conn, $query);
                
                if(mysqli_num_rows($sql) > 0) {
                    while($row = mysqli_fetch_array($sql)) { 
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card folder-card h-100 p-3 position-relative">
                        <a href="hapus_folder.php?id=<?= $row['id_folder'] ?>" 
                           class="btn-delete" 
                           onclick="return confirm('Hapus folder ini?')">
                            <i class="fas fa-trash-alt"></i>
                        </a>

                        <div class="text-center py-3">
                            <i class="fas fa-folder fa-4x text-warning mb-3"></i>
                            <h6 class="fw-bold text-truncate px-2"><?= $row['nama_folder'] ?></h6>
                            <p class="text-muted small mb-3">Semester <?= $row['semester'] ?> | <?= $row['tahun_akademik'] ?></p>
                            <a href="view_folder.php?id=<?= $row['id_folder'] ?>" class="btn btn-outline-primary btn-sm w-100 rounded-pill">Buka Folder</a>
                        </div>
                    </div>
                </div>
                <?php 
                    } 
                } else {
                    echo "
                    <div class='col-12 text-center py-5'>
                        <i class='fas fa-folder-open fa-3x text-light mb-3'></i>
                        <p class='text-muted'>Belum ada folder di kategori ini.</p>
                    </div>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="proses_folder.php" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-primary text-white border-0" style="border-radius: 20px 20px 0 0;">
                <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle me-2"></i>Buat Folder Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-bold">NAMA FOLDER</label>
                    <input type="text" name="nama_folder" class="form-control rounded-3" placeholder="Contoh: Skripsi 2024" required>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">TIPE</label>
                        <select name="tipe_arsip" class="form-select rounded-3">
                            <option value="Mahasiswa">Mahasiswa</option>
                            <option value="Dosen">Dosen</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">KATEGORI</label>
                        <select name="kategori_anggota" class="form-select rounded-3">
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                            <option value="Alumni">Alumni</option>
                        </select>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">SEMESTER</label>
                        <select name="semester" class="form-select rounded-3">
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">TAHUN</label>
                        <input type="text" name="tahun_akademik" class="form-control rounded-3" placeholder="2024/2025">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4">
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold rounded-pill shadow">SIMPAN DATA</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>