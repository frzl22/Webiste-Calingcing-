<?php 
require '../config/auth.php';
if (!isLoggedIn()) {
  header("Location: login.php");
  exit;
}

// Ambil data user
global $conn;
$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();

// Ambil data pengajuan surat
$pengajuan = $conn->query("SELECT * FROM pengajuan_surat WHERE user_id = $user_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Warga - Desa Calingcing</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #667eea;
      --primary-dark: #5a67d8;
      --secondary: #764ba2;
      --accent: #f093fb;
      --success: #10b981;
      --warning: #f59e0b;
      --danger: #ef4444;
      --info: #3b82f6;
      --light: #f8fafc;
      --dark: #1e293b;
      --glass: rgba(255, 255, 255, 0.1);
      --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    * {
      font-family: 'Inter', sans-serif;
    }

    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      margin: 0;
    }

    .sidebar {
      background: linear-gradient(180deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0.05) 100%);
      backdrop-filter: blur(10px);
      border-right: 1px solid rgba(255,255,255,0.2);
      min-height: 100vh;
      position: relative;
      overflow: hidden;
    }

    .sidebar::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.05) 50%, transparent 70%);
      animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
      0% { transform: translateX(-100%); }
      100% { transform: translateX(100%); }
    }

    .sidebar h4 {
      color: white;
      font-weight: 700;
      text-shadow: 0 2px 4px rgba(0,0,0,0.3);
      position: relative;
      z-index: 1;
    }

    .nav-link {
      color: rgba(255,255,255,0.9) !important;
      border-radius: 12px;
      margin-bottom: 8px;
      padding: 12px 16px;
      transition: all 0.3s ease;
      font-weight: 500;
      position: relative;
      z-index: 1;
    }

    .nav-link:hover {
      background: rgba(255,255,255,0.15);
      transform: translateX(5px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .nav-link.active {
      background: linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0.1));
      border: 1px solid rgba(255,255,255,0.2);
      color: white !important;
    }

    .main-content {
      background: var(--light);
      min-height: 100vh;
      padding: 2rem;
    }

    .welcome-header {
      background: linear-gradient(135deg, white 0%, rgba(255,255,255,0.8) 100%);
      border-radius: 20px;
      padding: 2rem;
      margin-bottom: 2rem;
      box-shadow: var(--shadow-lg);
      border: 1px solid rgba(255,255,255,0.5);
    }

    .welcome-header h2 {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    .card {
      background: rgba(255,255,255,0.9);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255,255,255,0.2);
      border-radius: 20px;
      box-shadow: var(--shadow-lg);
      transition: all 0.3s ease;
      overflow: hidden;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .stats-card {
      background: linear-gradient(135deg, white 0%, rgba(255,255,255,0.8) 100%);
      border-radius: 16px;
      padding: 1.5rem;
      text-align: center;
      transition: all 0.3s ease;
      border: 1px solid rgba(255,255,255,0.3);
      position: relative;
      overflow: hidden;
    }

    .stats-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--primary), var(--accent));
    }

    .stats-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }

    .stats-icon {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1rem;
      font-size: 1.5rem;
      color: white;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .stats-number {
      font-size: 2rem;
      font-weight: 700;
      color: var(--dark);
      margin-bottom: 0.5rem;
    }

    .stats-label {
      color: #64748b;
      font-weight: 500;
      font-size: 0.9rem;
    }

    .profile-card {
      background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.8) 100%);
      border-radius: 20px;
      padding: 2rem;
      margin-bottom: 2rem;
      box-shadow: var(--shadow-lg);
      border: 1px solid rgba(255,255,255,0.3);
    }

    .profile-avatar {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--primary), var(--accent));
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      color: white;
      margin-bottom: 1rem;
      box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    .table-container {
      background: rgba(255,255,255,0.95);
      border-radius: 20px;
      padding: 2rem;
      box-shadow: var(--shadow-lg);
      border: 1px solid rgba(255,255,255,0.3);
    }

    .table {
      margin-bottom: 0;
    }

    .table th {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: white;
      font-weight: 600;
      border: none;
      padding: 1rem;
      font-size: 0.9rem;
    }

    .table td {
      padding: 1rem;
      vertical-align: middle;
      border-color: rgba(0,0,0,0.1);
    }

    .table tbody tr:hover {
      background: rgba(102, 126, 234, 0.05);
      transform: scale(1.01);
      transition: all 0.2s ease;
    }

    .status-badge {
      padding: 0.5rem 1rem;
      border-radius: 50px;
      font-weight: 600;
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .badge-pending {
      background: linear-gradient(135deg, #6b7280, #9ca3af);
      color: white;
    }

    .badge-diproses {
      background: linear-gradient(135deg, #f59e0b, #fbbf24);
      color: white;
    }

    .badge-selesai {
      background: linear-gradient(135deg, #10b981, #34d399);
      color: white;
    }

    .badge-ditolak {
      background: linear-gradient(135deg, #ef4444, #f87171);
      color: white;
    }

    .btn {
      border-radius: 10px;
      font-weight: 500;
      transition: all 0.3s ease;
      border: none;
      padding: 0.5rem 1rem;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-info {
      background: linear-gradient(135deg, var(--info), #60a5fa);
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-warning {
      background: linear-gradient(135deg, var(--warning), #fbbf24);
      box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .btn-danger {
      background: linear-gradient(135deg, var(--danger), #f87171);
      box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-sm {
      padding: 0.375rem 0.75rem;
      font-size: 0.875rem;
      border-radius: 8px;
    }

    .alert {
      border-radius: 15px;
      border: none;
      padding: 1.5rem;
      box-shadow: var(--shadow);
    }

    .alert-info {
      background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(147, 197, 253, 0.1));
      color: var(--info);
      border-left: 4px solid var(--info);
    }

    .floating-action {
      position: fixed;
      bottom: 2rem;
      right: 2rem;
      z-index: 1000;
    }

    .floating-btn {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      text-decoration: none;
      box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
      transition: all 0.3s ease;
    }

    .floating-btn:hover {
      transform: scale(1.1);
      box-shadow: 0 12px 35px rgba(102, 126, 234, 0.5);
      color: white;
    }

    @media (max-width: 768px) {
      .main-content {
        padding: 1rem;
      }
      
      .stats-card {
        margin-bottom: 1rem;
      }
      
      .profile-card {
        padding: 1.5rem;
      }
    }

    .loading-shimmer {
      background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
      background-size: 200% 100%;
      animation: shimmer 2s infinite;
    }
  </style>
</head>
<body>
  <div class="container-fluid p-0">
    <div class="row g-0">
      <!-- Sidebar -->
      <div class="col-md-3 col-lg-2 sidebar p-4">
        <h4 class="mb-4">
          <i class="bi bi-gem me-2"></i>
          Desa Calingcing
        </h4>
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link active" href="#">
              <i class="bi bi-house-door me-2"></i> Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pengaduan.php">
              <i class="bi bi-chat-left-text me-2"></i> Pengaduan
            </a>
          </li>
          <li class="nav-item">
  <a class="nav-link" href="antrian.php">
    <i class="fas fa-ticket-alt me-2"></i> Antrian Online
  </a>
</li>
          <li class="nav-item mt-4">
            <a href="../config/auth.php?logout=1" class="nav-link" 
               onclick="return confirm('Yakin ingin logout?')">
              <i class="bi bi-box-arrow-left me-2"></i> Logout
            </a>
          </li>
        </ul>
      </div>

      <!-- Main Content -->
      <div class="col-md-9 col-lg-10 main-content">
        <!-- Welcome Header -->
        <div class="welcome-header">
          <h2>Selamat Datang, <?= htmlspecialchars($user['nama']) ?>!</h2>
          <p class="text-muted mb-0">Kelola pengajuan surat dan layanan desa dengan mudah</p>
        </div>

        <!-- Profile & Stats -->
        <div class="row mb-4">
          <div class="col-md-6">
            <div class="profile-card">
              <div class="d-flex align-items-center">
                <div class="profile-avatar">
                  <i class="bi bi-person-circle"></i>
                </div>
                <div class="ms-3">
                  <h5 class="mb-1"><?= htmlspecialchars($user['nama']) ?></h5>
                  <p class="text-muted mb-0">NIK: <?= htmlspecialchars($user['nik']) ?></p>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="row">
              <div class="col-4">
                <div class="stats-card">
                  <div class="stats-icon">
                    <i class="bi bi-file-text"></i>
                  </div>
                  <div class="stats-number"><?= $pengajuan->num_rows ?></div>
                  <div class="stats-label">Total Pengajuan</div>
                </div>
              </div>
              <div class="col-4">
                <div class="stats-card">
                  <div class="stats-icon" style="background: linear-gradient(135deg, var(--success), #34d399);">
                    <i class="bi bi-check-circle"></i>
                  </div>
                  <div class="stats-number"><?= mysqli_num_rows($conn->query("SELECT id FROM pengajuan_surat WHERE user_id = $user_id AND status = 'selesai'")) ?></div>
                  <div class="stats-label">Selesai</div>
                </div>
              </div>
              <div class="col-4">
                <div class="stats-card">
                  <div class="stats-icon" style="background: linear-gradient(135deg, var(--warning), #fbbf24);">
                    <i class="bi bi-hourglass-split"></i>
                  </div>
                  <div class="stats-number"><?= mysqli_num_rows($conn->query("SELECT id FROM pengajuan_surat WHERE user_id = $user_id AND status = 'pending'")) ?></div>
                  <div class="stats-label">Pending</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Add smooth scrolling and animations
    document.addEventListener('DOMContentLoaded', function() {
      // Animate cards on scroll
      const cards = document.querySelectorAll('.card, .stats-card');
      
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
          }
        });
      }, {
        threshold: 0.1
      });
      
      cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease';
        observer.observe(card);
      });
    });
  </script>
</body>
</html>