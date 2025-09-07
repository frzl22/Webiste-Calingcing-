<?php
require '../config/auth.php';
if (!isAdmin()) header("Location: ../user/login.php");

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Handle status changes
  if (isset($_POST['antrian_id']) && isset($_POST['action'])) {
    $id = $_POST['antrian_id'];
    $action = $_POST['action'];
    
    if ($action == 'panggil') {
      $conn->query("UPDATE antrian SET status = 'dipanggil' WHERE id = $id");   
    } elseif ($action == 'selesai') {
      $conn->query("UPDATE antrian SET status = 'selesai' WHERE id = $id");
    } elseif ($action == 'batal') {
      $conn->query("UPDATE antrian SET status = 'dibatalkan' WHERE id = $id");
    }
  }
  
  // Handle creation of new manual queue
  if (isset($_POST['create_manual'])) {
    $nama = $conn->real_escape_string($_POST['nama']);
    $jenis_layanan = $conn->real_escape_string($_POST['jenis_layanan']);
    
    // Get the next queue number for today
    $result = $conn->query("SELECT MAX(nomor_antrian) as max_nomor FROM antrian WHERE tanggal = CURDATE()");
    $row = $result->fetch_assoc();
    $next_number = $row['max_nomor'] ? $row['max_nomor'] + 1 : 1;
    
    // Insert the new manual queue
    $conn->query("INSERT INTO antrian (nomor_antrian, user_id, nama, jenis_layanan, status, tanggal, created_at, is_manual) 
                 VALUES ($next_number, 0, '$nama', '$jenis_layanan', 'menunggu', CURDATE(), NOW(), 1)");
  }
}

// Get today's queues
$antrian_hari_ini = $conn->query("SELECT a.*, u.nama as nama_user FROM antrian a 
                                 LEFT JOIN users u ON a.user_id = u.id
                                 WHERE a.tanggal = CURDATE()
                                 ORDER BY 
                                   CASE 
                                     WHEN a.status = 'menunggu' THEN 1
                                     WHEN a.status = 'dipanggil' THEN 2
                                     WHEN a.status = 'selesai' THEN 3
                                     WHEN a.status = 'dibatalkan' THEN 4
                                   END,
                                   a.created_at");

// Get statistics
$stats = $conn->query("SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'menunggu' THEN 1 ELSE 0 END) as menunggu,
    SUM(CASE WHEN status = 'dipanggil' THEN 1 ELSE 0 END) as dipanggil,
    SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai,
    SUM(CASE WHEN status = 'dibatalkan' THEN 1 ELSE 0 END) as dibatalkan
    FROM antrian WHERE tanggal = CURDATE()")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Antrian Manual</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            --secondary-gradient: linear-gradient(135deg, #ff6b6b 0%, #ff8e53 100%);
            --success-gradient: linear-gradient(135deg, #0ba360 0%, #3cba92 100%);
            --warning-gradient: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --dark-gradient: linear-gradient(135deg, #434343 0%, #000000 100%);
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .dashboard-container {
            padding: 20px;
            max-width: 1600px;
            margin: 0 auto;
        }
        
        .header {
            background: var(--primary-gradient);
            color: white;
            border-radius: 20px;
            padding: 25px 30px;
            margin-bottom: 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .header-content h1 {
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 2.2rem;
        }
        
        .header-content p {
            opacity: 0.9;
            margin-bottom: 0;
        }
        
        .header-actions {
            display: flex;
            gap: 15px;
        }
        
        .btn-header {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 50px;
            padding: 10px 20px;
            text-decoration: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-header:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 18px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--primary-gradient);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .stat-card.menunggu::before { background: var(--warning-gradient); }
        .stat-card.dipanggil::before { background: var(--info-gradient); }
        .stat-card.selesai::before { background: var(--success-gradient); }
        .stat-card.dibatalkan::before { background: var(--dark-gradient); }
        .stat-card.total::before { background: var(--primary-gradient); }
        
        .stat-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .stat-info h2 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        
        .stat-info p {
            color: #7f8c8d;
            font-weight: 500;
            margin-bottom: 0;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }
        
        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
        }
        
        .stat-icon.menunggu { background: var(--warning-gradient); }
        .stat-icon.dipanggil { background: var(--info-gradient); }
        .stat-icon.selesai { background: var(--success-gradient); }
        .stat-icon.dibatalkan { background: var(--dark-gradient); }
        .stat-icon.total { background: var(--primary-gradient); }
        
        .main-content {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        @media (max-width: 1200px) {
            .main-content {
                grid-template-columns: 1fr;
            }
        }
        
        .card {
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: none;
            overflow: hidden;
        }
        
        .card-header {
            background: var(--primary-gradient);
            color: white;
            padding: 20px 25px;
            border-bottom: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-header h3 {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .card-body {
            padding: 25px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-control {
            border-radius: 12px;
            padding: 12px 18px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
            border-color: #4299e1;
        }
        
        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            border-radius: 12px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(66, 153, 225, 0.4);
        }
        
        .table-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .table-header {
            background: var(--primary-gradient);
            color: white;
            padding: 20px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .table-header h3 {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .custom-table thead {
            background: #f8f9fa;
        }
        
        .custom-table th {
            padding: 18px 15px;
            font-weight: 600;
            color: #2c3e50;
            border-bottom: 2px solid #e9ecef;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        .custom-table td {
            padding: 16px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f2f6;
        }
        
        .custom-table tbody tr {
            transition: background-color 0.3s ease;
        }
        
        .custom-table tbody tr:hover {
            background-color: #f8f9ff;
        }
        
        .nomor-antrian {
            font-size: 1.3rem;
            font-weight: 800;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .badge {
            padding: 8px 14px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-menunggu {
            background: #fff3cd;
            color: #856404;
        }
        
        .badge-dipanggil {
            background: #cce5ff;
            color: #004085;
        }
        
        .badge-selesai {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-dibatalkan {
            background: #f8d7da;
            color: #721c24;
        }
        
        .btn-action {
            border-radius: 10px;
            padding: 8px 15px;
            font-size: 0.8rem;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-panggil {
            background: var(--info-gradient);
            color: white;
        }
        
        .btn-panggil:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        }
        
        .btn-selesai {
            background: var(--success-gradient);
            color: white;
        }
        
        .btn-selesai:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }
        
        .btn-batal {
            background: var(--dark-gradient);
            color: white;
        }
        
        .btn-batal:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #a0aec0;
        }
        
        .empty-state i {
            font-size: 4.5rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }
        
        .empty-state h4 {
            font-weight: 600;
            margin-bottom: 10px;
            color: #4a5568;
        }
        
        .empty-state p {
            max-width: 400px;
            margin: 0 auto;
        }
        
        .manual-badge {
            background: var(--secondary-gradient);
            color: white;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 0.7rem;
            margin-left: 8px;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }
            
            .header-actions {
                justify-content: center;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <h1><i class="fas fa-tasks me-2"></i>Manajemen Antrian Manual</h1>
                <p>Kelola antrian pelanggan secara manual - Administrator</p>
            </div>
            <div class="header-actions">
                <a href="antrian_monitor.php" target="_blank" class="btn-header">
                    <i class="fas fa-tv me-1"></i> Lihat Monitor
                </a>
                <a href="dashboard.php" class="btn-header">
                    <i class="fas fa-sign-out-alt me-1"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card total">
                <div class="stat-content">
                    <div class="stat-info">
                        <h2><?= $stats['total'] ?></h2>
                        <p>Total Antrian</p>
                    </div>
                    <div class="stat-icon total">
                        <i class="fas fa-list-alt"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card menunggu">
                <div class="stat-content">
                    <div class="stat-info">
                        <h2><?= $stats['menunggu'] ?></h2>
                        <p>Menunggu</p>
                    </div>
                    <div class="stat-icon menunggu">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card dipanggil">
                <div class="stat-content">
                    <div class="stat-info">
                        <h2><?= $stats['dipanggil'] ?></h2>
                        <p>Dipanggil</p>
                    </div>
                    <div class="stat-icon dipanggil">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card selesai">
                <div class="stat-content">
                    <div class="stat-info">
                        <h2><?= $stats['selesai'] ?></h2>
                        <p>Selesai</p>
                    </div>
                    <div class="stat-icon selesai">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card dibatalkan">
                <div class="stat-content">
                    <div class="stat-info">
                        <h2><?= $stats['dibatalkan'] ?></h2>
                        <p>Dibatalkan</p>
                    </div>
                    <div class="stat-icon dibatalkan">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Create Manual Queue Form -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-plus-circle"></i> Buat Antrian Manual</h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="form-group">
                            <label for="nama" class="form-label">Nama Pelanggan</label>
                            <input type="text" class="form-control" id="nama" name="nama" required placeholder="Masukkan nama pelanggan">
                        </div>
                        
                        <div class="form-group">
                            <label for="jenis_layanan" class="form-label">Jenis Layanan</label>
                            <select class="form-control" id="jenis_layanan" name="jenis_layanan" required>
                                <option value="">Pilih Jenis Layanan</option>
                                <option value="Konsultasi">Konsultasi</option>
                                <option value="Pembayaran">Pembayaran</option>
                                <option value="Pengaduan">Pengaduan</option>
                                <option value="Pendaftaran">Pendaftaran</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        
                        <button type="submit" name="create_manual" class="btn btn-primary">
                            <i class="fas fa-ticket-alt me-2"></i> Buat Antrian
                        </button>
                    </form>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-bolt"></i> Aksi Cepat</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="stat-card menunggu">
                                <div class="stat-content">
                                    <div class="stat-info">
                                        <h4>Panggil Berikutnya</h4>
                                        <p>Panggil antrian selanjutnya</p>
                                    </div>
                                    <div class="stat-icon menunggu">
                                        <i class="fas fa-bullhorn"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="stat-card selesai">
                                <div class="stat-content">
                                    <div class="stat-info">
                                        <h4>Reset Hari Ini</h4>
                                        <p>Reset semua antrian</p>
                                    </div>
                                    <div class="stat-icon selesai">
                                        <i class="fas fa-sync-alt"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary">
                            <i class="fas fa-print me-2"></i> Cetak Laporan Harian
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Queue List Table -->
        <div class="table-container">
            <div class="table-header">
                <h3><i class="fas fa-list-ol"></i> Daftar Antrian Hari Ini</h3>
                <span class="badge bg-light text-dark">Total: <?= $stats['total'] ?> Antrian</span>
            </div>
            
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>No Antrian</th>
                            <th>Nama Pelanggan</th>
                            <th>Jenis Layanan</th>
                            <th>Status</th>
                            <th>Waktu Daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($antrian_hari_ini->num_rows > 0): ?>
                            <?php while ($a = $antrian_hari_ini->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <div class="nomor-antrian">
                                            <i class="fas fa-ticket-alt"></i>
                                            <?= $a['nomor_antrian'] ?>
                                            <?php if ($a['is_manual']): ?>
                                                <span class="manual-badge">Manual</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($a['nama'] ?? $a['nama_user']) ?></strong>
                                    </td>
                                    <td>
                                        <i class="fas fa-cog me-2 text-primary"></i>
                                        <?= htmlspecialchars($a['jenis_layanan']) ?>
                                    </td>
                                    <td>
                                        <?php
                                        $status_class = '';
                                        $status_icon = '';
                                        switch ($a['status']) {
                                            case 'menunggu':
                                                $status_class = 'badge-menunggu';
                                                $status_icon = 'fas fa-clock';
                                                break;
                                            case 'dipanggil':
                                                $status_class = 'badge-dipanggil';
                                                $status_icon = 'fas fa-bullhorn';
                                                break;
                                            case 'selesai':
                                                $status_class = 'badge-selesai';
                                                $status_icon = 'fas fa-check-circle';
                                                break;
                                            case 'dibatalkan':
                                                $status_class = 'badge-dibatalkan';
                                                $status_icon = 'fas fa-times-circle';
                                                break;
                                        }
                                        ?>
                                        <span class="badge <?= $status_class ?>">
                                            <i class="<?= $status_icon ?> me-1"></i>
                                            <?= ucfirst($a['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <i class="fas fa-clock me-2 text-muted"></i>
                                        <?= date('H:i', strtotime($a['created_at'])) ?>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <?php if ($a['status'] == 'menunggu'): ?>
                                                <form method="POST" style="display:inline">
                                                    <input type="hidden" name="antrian_id" value="<?= $a['id'] ?>">
                                                    <input type="hidden" name="action" value="panggil">
                                                    <button type="submit" class="btn-action btn-panggil">
                                                        <i class="fas fa-bullhorn me-1"></i> Panggil
                                                    </button>
                                                </form>
                                                <form method="POST" style="display:inline">
                                                    <input type="hidden" name="antrian_id" value="<?= $a['id'] ?>">
                                                    <input type="hidden" name="action" value="batal">
                                                    <button type="submit" class="btn-action btn-batal">
                                                        <i class="fas fa-times me-1"></i> Batal
                                                    </button>
                                                </form>
                                            <?php elseif ($a['status'] == 'dipanggil'): ?>
                                                <form method="POST" style="display:inline">
                                                    <input type="hidden" name="antrian_id" value="<?= $a['id'] ?>">
                                                    <input type="hidden" name="action" value="selesai">
                                                    <button type="submit" class="btn-action btn-selesai">
                                                        <i class="fas fa-check me-1"></i> Selesai
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-muted">
                                                    <i class="fas fa-check-circle me-1"></i> Selesai
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="fas fa-clipboard-list"></i>
                                        <h4>Belum Ada Antrian</h4>
                                        <p>Silakan buat antrian manual menggunakan form di atas.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Auto refresh halaman setiap 30 detik
    setInterval(function() {
        location.reload();
    }, 30000);

    // Konfirmasi sebelum mengubah status
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form[method="POST"]');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const action = this.querySelector('input[name="action"]').value;
                let message = '';
                
                if (action === 'panggil') {
                    message = 'Apakah Anda yakin ingin memanggil antrian ini?';
                } else if (action === 'selesai') {
                    message = 'Apakah Anda yakin antrian ini sudah selesai?';
                } else if (action === 'batal') {
                    message = 'Apakah Anda yakin ingin membatalkan antrian ini?';
                }
                
                if (message && !confirm(message)) {
                    e.preventDefault();
                }
            });
        });
    });
    </script>
</body>
</html>