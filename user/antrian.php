<?php
require '../config/auth.php';
if (!isLoggedIn()) header("Location: login.php");

$user_id = $_SESSION['user_id'];
$antrian = getAntrianUser($user_id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $jenis_layanan = $_POST['jenis_layanan'];
  $nomor_antrian = buatAntrian($user_id, $jenis_layanan);
  
  if ($nomor_antrian) {
    $success = "Antrian berhasil dibuat! Nomor Antrian Anda: <strong>$nomor_antrian</strong>";
  } else {
    $error = "Gagal membuat antrian";
  }
}
?>

<style>
  .card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    overflow: hidden;
  }
  
  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
  }
  
  .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 20px;
    position: relative;
  }
  
  .card-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #ff6b6b, #ffa726, #42a5f5, #ab47bc);
    background-size: 400% 400%;
    animation: gradient 3s ease infinite;
  }
  
  @keyframes gradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }
  
  .card-header h5 {
    color: white;
    font-weight: 600;
    margin: 0;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
  }
  
  .sub-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
  }
  
  .sub-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
  }
  
  .sub-card-header {
    border-radius: 12px 12px 0 0;
    padding: 15px 20px;
    font-weight: 600;
    border: none;
  }
  
  .bg-primary-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  }
  
  .bg-info-gradient {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
  }
  
  .form-select {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    padding: 12px 15px;
    transition: all 0.3s ease;
  }
  
  .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    transform: scale(1.02);
  }
  
  .btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 10px;
    padding: 12px 20px;
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
  }
  
  .btn-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
  }
  
  .btn-primary:hover::before {
    left: 100%;
  }
  
  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
  }
  
  .alert {
    border: none;
    border-radius: 10px;
    padding: 15px 20px;
    margin-bottom: 25px;
  }
  
  .alert-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
  }
  
  .alert-danger {
    background: linear-gradient(135deg, #ff6b6b 0%, #ffa726 100%);
    color: white;
  }
  
  .alert-info {
    background: linear-gradient(135deg, #42a5f5 0%, #478ed1 100%);
    color: white;
  }
  
  .table {
    border-radius: 10px;
    overflow: hidden;
  }
  
  .table th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85em;
    letter-spacing: 1px;
  }
  
  .table td {
    border: none;
    padding: 15px 12px;
    vertical-align: middle;
  }
  
  .table tbody tr {
    transition: all 0.3s ease;
  }
  
  .table tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
  }
  
  .badge {
    padding: 8px 12px;
    font-size: 0.75em;
    font-weight: 600;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  
  .bg-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
  }
  
  .bg-warning {
    background: linear-gradient(135deg, #ffa726 0%, #ffb74d 100%) !important;
  }
  
  .bg-secondary {
    background: linear-gradient(135deg, #90a4ae 0%, #78909c 100%) !important;
  }
  
  .icon-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    margin-right: 10px;
  }
  
  .main-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 30px 0;
  }
  
  .content-wrapper {
    background: white;
    border-radius: 20px;
    padding: 30px;
    margin: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.1);
  }
  
  @media (max-width: 768px) {
    .content-wrapper {
      margin: 10px;
      padding: 20px;
    }
  }
</style>

<div class="main-container">
  <div class="container">
    <div class="content-wrapper">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5>
            <div class="icon-wrapper">
              <i class="fas fa-ticket-alt"></i>
            </div>
            Antrian Online
          </h5>
        </div>
        
        <div class="card-body" style="padding: 30px;">
          <?php if (isset($success)): ?>
            <div class="alert alert-success">
              <i class="fas fa-check-circle me-2"></i>
              <?= $success ?>
            </div>
          <?php elseif (isset($error)): ?>
            <div class="alert alert-danger">
              <i class="fas fa-exclamation-circle me-2"></i>
              <?= $error ?>
            </div>
          <?php endif; ?>
          
          <div class="row g-4">
            <div class="col-md-6">
              <div class="card sub-card mb-4">
                <div class="sub-card-header bg-primary-gradient text-white">
                  <h6 class="mb-0">
                    <i class="fas fa-plus-circle me-2"></i>
                    Ambil Antrian Baru
                  </h6>
                </div>
                <div class="card-body" style="padding: 25px;">
                  <form method="POST">
                    <div class="mb-4">
                      <label class="form-label fw-bold text-muted">Jenis Layanan</label>
                      <select name="jenis_layanan" class="form-select" required>
                        <option value="">Pilih Layanan</option>
                        <option value="KTP">🆔 Pembuatan KTP</option>
                        <option value="KK">👨‍👩‍👧‍👦 Pembuatan KK</option>
                        <option value="Surat Usaha">🧾 Surat Pengantar Nikah</option>
                        <option value="Surat Usaha">🧾 Surat Pengantar Cerai</option>
                        <option value="Surat Usaha">💼 Surat Keterangan Usaha</option>
                        <option value="Surat Domisili">🏠 Surat Domisili</option>
                        <option value="Surat Usaha">🪦 Surat Kematian</option>
                        <option value="Lainnya">Lainnya</option>
                      </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                      <i class="fas fa-ticket-alt me-2"></i>
                      Ambil Nomor Antrian
                    </button>
                  </form>
                </div>
              </div>
            </div>
            
            <div class="col-md-6">
              <div class="card sub-card">
                <div class="sub-card-header bg-info-gradient text-white">
                  <h6 class="mb-0">
                    <i class="fas fa-history me-2"></i>
                    Riwayat Antrian Anda
                  </h6>
                </div>
                <div class="card-body" style="padding: 25px;">
                  <?php if (count($antrian) > 0): ?>
                    <div class="table-responsive">
                      <table class="table table-sm">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Layanan</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($antrian as $a): ?>
                            <tr>
                              <td><strong><?= $a['nomor_antrian'] ?></strong></td>
                              <td><?= $a['jenis_layanan'] ?></td>
                              <td>
                                <span class="badge bg-<?= 
                                  $a['status'] == 'selesai' ? 'success' : 
                                  ($a['status'] == 'dipanggil' ? 'warning' : 'secondary')
                                ?>">
                                  <?php if($a['status'] == 'selesai'): ?>
                                    <i class="fas fa-check me-1"></i>
                                  <?php elseif($a['status'] == 'dipanggil'): ?>
                                    <i class="fas fa-bell me-1"></i>
                                  <?php else: ?>
                                    <i class="fas fa-clock me-1"></i>
                                  <?php endif; ?>
                                  <?= ucfirst($a['status']) ?>
                                </span>
                              </td>
                              <td><?= date('d/m/Y', strtotime($a['tanggal'])) ?></td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  <?php else: ?>
                    <div class="alert alert-info">
                      <i class="fas fa-info-circle me-2"></i>
                      Anda belum memiliki antrian. Ambil nomor antrian sekarang!
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
</div>