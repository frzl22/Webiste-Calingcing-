<?php
require '../config/auth.php';
if (!isLoggedIn()) header("Location: login.php");

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$pengaduan = getPengaduanDetail($id, $user_id);

if (!$pengaduan) {
  header("Location: pengaduan.php");
  exit;
}

// Fungsi untuk mendapatkan class badge berdasarkan status
function getStatusBadgeClass($status) {
  switch ($status) {
    case 'selesai':
      return 'success';
    case 'diproses':
      return 'warning';
    case 'ditolak':
      return 'danger';
    default:
      return 'primary';
  }
}

// Fungsi untuk mendapatkan icon berdasarkan status
function getStatusIcon($status) {
  switch ($status) {
    case 'selesai':
      return 'fa-check-circle';
    case 'diproses':
      return 'fa-cog';
    case 'ditolak':
      return 'fa-times-circle';
    default:
      return 'fa-clock';
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Pengaduan #<?= $pengaduan['id'] ?></title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #4e73df;
      --secondary-color: #6f42c1;
      --success-color: #1cc88a;
      --warning-color: #f6c23e;
      --danger-color: #e74a3b;
      --light-bg: #f8f9fc;
    }
    
    body {
      background-color: var(--light-bg);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .card {
      border-radius: 12px;
      box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
      border: none;
      margin-bottom: 2rem;
      overflow: hidden;
    }
    
    .card-header {
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
      color: white;
      border-radius: 12px 12px 0 0 !important;
      padding: 1.5rem;
    }
    
    .status-badge {
      font-size: 0.9rem;
      padding: 0.5rem 1rem;
      border-radius: 50px;
      font-weight: 600;
    }
    
    .detail-card {
      border-radius: 10px;
      box-shadow: 0 0.1rem 0.5rem rgba(0, 0, 0, 0.1);
      margin-bottom: 1.5rem;
      border: none;
    }
    
    .detail-card-header {
      background-color: #f8f9fa;
      padding: 0.75rem 1.25rem;
      border-bottom: 1px solid #e3e6f0;
      font-weight: 600;
      color: #4e4e4e;
      border-radius: 10px 10px 0 0 !important;
    }
    
    .detail-card-body {
      padding: 1.25rem;
    }
    
    .btn-action {
      border-radius: 8px;
      padding: 0.6rem 1.5rem;
      font-weight: 600;
      transition: all 0.3s;
    }
    
    .btn-action:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    
    .info-label {
      font-weight: 600;
      color: #5a5c69;
      padding-right: 1rem;
      width: 180px;
    }
    
    .info-content {
      color: #4e4e4e;
      line-height: 1.6;
    }
    
    .page-title {
      color: #4e4e4e;
      font-weight: 700;
      margin-bottom: 1.5rem;
      padding-bottom: 0.5rem;
      border-bottom: 2px solid var(--primary-color);
      display: inline-block;
    }
    
    .timeline {
      position: relative;
      padding-left: 2rem;
      margin: 1.5rem 0;
    }
    
    .timeline::before {
      content: '';
      position: absolute;
      left: 10px;
      top: 0;
      bottom: 0;
      width: 2px;
      background-color: #e3e6f0;
    }
    
    .timeline-item {
      position: relative;
      margin-bottom: 1.5rem;
    }
    
    .timeline-marker {
      position: absolute;
      left: -2rem;
      top: 0;
      width: 20px;
      height: 20px;
      border-radius: 50%;
      background-color: var(--primary-color);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 0.7rem;
    }
    
    .timeline-content {
      background-color: white;
      padding: 1rem;
      border-radius: 8px;
      box-shadow: 0 0.1rem 0.3rem rgba(0, 0, 0, 0.1);
    }
    
    @media (max-width: 768px) {
      .info-label {
        width: 100%;
        padding-right: 0;
        margin-bottom: 0.5rem;
      }
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <h2 class="page-title"><i class="fas fa-clipboard-list me-2"></i>Detail Pengaduan</h2>
    
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Pengaduan #<?= $pengaduan['id'] ?></h5>
        <span class="status-badge bg-<?= getStatusBadgeClass($pengaduan['status']) ?>">
          <i class="fas <?= getStatusIcon($pengaduan['status']) ?> me-1"></i>
          <?= ucfirst($pengaduan['status']) ?>
        </span>
      </div>
      
      <div class="card-body p-4">
        <!-- Informasi Utama -->
        <div class="row mb-4">
          <div class="col-md-6 mb-3">
            <div class="detail-card">
              <div class="detail-card-header">
                <i class="fas fa-heading me-2"></i>Judul Pengaduan
              </div>
              <div class="detail-card-body">
                <h5><?= htmlspecialchars($pengaduan['judul']) ?></h5>
              </div>
            </div>
          </div>
          
          <div class="col-md-6 mb-3">
            <div class="detail-card">
              <div class="detail-card-header">
                <i class="fas fa-map-marker-alt me-2"></i>Lokasi Kejadian
              </div>
              <div class="detail-card-body">
                <p class="mb-0"><?= $pengaduan['lokasi'] ? htmlspecialchars($pengaduan['lokasi']) : '<span class="text-muted">Tidak ditentukan</span>' ?></p>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Isi Pengaduan -->
        <div class="detail-card mb-4">
          <div class="detail-card-header">
            <i class="fas fa-align-left me-2"></i>Isi Pengaduan
          </div>
          <div class="detail-card-body">
            <p class="mb-0"><?= nl2br(htmlspecialchars($pengaduan['isi'])) ?></p>
          </div>
        </div>
        
        <!-- Informasi Waktu -->
        <div class="row mb-4">
          <div class="col-md-6 mb-3">
            <div class="detail-card">
              <div class="detail-card-header">
                <i class="fas fa-calendar-day me-2"></i>Tanggal Kejadian
              </div>
              <div class="detail-card-body">
                <p class="mb-0"><?= date('d F Y', strtotime($pengaduan['tanggal'])) ?></p>
              </div>
            </div>
          </div>
          
          <div class="col-md-6 mb-3">
            <div class="detail-card">
              <div class="detail-card-header">
                <i class="fas fa-paper-plane me-2"></i>Tanggal Dilaporkan
              </div>
              <div class="detail-card-body">
                <p class="mb-0"><?= date('d F Y H:i', strtotime($pengaduan['created_at'])) ?></p>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Timeline Status (jika ada informasi tambahan) -->
        <div class="detail-card mb-4">
          <div class="detail-card-header">
            <i class="fas fa-history me-2"></i>Status Timeline
          </div>
          <div class="detail-card-body">
            <div class="timeline">
              <div class="timeline-item">
                <div class="timeline-marker">
                  <i class="fas fa-plus"></i>
                </div>
                <div class="timeline-content">
                  <h6 class="mb-1">Pengaduan Dibuat</h6>
                  <p class="text-muted mb-0"><?= date('d F Y H:i', strtotime($pengaduan['created_at'])) ?></p>
                </div>
              </div>
              
              <div class="timeline-item">
                <div class="timeline-marker">
                  <i class="fas fa-check"></i>
                </div>
                <div class="timeline-content">
                  <h6 class="mb-1">Pengaduan <?= ucfirst($pengaduan['status']) ?></h6>
                  <p class="text-muted mb-0">Status terakhir: <?= ucfirst($pengaduan['status']) ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Tombol Aksi -->
        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
          <a href="pengaduan.php" class="btn btn-secondary btn-action">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
          </a>
          
          <?php if ($pengaduan['status'] == 'diterima'): ?>
            <a href="edit_pengaduan.php?id=<?= $pengaduan['id'] ?>" class="btn btn-warning btn-action">
              <i class="fas fa-edit me-2"></i>Edit Pengaduan
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap & jQuery JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>