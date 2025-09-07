<?php
require '../config/auth.php';
if (!isAdmin()) header("Location: ../user/login.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id = $_POST['antrian_id'];
  $action = $_POST['action'];
  
  if ($action == 'panggil') {
    $conn->query("UPDATE antrian SET status = 'dipanggil' WHERE id = $id");
  } elseif ($action == 'selesai') {
    $conn->query("UPDATE antrian SET status = 'selesai' WHERE id = $id");
  }
}

$antrian_hari_ini = $conn->query("SELECT a.*, u.nama FROM antrian a 
                                 JOIN users u ON a.user_id = u.id
                                 WHERE a.tanggal = CURDATE()
                                 ORDER BY a.status, a.created_at");

// Hitung statistik
$total_hari_ini = $antrian_hari_ini->num_rows;
$stats = $conn->query("SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'menunggu' THEN 1 ELSE 0 END) as menunggu,
    SUM(CASE WHEN status = 'dipanggil' THEN 1 ELSE 0 END) as dipanggil,
    SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai
    FROM antrian WHERE tanggal = CURDATE()")->fetch_assoc();
?>

<style>
.queue-management {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px 0;
}

.stats-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border: none;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-item {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 25px;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    transition: transform 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-5px);
}

.stat-number {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.main-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border: none;
    overflow: hidden;
}

.card-header-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 25px 30px;
    border-bottom: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header-custom h5 {
    margin: 0;
    font-size: 1.4rem;
    font-weight: 600;
}

.btn-monitor {
    background: rgba(255,255,255,0.2);
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
    border-radius: 25px;
    padding: 10px 20px;
    text-decoration: none;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.btn-monitor:hover {
    background: rgba(255,255,255,0.3);
    color: white;
    transform: translateY(-2px);
}

.table-container {
    padding: 30px;
}

.custom-table {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.custom-table thead {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.custom-table th {
    padding: 18px 15px;
    font-weight: 600;
    color: #495057;
    border: none;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.custom-table td {
    padding: 15px;
    vertical-align: middle;
    border-bottom: 1px solid #f8f9fa;
}

.custom-table tr:hover {
    background-color: #f8f9ff;
    transition: background-color 0.3s ease;
}

.nomor-antrian {
    font-size: 1.2rem;
    font-weight: bold;
    color: #667eea;
}

.badge-custom {
    padding: 8px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: capitalize;
}

.badge-menunggu {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
}

.badge-dipanggil {
    background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
    color: white;
}

.badge-selesai {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.btn-action {
    border-radius: 20px;
    padding: 8px 16px;
    font-size: 0.85rem;
    font-weight: 500;
    border: none;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-panggil {
    background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
    color: white;
}

.btn-panggil:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
}

.btn-selesai {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.btn-selesai:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.5;
}

.page-title {
    text-align: center;
    margin-bottom: 30px;
    color: white;
}

.page-title h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 10px;
}

.page-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .card-header-custom {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .custom-table {
        font-size: 0.9rem;
    }
    
    .page-title h2 {
        font-size: 2rem;
    }
}
</style>

<div class="queue-management">
    <div class="container">
        <div class="page-title">
            <h2><i class="fas fa-tasks me-3"></i>Manajemen Antrian Online</h2>
            <p class="page-subtitle">Kelola antrian pelanggan dengan mudah dan efisien</p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number"><?= $stats['total'] ?></div>
                <div class="stat-label">Total Hari Ini</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= $stats['menunggu'] ?></div>
                <div class="stat-label">Menunggu</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= $stats['dipanggil'] ?></div>
                <div class="stat-label">Dipanggil</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= $stats['selesai'] ?></div>
                <div class="stat-label">Selesai</div>
            </div>
        </div>

        <!-- Main Table Card -->
        <div class="main-card">
            <div class="card-header-custom">
                <h5><i class="fas fa-list-ul me-2"></i>Daftar Antrian Hari Ini</h5>
                <a href="antrian_monitor.php" target="_blank" class="btn-monitor">
                    <i class="fas fa-tv me-2"></i>Lihat Monitor
                </a>
            </div>
            
            <div class="table-container">
                <?php if ($total_hari_ini > 0): ?>
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
                                <?php 
                                $antrian_hari_ini->data_seek(0); // Reset pointer
                                while ($a = $antrian_hari_ini->fetch_assoc()): 
                                ?>
                                    <tr>
                                        <td>
                                            <span class="nomor-antrian"><?= $a['nomor_antrian'] ?></span>
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($a['nama']) ?></strong>
                                        </td>
                                        <td>
                                            <i class="fas fa-cog me-2 text-primary"></i>
                                            <?= htmlspecialchars($a['jenis_layanan']) ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-custom badge-<?= $a['status'] ?>">
                                                <?php
                                                $status_icons = [
                                                    'menunggu' => 'fas fa-clock',
                                                    'dipanggil' => 'fas fa-bullhorn',
                                                    'selesai' => 'fas fa-check-circle'
                                                ];
                                                ?>
                                                <i class="<?= $status_icons[$a['status']] ?> me-1"></i>
                                                <?= ucfirst($a['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <i class="fas fa-clock me-2 text-muted"></i>
                                            <?= date('H:i', strtotime($a['created_at'])) ?>
                                        </td>
                                        <td>
                                            <?php if ($a['status'] == 'menunggu'): ?>
                                                <form method="POST" style="display:inline">
                                                    <input type="hidden" name="antrian_id" value="<?= $a['id'] ?>">
                                                    <input type="hidden" name="action" value="panggil">
                                                    <button type="submit" class="btn btn-action btn-panggil">
                                                        <i class="fas fa-bullhorn me-1"></i> Panggil
                                                    </button>
                                                </form>
                                            <?php elseif ($a['status'] == 'dipanggil'): ?>
                                                <form method="POST" style="display:inline">
                                                    <input type="hidden" name="antrian_id" value="<?= $a['id'] ?>">
                                                    <input type="hidden" name="action" value="selesai">
                                                    <button type="submit" class="btn btn-action btn-selesai">
                                                        <i class="fas fa-check me-1"></i> Selesai
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-muted">
                                                    <i class="fas fa-check-circle me-1"></i> Selesai
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-clipboard-list"></i>
                        <h4>Belum Ada Antrian</h4>
                        <p>Tidak ada antrian yang terdaftar untuk hari ini.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

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
            }
            
            if (message && !confirm(message)) {
                e.preventDefault();
            }
        });
    });
});
</script>