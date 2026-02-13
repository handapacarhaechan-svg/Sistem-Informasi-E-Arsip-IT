<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Arsip Admin TI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f0f2f5; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-box { width: 400px; background: white; padding: 30px; border-radius: 15px; shadow: 0 4px 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="login-box">
    <div class="text-center mb-4">
        <i class="fas fa-university fa-3x text-primary mb-2"></i>
        <h4 class="fw-bold">E-Arsip Admin TI</h4>
    </div>

    <?php 
    if(isset($_GET['pesan'])){
        if($_GET['pesan'] == "gagal"){
            echo "<div class='alert alert-danger py-2 small text-center'>Username atau Password Salah!</div>";
        } else if($_GET['pesan'] == "logout"){
            echo "<div class='alert alert-success py-2 small text-center'>Berhasil Logout</div>";
        } else if($_GET['pesan'] == "belum_login"){
            echo "<div class='alert alert-warning py-2 small text-center'>Silahkan Login Terlebih Dahulu</div>";
        }
    }
    ?>

    <form action="proses_login.php" method="POST">
    <div class="mb-3">
        <label class="small fw-bold">Username</label>
        <input type="text" name="username" class="form-control" placeholder="Masukkan Username" required autocomplete="off">
    </div>
    <div class="mb-4">
        <label class="small fw-bold">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required>
    </div>
    
    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-danger py-2 small text-center">Akses Ditolak: Kredensial tidak valid.</div>
    <?php endif; ?>

    <button type="submit" class="btn btn-primary w-100 fw-bold py-2">MASUK</button>
</form>
</div>

</body>
</html>