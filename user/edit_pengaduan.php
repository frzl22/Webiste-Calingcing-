<?php
require '../config/auth.php';
if (!isLoggedIn()) header("Location: login.php");

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$pengaduan = getPengaduanDetail($id, $user_id);

if (!$pengaduan || $pengaduan['status'] != 'diterima') {
  header("Location: pengaduan.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $judul = $_POST['judul'];
  $isi = $_POST['isi'];
  $lokasi = $_POST['lokasi'];
  $tanggal = $_POST['tanggal'];
  
  $stmt = $conn->prepare("UPDATE pengaduan SET judul=?, isi=?, lokasi=?, tanggal=? WHERE id=? AND user_id=?");
  $stmt->bind_param("ssssii", $judul, $isi, $lokasi, $tanggal, $id, $user_id);
  
  if ($stmt->execute()) {
    header("Location: detail_pengaduan.php?id=$id&success=1");
    exit;
  } else {
    $error = "Gagal mengupdate pengaduan";
  }
}
?>

<div class="card">
  <div class="card-header">
    <h5><i class="fas fa-edit me-2"></i> Edit Pengaduan</h5>
  </div>
  <div class="card-body">
    <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Judul Pengaduan</label>
        <input type="text" class="form-control" name="judul" value="<?= htmlspecialchars($pengaduan['judul']) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Isi Pengaduan</label>
        <textarea class="form-control" name="isi" rows="5" required><?= htmlspecialchars($pengaduan['isi']) ?></textarea>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Lokasi Kejadian</label>
          <input type="text" class="form-control" name="lokasi" value="<?= htmlspecialchars($pengaduan['lokasi']) ?>">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Tanggal Kejadian</label>
          <input type="date" class="form-control" name="tanggal" value="<?= $pengaduan['tanggal'] ?>" required>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Update Pengaduan</button>
    </form>
  </div>
</div>