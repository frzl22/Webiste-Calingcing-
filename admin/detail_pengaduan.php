<?php
require '../config/auth.php';
if (!isAdmin()) header("Location: ../user/login.php");

$id = $_GET['id'];
$pengaduan = getPengaduanDetailForAdmin($id);

if (!$pengaduan) {
  header("Location: pengaduan.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $admin_id = $_SESSION['user_id'];
  $response = $_POST['response'];
  $status = $_POST['status'];
  
  if (updateResponPengaduan($id, $admin_id, $response, $status)) {
    header("Location: detail_pengaduan.php?id=$id&success=1");
    exit;
  } else {
    $error = "Gagal mengupdate respon";
  }
}

function getPengaduanDetailForAdmin($id) {
  global $conn;
  $result = $conn->query("SELECT p.*, u.nama as user_nama FROM pengaduan p JOIN users u ON p.user_id = u.id WHERE p.id = $id");
  return $result->fetch_assoc();
}
?>

<div class="card">
  <div class="card-header">
    <h5>Detail Pengaduan #<?= $pengaduan['id'] ?></h5>
  </div>
  <div class="card-body">
    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-success">Respon berhasil disimpan!</div>
    <?php endif; ?>
    
    <div class="row">
      <div class="col-md-6">
        <h6>Data Pengaduan</h6>
        <table class="table table-bordered">
          <tr>
            <th width="30%">Nama Warga</th>
            <td><?= htmlspecialchars($pengaduan['user_nama']) ?></td>
          </tr>
          <tr>
            <th>Judul</th>
            <td><?= htmlspecialchars($pengaduan['judul']) ?></td>
          </tr>
          <tr>
            <th>Isi Pengaduan</th>
            <td><?= nl2br(htmlspecialchars($pengaduan['isi'])) ?></td>
          </tr>
          <tr>
            <th>Lokasi</th>
            <td><?= $pengaduan['lokasi'] ? htmlspecialchars($pengaduan['lokasi']) : '-' ?></td>
          </tr>
          <tr>
            <th>Tanggal Kejadian</th>
            <td><?= date('d F Y', strtotime($pengaduan['tanggal'])) ?></td>
          </tr>
          <tr>
            <th>Status</th>
            <td>
              <span class="badge bg-<?= 
                $pengaduan['status'] == 'selesai' ? 'success' : 
                ($pengaduan['status'] == 'diproses' ? 'warning' : 'primary') 
              ?>">
                <?= ucfirst($pengaduan['status']) ?>
              </span>
            </td>
          </tr>
        </table>
      </div>
      
      <div class="col-md-6">
        <h6>Form Respon Admin</h6>
        <form method="POST">
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
              <option value="diterima" <?= $pengaduan['status'] == 'diterima' ? 'selected' : '' ?>>Diterima</option>
              <option value="diproses" <?= $pengaduan['status'] == 'diproses' ? 'selected' : '' ?>>Diproses</option>
              <option value="selesai" <?= $pengaduan['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Respon Admin</label>
            <textarea name="response" class="form-control" rows="5" required><?= 
              $pengaduan['admin_response'] ? htmlspecialchars($pengaduan['admin_response']) : ''
            ?></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Simpan Respon</button>
        </form>
        
        <?php if ($pengaduan['admin_response']): ?>
          <div class="mt-4 p-3 bg-light rounded">
            <h6>Respon Sebelumnya</h6>
            <p><?= nl2br(htmlspecialchars($pengaduan['admin_response'])) ?></p>
            <small class="text-muted">
              Diupdate pada: <?= date('d/m/Y H:i', strtotime($pengaduan['updated_at'])) ?>
            </small>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>