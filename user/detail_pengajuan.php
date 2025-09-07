<?php 
require '../config/auth.php';
if (!isLoggedIn()) header("Location: login.php");

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$pengajuan = $conn->query("SELECT * FROM pengajuan_surat WHERE id = $id AND user_id = $user_id")->fetch_assoc();

if (!$pengajuan) {
  header("Location: dashboard.php");
  exit;
}
?>

<!-- Tampilkan detail -->
<div class="card">
  <div class="card-header d-flex justify-content-between">
    <h5>Detail Pengajuan #<?= $pengajuan['id'] ?></h5>
    <span class="badge rounded-pill status-badge badge-<?= $pengajuan['status'] ?>">
      <?= ucfirst($pengajuan['status']) ?>
    </span>
  </div>
  <div class="card-body">
    <table class="table">
      <tr>
        <th width="30%">Jenis Surat</th>
        <td><?= ucfirst($pengajuan['jenis_surat']) ?></td>
      </tr>
      <tr>
        <th>Tanggal Pengajuan</th>
        <td><?= date('d M Y H:i', strtotime($pengajuan['created_at'])) ?></td>
      </tr>
      <tr>
        <th>Keterangan</th>
        <td><?= nl2br(htmlspecialchars($pengajuan['keterangan'])) ?></td>
      </tr>
    </table>
  </div>
</div>