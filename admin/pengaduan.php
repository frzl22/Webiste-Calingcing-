<?php
require '../config/auth.php';
if (!isAdmin()) header("Location: ../user/login.php");

$pengaduan = getAllPengaduan();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pengaduan - Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --light-bg: #f8fafc;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --card-hover-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: #1f2937;
        }

        .main-container {
            padding: 2rem 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            color: white;
        }

        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .page-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            text-align: center;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--card-hover-shadow);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            color: white;
        }

        .stat-icon.pending { background: var(--info-color); }
        .stat-icon.processing { background: var(--warning-color); }
        .stat-icon.completed { background: var(--success-color); }
        .stat-icon.total { background: var(--primary-color); }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--secondary-color);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .main-card {
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            transition: box-shadow 0.2s ease;
        }

        .main-card:hover {
            box-shadow: var(--card-hover-shadow);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
            color: white;
            padding: 1.5rem 2rem;
            border: none;
        }

        .card-header h5 {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .card-header i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }

        .card-body {
            padding: 0;
        }

        .table-container {
            overflow-x: auto;
        }

        .table {
            margin: 0;
            font-size: 0.9rem;
        }

        .table thead th {
            background: var(--light-bg);
            border: none;
            color: var(--secondary-color);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 1rem;
        }

        .table tbody tr {
            border: none;
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-top: 1px solid #e5e7eb;
        }

        .table tbody tr:first-child td {
            border-top: none;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-badge.pending {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-badge.processing {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.completed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .btn-action {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .btn-detail {
            background: var(--info-color);
            color: white;
        }

        .btn-detail:hover {
            background: #0891b2;
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--secondary-color);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .complaint-title {
            font-weight: 600;
            color: #1f2937;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .complaint-date {
            color: var(--secondary-color);
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }
            
            .page-header h1 {
                font-size: 2rem;
            }
            
            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .table {
                font-size: 0.8rem;
            }
            
            .table thead th,
            .table tbody td {
                padding: 0.75rem 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="page-header">
            <h1><i class="fas fa-shield-alt me-3"></i>Dashboard Pengaduan</h1>
            <p>Kelola dan pantau semua pengaduan warga dengan mudah</p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-container">
            <?php
            $totalPengaduan = count($pengaduan);
            $pending = count(array_filter($pengaduan, fn($p) => $p['status'] == 'pending'));
            $diproses = count(array_filter($pengaduan, fn($p) => $p['status'] == 'diproses'));
            $selesai = count(array_filter($pengaduan, fn($p) => $p['status'] == 'selesai'));
            ?>
            
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="stat-number"><?= $totalPengaduan ?></div>
                <div class="stat-label">Total Pengaduan</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon pending">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number"><?= $pending ?></div>
                <div class="stat-label">Menunggu</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon processing">
                    <i class="fas fa-cogs"></i>
                </div>
                <div class="stat-number"><?= $diproses ?></div>
                <div class="stat-label">Diproses</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon completed">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number"><?= $selesai ?></div>
                <div class="stat-label">Selesai</div>
            </div>
        </div>

        <!-- Main Table -->
        <div class="main-card">
            <div class="card-header">
                <h5><i class="fas fa-exclamation-triangle"></i>Daftar Pengaduan Warga</h5>
            </div>
            <div class="card-body">
                <?php if (empty($pengaduan)): ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h5>Belum Ada Pengaduan</h5>
                        <p>Tidak ada pengaduan yang masuk saat ini.</p>
                    </div>
                <?php else: ?>
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Warga</th>
                                    <th>Judul Pengaduan</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pengaduan as $p): ?>
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-primary">#<?= str_pad($p['id'], 4, '0', STR_PAD_LEFT) ?></span>
                                        </td>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">
                                                    <?= strtoupper(substr($p['user_nama'], 0, 1)) ?>
                                                </div>
                                                <span><?= htmlspecialchars($p['user_nama']) ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="complaint-title" title="<?= htmlspecialchars($p['judul']) ?>">
                                                <?= htmlspecialchars($p['judul']) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="complaint-date">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                <?= date('d/m/Y', strtotime($p['created_at'])) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-badge <?= 
                                                $p['status'] == 'selesai' ? 'completed' : 
                                                ($p['status'] == 'diproses' ? 'processing' : 'pending') 
                                            ?>">
                                                <?= ucfirst($p['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="detail_pengaduan.php?id=<?= $p['id'] ?>" class="btn-action btn-detail">
                                                <i class="fas fa-eye"></i>
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>