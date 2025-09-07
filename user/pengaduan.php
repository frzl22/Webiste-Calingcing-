<?php 
require '../config/auth.php';
if (!isLoggedIn()) header("Location: login.php");

$user_id = $_SESSION['user_id'];
$pengaduan = getPengaduanByUser($user_id);

function getStatusClass($status) {
    switch($status) {
        case 'selesai': return 'status-selesai';
        case 'diproses': return 'status-diproses';
        default: return 'status-diterima';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pengaduan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3b82f6;
            --secondary-color: #1e40af;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --light-bg: #f8fafc;
            --dark-text: #1e293b;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            padding: 2rem 0;
        }

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .dashboard-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            border: none;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .card-header-modern {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.5rem 2rem;
            border: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-title {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }

        .header-title i {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.5rem;
            border-radius: 8px;
            margin-right: 0.75rem;
        }

        .btn-create {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-create:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .card-body-modern {
            padding: 2rem;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            border-left: 4px solid var(--primary-color);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .stat-label {
            color: #64748b;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .table-modern {
            margin: 0;
        }

        .table-modern thead {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        }

        .table-modern th {
            border: none;
            padding: 1rem 1.5rem;
            font-weight: 600;
            color: var(--dark-text);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .table-modern td {
            border: none;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .table-modern tbody tr:hover {
            background: #f8fafc;
            transition: all 0.2s ease;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .status-diterima {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-color);
        }

        .status-diproses {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .status-selesai {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-action {
            padding: 0.5rem;
            border-radius: 8px;
            border: none;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-view {
            background: rgba(6, 182, 212, 0.1);
            color: var(--info-color);
        }

        .btn-view:hover {
            background: var(--info-color);
            color: white;
            transform: scale(1.1);
        }

        .btn-edit {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .btn-edit:hover {
            background: var(--warning-color);
            color: white;
            transform: scale(1.1);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 12px;
            border: 2px dashed #cbd5e1;
        }

        .empty-icon {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }

        .empty-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 0.5rem;
        }

        .empty-text {
            color: #64748b;
            margin-bottom: 1.5rem;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            color: white;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            color: white;
        }

        .complaint-id {
            font-family: 'Monaco', 'Menlo', monospace;
            background: #f1f5f9;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.875rem;
            color: var(--dark-text);
        }

        .date-text {
            color: #64748b;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .card-header-modern {
                flex-direction: column;
                align-items: stretch;
                text-align: center;
            }

            .action-buttons {
                justify-content: center;
            }

            .table-responsive {
                border-radius: 12px;
            }

            .stats-row {
                grid-template-columns: 1fr;
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
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
    <div class="main-container">
        <div class="dashboard-card fade-in">
            <div class="card-header-modern">
                <h5 class="header-title">
                    <i class="fas fa-clipboard-list"></i>
                    Dashboard Pengaduan Masyarakat
                </h5>
                <a href="buat_pengaduan.php" class="btn-create">
                    <i class="fas fa-plus"></i>
                    Buat Pengaduan Baru
                </a>
            </div>
            
            <div class="card-body-modern">
                <!-- Statistics Row -->
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-number"><?= count($pengaduan) ?></div>
                        <div class="stat-label">Total Pengaduan</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= count(array_filter($pengaduan, fn($p) => $p['status'] == 'diproses')) ?></div>
                        <div class="stat-label">Sedang Diproses</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= count(array_filter($pengaduan, fn($p) => $p['status'] == 'selesai')) ?></div>
                        <div class="stat-label">Selesai</div>
                    </div>
                </div>

                <?php if (count($pengaduan) > 0): ?>
                    <div class="table-container">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th>ID Pengaduan</th>
                                    <th>Judul Pengaduan</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Status</th>
                                    <th style="text-align: center;">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pengaduan as $p): ?>
                                    <tr>
                                        <td>
                                            <span class="complaint-id">#<?= $p['id'] ?></span>
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($p['judul']) ?></strong>
                                        </td>
                                        <td>
                                            <span class="date-text">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                <?= date('d M Y', strtotime($p['tanggal'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-badge <?= getStatusClass($p['status']) ?>">
                                                <?= ucfirst($p['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="detail_pengaduan.php?id=<?= $p['id'] ?>" 
                                                   class="btn-action btn-view" 
                                                   title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if ($p['status'] == 'diterima'): ?>
                                                    <a href="edit_pengaduan.php?id=<?= $p['id'] ?>" 
                                                       class="btn-action btn-edit"
                                                       title="Edit Pengaduan">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <div class="empty-title">Belum Ada Pengaduan</div>
                        <div class="empty-text">
                            Anda belum memiliki pengaduan. Mulai dengan membuat pengaduan pertama Anda.
                        </div>
                        <a href="buat_pengaduan.php" class="btn-primary-custom">
                            <i class="fas fa-plus"></i>
                            Buat Pengaduan Pertama
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>