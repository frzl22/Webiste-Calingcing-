<?php
require '../config/auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $nik = $_POST['nik'];
  $password = $_POST['password'];
  
  if (login($nik, $password)) {
    if (isAdmin()) {
      header("Location: ../admin/dashboard.php");
    } else {
      header("Location: dashboard.php");
    }
    exit;
  } else {
    header("Location: login.php?error=1");
    exit;
  }
}
?>