<?php
session_start();
require 'database.php';

function login($nik, $password) {
    global $conn;
    
    // Validasi input
    if (empty($nik)) {
        throw new Exception('NIK tidak boleh kosong');
    }
    if (empty($password)) {
        throw new Exception('Password tidak boleh kosong');
    }
    
    $sql = "SELECT * FROM users WHERE nik = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Error persiapan query: ' . $conn->error);
    }
    
    $stmt->bind_param("s", $nik);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Regenerate session ID untuk mencegah session fixation
            session_regenerate_id(true);
            
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;
            $_SESSION['last_activity'] = time();
            
            return true;
        }
    }
    
    return false;
}

function isLoggedIn() {
    // Periksa apakah user sudah login dan session masih aktif
    return isset($_SESSION['logged_in'], $_SESSION['user_id'], $_SESSION['role']) 
           && $_SESSION['logged_in'] === true;
}

function isAdmin() {
    return isLoggedIn() && $_SESSION['role'] == 'admin';
}

function logout() {
    // Hapus semua data session
    $_SESSION = array();
    
    // Hapus session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    
    // Hancurkan session
    session_destroy();
    
    // Redirect ke halaman login
    header("Location: /calingcing/user/login.php");
    exit;
}
function buatPengaduan($user_id, $judul, $isi, $lokasi, $tanggal) {
  global $conn;
  
  $stmt = $conn->prepare("INSERT INTO pengaduan (user_id, judul, isi, lokasi, tanggal) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("issss", $user_id, $judul, $isi, $lokasi, $tanggal);
  return $stmt->execute();
}

function getPengaduanByUser($user_id) {
  global $conn;
  $result = $conn->query("SELECT * FROM pengaduan WHERE user_id = $user_id ORDER BY created_at DESC");
  return $result->fetch_all(MYSQLI_ASSOC);
}

function getPengaduanDetail($id, $user_id) {
  global $conn;
  $result = $conn->query("SELECT * FROM pengaduan WHERE id = $id AND user_id = $user_id");
  return $result->fetch_assoc();
}
// Untuk admin
function getAllPengaduan() {
  global $conn;
  $result = $conn->query("SELECT p.*, u.nama as user_nama FROM pengaduan p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC");
  return $result->fetch_all(MYSQLI_ASSOC);
}

function updateResponPengaduan($pengaduan_id, $admin_id, $response, $status) {
  global $conn;
  $stmt = $conn->prepare("UPDATE pengaduan SET admin_id=?, admin_response=?, status=? WHERE id=?");
  $stmt->bind_param("issi", $admin_id, $response, $status, $pengaduan_id);
  return $stmt->execute();
}

function buatAntrian($user_id, $jenis_layanan) {
  global $conn;
  
  // Generate nomor antrian (format: A001)
  $tanggal = date('Y-m-d');
  $last_num = $conn->query("SELECT COUNT(*) as total FROM antrian WHERE tanggal = '$tanggal'")->fetch_assoc()['total'];
  $nomor_antrian = 'A' . str_pad($last_num + 1, 3, '0', STR_PAD_LEFT);
  
  $stmt = $conn->prepare("INSERT INTO antrian (user_id, jenis_layanan, nomor_antrian, tanggal) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("isss", $user_id, $jenis_layanan, $nomor_antrian, $tanggal);
  return $stmt->execute() ? $nomor_antrian : false;
}

function getAntrianUser($user_id) {
  global $conn;
  $result = $conn->query("SELECT * FROM antrian WHERE user_id = $user_id ORDER BY created_at DESC");
  return $result->fetch_all(MYSQLI_ASSOC);
}
// Handle logout request
if (isset($_GET['logout'])) {
    logout();
}
?>