<?php
require '../config/auth.php';
if (!isLoggedIn()) header("Location: login.php");

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Pastikan hanya bisa hapus pengajuan pending milik sendiri
$pengajuan = $conn->query("SELECT id FROM pengajuan_surat WHERE id = $id AND user_id = $user_id AND status = 'pending'");

if ($pengajuan->num_rows == 1) {
  $conn->query("DELETE FROM pengajuan_surat WHERE id = $id");
  header("Location: dashboard.php?success=2"); // 2 untuk pesan delete success
} else {
  header("Location: dashboard.php?error=2"); // 2 untuk pesan delete error
}
exit;
?>