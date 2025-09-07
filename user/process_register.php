<?php
require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = $_POST['nama'];
  $nik = $_POST['nik'];
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  // Validasi
  if ($password !== $confirm_password) {
    header("Location: register.php?error=password_mismatch");
    exit;
  }

  // Cek NIK sudah ada
  $check_nik = $conn->prepare("SELECT id FROM users WHERE nik = ?");
  $check_nik->bind_param("s", $nik);
  $check_nik->execute();
  $check_nik->store_result();

  if ($check_nik->num_rows > 0) {
    header("Location: register.php?error=nik_exists");
    exit;
  }

  // Hash password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Simpan ke database
  $stmt = $conn->prepare("INSERT INTO users (nama, nik, password, role) VALUES (?, ?, ?, 'user')");
  $stmt->bind_param("sss", $nama, $nik, $hashed_password);

  if ($stmt->execute()) {
    header("Location: login.php?register_success=1");
  } else {
    header("Location: register.php?error=database_error");
  }

  $stmt->close();
  $conn->close();
} else {
  header("Location: register.php");
}
?>