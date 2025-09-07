<?php
require '../config/auth.php';
if (!isAdmin()) header("Location: ../user/login.php");

// Hitung jumlah data
$total_warga = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetch_row()[0];
$total_pengaduan = $conn->query("SELECT COUNT(*) FROM pengaduan")->fetch_row()[0];
$total_antrian = $conn->query("SELECT COUNT(*) FROM antrian WHERE tanggal = CURDATE()")->fetch_row()[0];
$total_surat = $conn->query("SELECT COUNT(*) FROM pengajuan_surat")->fetch_row()[0];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Desa Calingcing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --info-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --dark-bg: #1a1d29;
            --sidebar-bg: #2d3748;
            --card-shadow: 0 10px 25px rgba(0,0,0,0.1);
            --border-radius: 15px;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .sidebar {
            background: var(--sidebar-bg);
            min-height: 100vh;
            box-shadow: 2px 0 15px rgba(0,0,0,0.1);
            border-radius: 0 20px 20px 0;
        }

        .sidebar .nav-link {
            color: #e2e8f0;
            margin-bottom: 8px;
            padding: 12px 16px;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar .nav-link:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .sidebar h4 {
            color: #fff;
            font-weight: 700;
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid rgba(255,255,255,0.1);
            margin-bottom: 30px;
        }

        .main-content {
            background: rgba(255,255,255,0.95);
            border-radius: 25px;
            margin: 15px;
            padding: 30px;
            box-shadow: var(--card-shadow);
            backdrop-filter: blur(10px);
        }

        .page-title {
            color: var(--dark-bg);
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: var(--primary-gradient);
            border-radius: 2px;
        }

        .card-counter {
            border-radius: var(--border-radius);
            border: none;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            margin-bottom: 25px;
        }

        .card-counter::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: inherit;
            opacity: 0.9;
            z-index: 1;
        }

        .card-counter * {
            position: relative;
            z-index: 2;
        }

        .card-counter:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .card-counter.bg-primary {
            background: var(--primary-gradient);
        }

        .card-counter.bg-success {
            background: var(--success-gradient);
        }

        .card-counter.bg-warning {
            background: var(--warning-gradient);
            color: #2d3748 !important;
        }

        .card-counter.bg-info {
            background: var(--info-gradient);
        }

        .card-counter h5 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .card-counter small {
            font-size: 0.9rem;
            font-weight: 500;
            opacity: 0.9;
        }

        .card-counter .bi {
            opacity: 0.3 !important;
            font-size: 3.5rem !important;
        }

        .activity-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            border: none;
            transition: all 0.3s ease;
        }

        .activity-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .activity-card .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 2px solid #dee2e6;
            border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
            padding: 15px 20px;
        }

        .activity-card .card-header h6 {
            color: var(--dark-bg);
            font-weight: 600;
            margin: 0;
            font-size: 1.1rem;
        }

        .list-group-item {
            border: none;
            border-radius: 10px !important;
            margin-bottom: 8px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .list-group-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }

        .badge {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 20px;
        }

        .alert {
            border: none;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            font-weight: 500;
        }

        .alert-info {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            color: #1565c0;
        }

        .stats-row {
            margin-bottom: 40px;
        }

        .activity-section {
            margin-top: 20px;
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .sidebar {
                border-radius: 0;
            }
            
            .main-content {
                margin: 10px;
                padding: 20px;
                border-radius: 15px;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .card-counter h5 {
                font-size: 2rem;
            }
        }

        /* Animation for loading */
        .fade-in {
            animation: fadeIn 0.6s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar p-0">
                <div class="p-4">
                    <h4 class="mb-4">
                        <i class="bi bi-house-gear me-2"></i>
                        Admin Desa
                    </h4>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard.php">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="warga.php">
                                <i class="bi bi-people me-2"></i> Data Warga
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pengaduan.php">
                                <i class="bi bi-exclamation-triangle me-2"></i> Pengaduan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="antrian.php">
                                <i class="bi bi-ticket-perforated me-2"></i> Antrian Online
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="antrian_offline.php">
                                <i class="bi bi-ticket-perforated me-2"></i> Antrian Offline
                            </a>
                        </li>
                        <li class="nav-item mt-4">
                            <a class="nav-link" href="../config/auth.php?logout=1">
                                <i class="bi bi-box-arrow-left me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 ms-sm-auto col-lg-10 p-0">
                <div class="main-content fade-in">
                    <h1 class="page-title">Dashboard Admin</h1>
                    
                    <!-- Statistik -->
                    <div class="row stats-row">
                        <div class="col-lg-3 col-md-6">
                            <div class="card card-counter bg-primary text-white p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0"><?= $total_warga ?></h5>
                                        <small>Warga Terdaftar</small>
                                    </div>
                                    <i class="bi bi-people"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card card-counter bg-success text-white p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0"><?= $total_pengaduan ?></h5>
                                        <small>Total Pengaduan</small>
                                    </div>
                                    <i class="bi bi-exclamation-triangle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card card-counter bg-warning text-dark p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0"><?= $total_antrian ?></h5>
                                        <small>Antrian Hari Ini</small>
                                    </div>
                                    <i class="bi bi-ticket-perforated"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card card-counter bg-info text-white p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0"><?= $total_surat ?></h5>
                                        <small>Pengajuan Surat</small>
                                    </div>
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Aktivitas Terbaru -->
                    <div class="row activity-section">
                        <div class="col-md-6">
                            <div class="card activity-card mb-4">
                                <div class="card-header">
                                    <h6><i class="bi bi-exclamation-triangle me-2"></i> Pengaduan Terbaru</h6>
                                </div>
                                <div class="card-body">
                                    <?php
                                    $pengaduan = $conn->query("SELECT p.*, u.nama FROM pengaduan p 
                                                              JOIN users u ON p.user_id = u.id 
                                                              ORDER BY created_at DESC LIMIT 5");
                                    if ($pengaduan->num_rows > 0): ?>
                                        <div class="list-group">
                                            <?php while ($p = $pengaduan->fetch_assoc()): ?>
                                                <a href="pengaduan.php?action=detail&id=<?= $p['id'] ?>" 
                                                   class="list-group-item list-group-item-action">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1"><?= htmlspecialchars($p['judul']) ?></h6>
                                                            <small class="text-muted">
                                                                Oleh: <?= htmlspecialchars($p['nama']) ?> | 
                                                                <?= date('d M Y', strtotime($p['created_at'])) ?>
                                                            </small>
                                                        </div>
                                                        <span class="badge bg-<?= 
                                                            $p['status'] == 'selesai' ? 'success' : 
                                                            ($p['status'] == 'diproses' ? 'warning' : 'primary')
                                                        ?>">
                                                            <?= ucfirst($p['status']) ?>
                                                        </span>
                                                    </div>
                                                </a>
                                            <?php endwhile; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle me-2"></i>
                                            Belum ada pengaduan
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card activity-card">
                                <div class="card-header">
                                    <h6><i class="bi bi-ticket-perforated me-2"></i> Antrian Hari Ini</h6>
                                </div>
                                <div class="card-body">
                                    <?php
                                    $antrian = $conn->query("SELECT a.*, u.nama FROM antrian a 
                                                            JOIN users u ON a.user_id = u.id
                                                            WHERE a.tanggal = CURDATE()
                                                            ORDER BY a.status, a.created_at LIMIT 5");
                                    if ($antrian->num_rows > 0): ?>
                                        <div class="list-group">
                                            <?php while ($a = $antrian->fetch_assoc()): ?>
                                                <a href="antrian.php?action=detail&id=<?= $a['id'] ?>" 
                                                   class="list-group-item list-group-item-action">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1"><?= $a['nomor_antrian'] ?> - <?= $a['jenis_layanan'] ?></h6>
                                                            <small class="text-muted">
                                                                <?= htmlspecialchars($a['nama']) ?> | 
                                                                <?= date('H:i', strtotime($a['created_at'])) ?>
                                                            </small>
                                                        </div>
                                                        <span class="badge bg-<?= 
                                                            $a['status'] == 'selesai' ? 'success' : 
                                                            ($a['status'] == 'dipanggil' ? 'warning' : 'secondary')
                                                        ?>">
                                                            <?= ucfirst($a['status']) ?>
                                                        </span>
                                                    </div>
                                                </a>
                                            <?php endwhile; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle me-2"></i>
                                            Belum ada antrian hari ini
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>