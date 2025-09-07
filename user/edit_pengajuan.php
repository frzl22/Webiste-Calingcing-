<?php 
require '../config/auth.php';
if (!isLoggedIn()) header("Location: login.php");

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$pengajuan = $conn->query("SELECT * FROM pengajuan_surat WHERE id = $id AND user_id = $user_id")->fetch_assoc();

if (!$pengajuan || $pengajuan['status'] != 'pending') {
  header("Location: dashboard.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $jenis_surat = $_POST['jenis_surat'];
  $keterangan = $_POST['keterangan'];
  
  $stmt = $conn->prepare("UPDATE pengajuan_surat SET jenis_surat = ?, keterangan = ? WHERE id = ?");
  $stmt->bind_param("ssi", $jenis_surat, $keterangan, $id);
  
  if ($stmt->execute()) {
    header("Location: dashboard.php?success=1");
  } else {
    header("Location: edit_pengajuan.php?id=$id&error=1");
  }
  exit;
}
?>

<!-- Form Edit (Mirip dengan form create, tapi dengan value existing) -->