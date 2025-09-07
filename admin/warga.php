<?php
require '../config/auth.php';
if (!isAdmin()) header("Location: ../user/login.php");

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['tambah_warga'])) {
        // Handle tambah warga
        $nik = $_POST['nik'];
        $nama = $_POST['nama'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO users (nik, nama, password, role) VALUES (?, ?, ?, 'user')");
        $stmt->bind_param("sss", $nik, $nama, $password);
        
        if ($stmt->execute()) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    alert('Data warga berhasil ditambahkan!');
                    window.location.href = window.location.pathname;
                });
            </script>";
        } else {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    alert('Error: " . $stmt->error . "');
                });
            </script>";
        }
    } 
    elseif (isset($_POST['edit_warga'])) {
        // Handle edit warga
        $id = $_POST['id'];
        $nik = $_POST['nik'];
        $nama = $_POST['nama'];
        
        $stmt = $conn->prepare("UPDATE users SET nik=?, nama=? WHERE id=? AND role='user'");
        $stmt->bind_param("ssi", $nik, $nama, $id);
        
        if ($stmt->execute()) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    alert('Data warga berhasil diupdate!');
                    window.location.href = window.location.pathname;
                });
            </script>";
        } else {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    alert('Error: " . $stmt->error . "');
                });
            </script>";
        }
    }
}

if (isset($_GET['hapus'])) {
    // Handle hapus warga
    $id = $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role = 'user'");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                alert('Data warga berhasil dihapus!');
                window.location.href = window.location.pathname;
            });
        </script>";
    } else {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                alert('Error: " . $stmt->error . "');
            });
        </script>";
    }
}

// Ambil data warga
$warga = $conn->query("SELECT * FROM users WHERE role = 'user' ORDER BY nama");

// Ambil statistik
$totalWarga = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'user'")->fetch_assoc()['total'];
$wargaBaru = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'user' AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())")->fetch_assoc()['total'];
$totalAktif = $totalWarga; // Assuming all users are active
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Data Warga</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #f9ca24 0%, #f0932b 100%);
            --danger-gradient: linear-gradient(135deg, #eb4d4b 0%, #6c5ce7 100%);
            --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            --card-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            --hover-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px 0;
        }

        .container {
            max-width: 1200px;
        }

        .main-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .main-card:hover {
            box-shadow: var(--hover-shadow);
            transform: translateY(-5px);
        }

        .card-header {
            background: var(--primary-gradient);
            color: white;
            padding: 25px 30px;
            border-radius: 25px 25px 0 0;
            border-bottom: none;
        }

        .card-header h5 {
            font-weight: 600;
            font-size: 1.5rem;
            margin: 0;
        }

        .btn-gradient {
            background: var(--success-gradient);
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .btn-gradient:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
            color: white;
        }

        .btn-sm.btn-warning {
            background: var(--warning-gradient);
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-sm.btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            color: white;
        }

        .btn-sm.btn-danger {
            background: var(--danger-gradient);
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-sm.btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            color: white;
        }

        .table-container {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .table {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .table thead th {
            background: var(--dark-gradient);
            color: white;
            font-weight: 600;
            padding: 15px;
            border: none;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            transform: scale(1.02);
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-color: #e9ecef;
        }

        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(20px);
        }

        .modal-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 20px 25px;
            border-bottom: none;
        }

        .modal-title {
            font-weight: 600;
            font-size: 1.3rem;
        }

        .modal-body {
            padding: 30px 25px;
        }

        .form-control {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            transform: translateY(-2px);
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .btn-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: white;
            opacity: 0.8;
        }

        .btn-close:hover {
            opacity: 1;
        }

        .modal-footer {
            padding: 20px 25px;
            border-top: 1px solid #e9ecef;
        }

        .btn-secondary {
            background: #6c757d;
            border: none;
            border-radius: 12px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            border-radius: 12px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .badge {
            font-size: 0.8rem;
            padding: 8px 12px;
            border-radius: 10px;
            font-weight: 600;
        }

        .page-title {
            text-align: center;
            margin-bottom: 30px;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .stats-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            display: block;
        }

        .stats-label {
            font-size: 1rem;
            opacity: 0.9;
            margin-top: 5px;
        }

        .avatar-circle {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.9rem;
        }

        .form-text {
            color: #6c757d;
            font-size: 0.8rem;
            margin-top: 5px;
        }

        .modal-lg {
            max-width: 600px;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 10px;
            }
            
            .card-header {
                padding: 20px 15px;
            }
            
            .table-container {
                margin: 10px;
                padding: 15px;
            }
            
            .btn-gradient {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-title animate__animated animate__fadeInDown">
            <h1><i class="bi bi-people-fill me-3"></i>Manajemen Data Warga</h1>
            <p class="lead">Sistem Informasi Warga Desa</p>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stats-card animate__animated animate__fadeInLeft">
                    <span class="stats-number"><?= $totalWarga ?></span>
                    <div class="stats-label">Total Warga</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card animate__animated animate__fadeInUp">
                    <span class="stats-number"><?= $wargaBaru ?></span>
                    <div class="stats-label">Warga Baru Bulan Ini</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card animate__animated animate__fadeInRight">
                    <span class="stats-number"><?= $totalAktif ?></span>
                    <div class="stats-label">Status Aktif</div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="main-card animate__animated animate__fadeInUp">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="bi bi-people me-2"></i> Data Warga</h5>
                <button class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#tambahWargaModal">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Warga
                </button>
            </div>
            
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><i class="bi bi-hash me-1"></i>No</th>
                                <th><i class="bi bi-card-text me-1"></i>NIK</th>
                                <th><i class="bi bi-person me-1"></i>Nama</th>
                                <th><i class="bi bi-calendar me-1"></i>Tanggal Daftar</th>
                                <th><i class="bi bi-gear me-1"></i>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            if ($warga->num_rows > 0):
                                while ($row = $warga->fetch_assoc()): 
                            ?>
                                <tr>
                                    <td><span class="badge bg-primary"><?= $no++ ?></span></td>
                                    <td><?= htmlspecialchars($row['nik']) ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-2">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                            <?= htmlspecialchars($row['nama']) ?>
                                        </div>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning me-2" data-bs-toggle="modal" 
                                            data-bs-target="#editWargaModal" 
                                            data-id="<?= $row['id'] ?>"
                                            data-nik="<?= htmlspecialchars($row['nik']) ?>"
                                            data-nama="<?= htmlspecialchars($row['nama']) ?>"
                                            title="Edit Data">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" 
                                            onclick="confirmDelete(<?= $row['id'] ?>, '<?= htmlspecialchars($row['nama']) ?>')"
                                            title="Hapus Data">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <i class="bi bi-people"></i>
                                            <h5>Belum ada data warga</h5>
                                            <p>Silakan tambah data warga baru dengan menekan tombol "Tambah Warga"</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Warga -->
    <div class="modal fade" id="tambahWargaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" id="tambahWargaForm">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-person-plus me-2"></i>Tambah Warga Baru
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-card-text me-1"></i>NIK
                                    </label>
                                    <input type="text" class="form-control" name="nik" required 
                                           pattern="[0-9]{16}" maxlength="16" 
                                           placeholder="Masukkan 16 digit NIK">
                                    <div class="form-text">NIK harus 16 digit angka</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-person me-1"></i>Nama Lengkap
                                    </label>
                                    <input type="text" class="form-control" name="nama" required
                                           placeholder="Masukkan nama lengkap">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="bi bi-lock me-1"></i>Password
                            </label>
                            <input type="password" class="form-control" name="password" required
                                   placeholder="Masukkan password" minlength="6">
                            <div class="form-text">Password minimal 6 karakter</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </button>
                        <button type="submit" name="tambah_warga" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Warga -->
    <div class="modal fade" id="editWargaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" id="editWargaForm">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-pencil me-2"></i>Edit Data Warga
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-card-text me-1"></i>NIK
                                    </label>
                                    <input type="text" class="form-control" name="nik" id="edit_nik" required
                                           pattern="[0-9]{16}" maxlength="16">
                                    <div class="form-text">NIK harus 16 digit angka</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-person me-1"></i>Nama Lengkap
                                    </label>
                                    <input type="text" class="form-control" name="nama" id="edit_nama" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </button>
                        <button type="submit" name="edit_warga" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Enhanced JavaScript functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Modal edit functionality
            const editModal = document.getElementById('editWargaModal');
            editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                document.getElementById('edit_id').value = button.getAttribute('data-id');
                document.getElementById('edit_nik').value = button.getAttribute('data-nik');
                document.getElementById('edit_nama').value = button.getAttribute('data-nama');
            });

            // Form validation
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(event) {
                    const nikInput = form.querySelector('input[name="nik"]');
                    if (nikInput && nikInput.value.length !== 16) {
                        event.preventDefault();
                        alert('NIK harus 16 digit angka!');
                        return false;
                    }
                    
                    const passwordInput = form.querySelector('input[name="password"]');
                    if (passwordInput && passwordInput.value.length < 6) {
                        event.preventDefault();
                        alert('Password minimal 6 karakter!');
                        return false;
                    }
                });
            });

            // Add smooth scrolling
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });

        // Enhanced delete confirmation
        function confirmDelete(id, nama) {
            if (confirm(`Apakah Anda yakin ingin menghapus data warga "${nama}"?\n\nTindakan ini tidak dapat dibatalkan.`)) {
                window.location.href = `?hapus=${id}`;
            }
        }

        // Add input formatting for NIK
        document.querySelectorAll('input[name="nik"]').forEach(input => {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length > 16) {
                    this.value = this.value.slice(0, 16);
                }
            });
        });

        // Add auto-capitalize for names
        document.querySelectorAll('input[name="nama"]').forEach(input => {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());
            });
        });
    </script>
</body>
</html>