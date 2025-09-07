<?php
require '../config/auth.php';
if (!isLoggedIn()) header("Location: login.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $user_id = $_SESSION['user_id'];
  $judul = $_POST['judul'];
  $isi = $_POST['isi'];
  $lokasi = $_POST['lokasi'];
  $tanggal = $_POST['tanggal'];
  
  if (buatPengaduan($user_id, $judul, $isi, $lokasi, $tanggal)) {
    header("Location: pengaduan.php?success=1");
    exit;
  } else {
    $error = "Gagal membuat pengaduan";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buat Pengaduan Baru</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #4e73df;
      --secondary-color: #6f42c1;
      --success-color: #1cc88a;
      --light-bg: #f8f9fc;
    }
    
    body {
      background-color: var(--light-bg);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .card {
      border-radius: 10px;
      box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
      border: none;
      margin-bottom: 2rem;
    }
    
    .card-header {
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
      color: white;
      border-radius: 10px 10px 0 0 !important;
      padding: 1.2rem 1.5rem;
    }
    
    .form-label {
      font-weight: 600;
      color: #4e4e4e;
      margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
      border-radius: 8px;
      padding: 0.75rem 1rem;
      border: 1px solid #ddd;
      transition: all 0.3s;
    }
    
    .form-control:focus, .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
    }
    
    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
      border: none;
      border-radius: 8px;
      padding: 0.75rem 2rem;
      font-weight: 600;
      transition: all 0.3s;
    }
    
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    
    .alert {
      border-radius: 8px;
      padding: 1rem 1.5rem;
    }
    
    .character-count {
      font-size: 0.85rem;
      color: #6c757d;
      text-align: right;
    }
    
    .page-title {
      color: #4e4e4e;
      font-weight: 700;
      margin-bottom: 1.5rem;
      padding-bottom: 0.5rem;
      border-bottom: 2px solid var(--primary-color);
      display: inline-block;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <h2 class="page-title"><i class="fas fa-plus-circle me-2"></i>Buat Pengaduan Baru</h2>
    
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Formulir Pengaduan</h5>
      </div>
      <div class="card-body p-4">
        <?php if (isset($error)): ?>
          <div class="alert alert-danger d-flex align-items-center" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <div><?= $error ?></div>
          </div>
        <?php endif; ?>
        
        <form method="POST" id="pengaduanForm">
          <div class="mb-4">
            <label class="form-label">Judul Pengaduan <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="judul" placeholder="Masukkan judul pengaduan" required maxlength="100">
            <div class="character-count"><span id="judulCount">0</span>/100 karakter</div>
          </div>
          
          <div class="mb-4">
            <label class="form-label">Isi Pengaduan <span class="text-danger">*</span></label>
            <textarea class="form-control" name="isi" rows="6" placeholder="Jelaskan secara detail pengaduan Anda" required maxlength="1000"></textarea>
            <div class="character-count"><span id="isiCount">0</span>/1000 karakter</div>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-4">
              <label class="form-label">Lokasi Kejadian</label>
              <input type="text" class="form-control" name="lokasi" placeholder="Contoh: Jalan Merdeka No. 10">
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Tanggal Kejadian <span class="text-danger">*</span></label>
              <input type="date" class="form-control" name="tanggal" required>
            </div>
          </div>
          
          <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
            <a href="pengaduan.php" class="btn btn-outline-secondary">
              <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-paper-plane me-2"></i>Kirim Pengaduan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Bootstrap & jQuery JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    $(document).ready(function() {
      // Karakter counter untuk judul
      $('input[name="judul"]').on('input', function() {
        $('#judulCount').text($(this).val().length);
      });
      
      // Karakter counter untuk isi
      $('textarea[name="isi"]').on('input', function() {
        $('#isiCount').text($(this).val().length);
      });
      
      // Set tanggal default ke hari ini
      const today = new Date().toISOString().split('T')[0];
      $('input[name="tanggal"]').val(today);
      
      // Validasi form sebelum submit
      $('#pengaduanForm').on('submit', function() {
        const judul = $('input[name="judul"]').val().trim();
        const isi = $('textarea[name="isi"]').val().trim();
        
        if (judul === '' || isi === '') {
          alert('Judul dan isi pengaduan harus diisi!');
          return false;
        }
        
        return true;
      });
    });
  </script>
</body>
</html>