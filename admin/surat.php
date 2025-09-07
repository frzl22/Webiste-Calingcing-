<?php
require '../config/auth.php';
if (!isAdmin()) {
    header("Location: ../user/login.php");
    exit;
}

// Handle tambah pengajuan surat
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_surat'])) {
    $user_id = $_POST['user_id'];
    $jenis_surat = $_POST['jenis_surat'];
    $keterangan = $_POST['keterangan'];
    $status = 'pending';
    
    $stmt = $conn->prepare("INSERT INTO pengajuan_surat (user_id, jenis_surat, keterangan, status, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("isss", $user_id, $jenis_surat, $keterangan, $status);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'Pengajuan surat berhasil ditambahkan!';
    } else {
        $_SESSION['error_message'] = 'Gagal menambahkan pengajuan surat! Error: ' . $conn->error;
    }
    $stmt->close();
    header("Location: surat.php");
    exit;
}

// Handle update surat
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_surat'])) {
    $id = $_POST['id'];
    $user_id = $_POST['user_id'];
    $jenis_surat = $_POST['jenis_surat'];
    $keterangan = $_POST['keterangan'];
    $status = $_POST['status'];
    $admin_id = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("UPDATE pengajuan_surat SET user_id=?, jenis_surat=?, keterangan=?, status=?, admin_id=? WHERE id=?");
    $stmt->bind_param("isssii", $user_id, $jenis_surat, $keterangan, $status, $admin_id, $id);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'Pengajuan surat berhasil diupdate!';
    } else {
        $_SESSION['error_message'] = 'Gagal mengupdate pengajuan surat! Error: ' . $conn->error;
    }
    $stmt->close();
    header("Location: surat.php");
    exit;
}

// Handle delete surat
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    $stmt = $conn->prepare("DELETE FROM pengajuan_surat WHERE id=?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'Pengajuan surat berhasil dihapus!';
    } else {
        $_SESSION['error_message'] = 'Gagal menghapus pengajuan surat! Error: ' . $conn->error;
    }
    $stmt->close();
    header("Location: surat.php");
    exit;
}

// Ambil data pengajuan surat
$query = "SELECT p.*, u.nama as user_nama, u.id as user_id FROM pengajuan_surat p 
          JOIN users u ON p.user_id = u.id 
          ORDER BY p.created_at DESC";
$surat = $conn->query($query);

if (!$surat) {
    die("Error: " . $conn->error);
}

// Ambil data user untuk dropdown
$users = $conn->query("SELECT id, nama FROM users WHERE role = 'user'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Pengajuan Surat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .admin-page {
            background-color: #f8f9fa;
            min-height: 100vh;
            padding: 20px;
        }
        .main-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 25px;
        }
        .stats-row {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            flex-wrap: wrap;
        }
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin: 10px;
            flex: 1;
            min-width: 200px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            border-left: 4px solid #667eea;
        }
        .table-container {
            padding: 20px;
        }
        .table th {
            background-color: #f8f9fa;
            border-top: none;
        }
        .btn-action {
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transition: all 0.3s;
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 50px;
            margin-bottom: 15px;
            color: #dee2e6;
        }
    </style>
</head>
<body>
    <div class="admin-page">
        <div class="container-fluid">
            <div class="main-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="bi bi-file-earmark-text"></i> Pengajuan Surat Warga</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahSuratModal">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Pengajuan
                    </button>
                </div>
                
                <!-- Notifikasi -->
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                        <?= $_SESSION['success_message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                        <?= $_SESSION['error_message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>
                
                <!-- Statistics Row -->
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-title text-muted">Total Pengajuan</div>
                        <div class="stat-value"><?= $surat->num_rows ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-title text-muted">Selesai</div>
                        <div class="stat-value"><?= getCountByStatus($conn, 'selesai') ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-title text-muted">Diproses</div>
                        <div class="stat-value"><?= getCountByStatus($conn, 'diproses') ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-title text-muted">Pending</div>
                        <div class="stat-value"><?= getCountByStatus($conn, 'pending') ?></div>
                    </div>
                </div>
                
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Warga</th>
                                    <th>Jenis Surat</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($surat->num_rows > 0): ?>
                                    <?php while ($row = $surat->fetch_assoc()): ?>
                                        <tr>
                                            <td><strong><?= $row['id'] ?></strong></td>
                                            <td><strong><?= htmlspecialchars($row['user_nama']) ?></strong></td>
                                            <td><span style="color: #667eea; font-weight: 500;"><?= ucfirst($row['jenis_surat']) ?></span></td>
                                            <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                                            <td>
                                                <span class="badge bg-<?= 
                                                    $row['status'] == 'selesai' ? 'success' : 
                                                    ($row['status'] == 'diproses' ? 'warning' : 'secondary')
                                                ?>">
                                                    <?= ucfirst($row['status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn-action me-2" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#detailSuratModal"
                                                    data-id="<?= $row['id'] ?>"
                                                    data-user-id="<?= $row['user_id'] ?>"
                                                    data-nama="<?= htmlspecialchars($row['user_nama']) ?>"
                                                    data-jenis="<?= htmlspecialchars($row['jenis_surat']) ?>"
                                                    data-status="<?= $row['status'] ?>"
                                                    data-tanggal="<?= date('d/m/Y', strtotime($row['created_at'])) ?>"
                                                    data-keterangan="<?= htmlspecialchars($row['keterangan']) ?>"
                                                    title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <a href="?delete=<?= $row['id'] ?>" class="btn-action" 
                                                    style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus pengajuan surat ini?')"
                                                    title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="empty-state">
                                            <i class="bi bi-inbox"></i>
                                            <h5>Tidak ada pengajuan surat</h5>
                                            <p>Belum ada pengajuan surat yang masuk</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Surat -->
    <div class="modal fade" id="tambahSuratModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-file-earmark-plus me-2"></i>
                            Tambah Pengajuan Surat
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Warga</label>
                            <select class="form-select" id="user_id" name="user_id" required>
                                <?php while ($user = $users->fetch_assoc()): ?>
                                    <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['nama']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jenis_surat" class="form-label">Jenis Surat</label>
                            <select class="form-select" id="jenis_surat" name="jenis_surat" required>
                                <option value="surat keterangan">Surat Keterangan</option>
                                <option value="surat pengantar">Surat Pengantar</option>
                                <option value="surat domisili">Surat Domisili</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" name="tambah_surat" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Surat -->
    <div class="modal fade" id="detailSuratModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" id="updateStatusForm">
                    <input type="hidden" name="id" id="surat_id">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-file-earmark-text me-2"></i>
                            Edit Pengajuan Surat
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Warga</strong></label>
                                <select name="user_id" class="form-select" id="surat_user_id" required>
                                    <?php 
                                    $users->data_seek(0); // Reset pointer result
                                    while ($user = $users->fetch_assoc()): ?>
                                        <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['nama']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Jenis Surat</strong></label>
                                <select name="jenis_surat" class="form-select" id="surat_jenis" required>
                                    <option value="surat keterangan">Surat Keterangan</option>
                                    <option value="surat pengantar">Surat Pengantar</option>
                                    <option value="surat domisili">Surat Domisili</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Tanggal Pengajuan</strong></label>
                                <div class="form-control-plaintext bg-light rounded p-3" id="surat_tanggal"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Status</strong></label>
                                <select name="status" class="form-select" id="surat_status" required>
                                    <option value="pending">Pending</option>
                                    <option value="diproses">Diproses</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label"><strong>Keterangan</strong></label>
                                <textarea name="keterangan" class="form-control" id="surat_keterangan" rows="3" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Tutup
                        </button>
                        <button type="submit" name="update_surat" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi modal detail
        var detailModal = document.getElementById('detailSuratModal');
        var bsDetailModal = new bootstrap.Modal(detailModal);
        
        // Handle ketika modal detail ditampilkan
        detailModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            
            // Isi data ke modal
            document.getElementById('surat_id').value = button.getAttribute('data-id');
            document.getElementById('surat_user_id').value = button.getAttribute('data-user-id');
            document.getElementById('surat_jenis').value = button.getAttribute('data-jenis');
            document.getElementById('surat_tanggal').textContent = button.getAttribute('data-tanggal');
            document.getElementById('surat_keterangan').value = button.getAttribute('data-keterangan');
            document.getElementById('surat_status').value = button.getAttribute('data-status');
        });
        
        // Handle form submission
        var updateForm = document.getElementById('updateStatusForm');
        if (updateForm) {
            updateForm.addEventListener('submit', function(e) {
                var submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
            });
        }
        
        // Handle form tambah submission
        var tambahForm = document.querySelector('#tambahSuratModal form');
        if (tambahForm) {
            tambahForm.addEventListener('submit', function(e) {
                var submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
            });
        }
    });
    </script>
</body>
</html>

<?php
// Fungsi helper untuk menghitung jumlah berdasarkan status
function getCountByStatus($conn, $status) {
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM pengajuan_surat WHERE status = ?");
    $stmt->bind_param("s", $status);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['total'];
}
?>