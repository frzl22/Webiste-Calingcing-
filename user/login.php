<?php require '../config/auth.php'; ?>
<?php if (isLoggedIn()) header("Location: dashboard.php"); ?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Warga - Desa Calingcing</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    /* === GLOBAL STYLES === */
    :root {
      --primary: #667eea;
      --secondary: #764ba2;
      --accent: #f093fb;
      --dark: #0f0f23;
      --light: #ffffff;
      --glass: rgba(255, 255, 255, 0.1);
      --glass-border: rgba(255, 255, 255, 0.2);
      --shadow: rgba(0, 0, 0, 0.1);
      --glow: rgba(102, 126, 234, 0.4);
      --danger: #ff6b6b;
      --success: #51cf66;
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      overflow-x: hidden;
      position: relative;
    }
    
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: 
        radial-gradient(circle at 20% 50%, rgba(102, 126, 234, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(118, 75, 162, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 40% 80%, rgba(240, 147, 251, 0.2) 0%, transparent 50%);
      pointer-events: none;
      z-index: -1;
    }
    
    /* === FLOATING ANIMATION === */
    .floating-shapes {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: -1;
    }
    
    .shape {
      position: absolute;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      animation: float 6s ease-in-out infinite;
    }
    
    .shape:nth-child(1) {
      width: 80px;
      height: 80px;
      top: 20%;
      left: 10%;
      animation-delay: 0s;
    }
    
    .shape:nth-child(2) {
      width: 60px;
      height: 60px;
      top: 60%;
      left: 80%;
      animation-delay: 2s;
    }
    
    .shape:nth-child(3) {
      width: 40px;
      height: 40px;
      top: 80%;
      left: 20%;
      animation-delay: 4s;
    }
    
    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      50% { transform: translateY(-20px) rotate(180deg); }
    }
    
    /* === CONTAINER === */
    .login-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }
    
    /* === LOGIN CARD === */
    .login-card {
      background: var(--glass);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border-radius: 24px;
      padding: 3rem;
      box-shadow: 
        0 20px 40px rgba(0, 0, 0, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.2);
      border: 1px solid var(--glass-border);
      position: relative;
      overflow: hidden;
      width: 100%;
      max-width: 450px;
      transition: all 0.3s ease;
    }
    
    .login-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, transparent 100%);
      pointer-events: none;
    }
    
    .login-card:hover {
      transform: translateY(-5px);
      box-shadow: 
        0 25px 50px rgba(0, 0, 0, 0.15),
        inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }
    
    /* === HEADER === */
    .login-header {
      text-align: center;
      margin-bottom: 2.5rem;
      position: relative;
      z-index: 1;
    }
    
    .login-icon {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      box-shadow: 0 8px 25px var(--glow);
      position: relative;
      overflow: hidden;
    }
    
    .login-icon::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
      animation: shimmer 3s infinite;
    }
    
    @keyframes shimmer {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    
    .login-icon i {
      font-size: 2rem;
      color: var(--light);
      position: relative;
      z-index: 1;
    }
    
    .login-title {
      font-size: 2rem;
      font-weight: 700;
      color: var(--light);
      margin-bottom: 0.5rem;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .login-subtitle {
      color: rgba(255, 255, 255, 0.8);
      font-size: 1rem;
      font-weight: 400;
    }
    
    /* === FORM ELEMENTS === */
    .form-group {
      margin-bottom: 1.5rem;
      position: relative;
    }
    
    .form-label {
      color: var(--light);
      font-weight: 500;
      margin-bottom: 0.5rem;
      display: block;
      font-size: 0.9rem;
    }
    
    .form-control {
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 12px;
      padding: 1rem 1.25rem;
      color: var(--light);
      font-size: 1rem;
      transition: all 0.3s ease;
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
    }
    
    .form-control:focus {
      background: rgba(255, 255, 255, 0.15);
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(240, 147, 251, 0.2);
      color: var(--light);
      outline: none;
    }
    
    .form-control::placeholder {
      color: rgba(255, 255, 255, 0.6);
    }
    
    .input-group {
      position: relative;
    }
    
    .input-icon {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: rgba(255, 255, 255, 0.6);
      font-size: 1.1rem;
      z-index: 10;
    }
    
    .input-group .form-control {
      padding-left: 3rem;
    }
    
    /* === BUTTON === */
    .btn-login {
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      border: none;
      border-radius: 12px;
      padding: 1rem 2rem;
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--light);
      width: 100%;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      box-shadow: 0 8px 25px var(--glow);
    }
    
    .btn-login::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s ease;
    }
    
    .btn-login:hover::before {
      left: 100%;
    }
    
    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 35px var(--glow);
    }
    
    .btn-login:active {
      transform: translateY(0);
    }
    
    /* === ALERTS === */
    .alert {
      background: rgba(255, 107, 107, 0.1);
      border: 1px solid rgba(255, 107, 107, 0.3);
      border-radius: 12px;
      color: #ffb3b3;
      padding: 1rem;
      margin-bottom: 1.5rem;
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
    }
    
    .alert-danger {
      background: rgba(255, 107, 107, 0.1);
      border-color: rgba(255, 107, 107, 0.3);
      color: #ffb3b3;
    }
    
    /* === LINKS === */
    .login-footer {
      text-align: center;
      margin-top: 2rem;
      position: relative;
      z-index: 1;
    }
    
    .login-footer p {
      color: rgba(255, 255, 255, 0.8);
      margin-bottom: 0.5rem;
    }
    
    .login-footer a {
      color: var(--accent);
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    
    .login-footer a:hover {
      color: var(--light);
      text-shadow: 0 0 10px var(--accent);
    }
    
    .back-link {
      display: inline-flex;
      align-items: center;
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
      margin-top: 1rem;
    }
    
    .back-link:hover {
      color: var(--light);
      transform: translateX(-5px);
    }
    
    .back-link i {
      margin-right: 0.5rem;
    }
    
    /* === RESPONSIVE === */
    @media (max-width: 768px) {
      .login-card {
        padding: 2rem;
        margin: 1rem;
      }
      
      .login-title {
        font-size: 1.5rem;
      }
      
      .login-icon {
        width: 60px;
        height: 60px;
      }
      
      .login-icon i {
        font-size: 1.5rem;
      }
    }
    
    /* === ANIMATIONS === */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .fade-in-up {
      animation: fadeInUp 0.8s ease-out;
    }
    
    /* === LOADING STATE === */
    .btn-login.loading {
      pointer-events: none;
      opacity: 0.8;
    }
    
    .btn-login.loading::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 20px;
      height: 20px;
      margin-left: -10px;
      margin-top: -10px;
      border: 2px solid transparent;
      border-top: 2px solid var(--light);
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
</head>
<body>
  <!-- Floating Shapes -->
  <div class="floating-shapes">
    <div class="shape"></div>
    <div class="shape"></div>
    <div class="shape"></div>
  </div>

  <div class="login-container">
    <div class="login-card fade-in-up">
      <!-- Header -->
      <div class="login-header">
        <div class="login-icon">
          <i class="fas fa-user-circle"></i>
        </div>
        <h2 class="login-title">Selamat Datang</h2>
        <p class="login-subtitle">Masuk ke akun warga Desa Calingcing</p>
      </div>
      
      <!-- Alert -->
      <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
          <i class="fas fa-exclamation-circle me-2"></i>
          NIK atau Password salah! Silakan coba lagi.
        </div>
      <?php endif; ?>
      
      <!-- Form -->
      <form action="process_login.php" method="POST" id="loginForm">
        <div class="form-group">
          <label for="nik" class="form-label">
            <i class="fas fa-id-card me-2"></i>Nomor Induk Kependudukan (NIK)
          </label>
          <div class="input-group">
            <i class="input-icon fas fa-id-card"></i>
            <input type="text" class="form-control" id="nik" name="nik" placeholder="Masukkan NIK Anda" required>
          </div>
        </div>
        
        <div class="form-group">
          <label for="password" class="form-label">
            <i class="fas fa-lock me-2"></i>Password
          </label>
          <div class="input-group">
            <i class="input-icon fas fa-lock"></i>
            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password Anda" required>
          </div>
        </div>
        
        <button type="submit" class="btn-login" id="loginBtn">
          <i class="fas fa-sign-in-alt me-2"></i>Masuk ke Dashboard
        </button>
      </form>
      
      <!-- Footer -->
        
        <div class="mt-3">
          <a href="../index.php" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Beranda
          </a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Form submission with loading state
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      const btn = document.getElementById('loginBtn');
      btn.classList.add('loading');
      btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
    });

    // Input focus effects
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
      input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'translateY(-2px)';
      });
      
      input.addEventListener('blur', function() {
        this.parentElement.style.transform = 'translateY(0)';
      });
    });

    // NIK input validation (only numbers)
    document.getElementById('nik').addEventListener('input', function(e) {
      this.value = this.value.replace(/[^0-9]/g, '');
      if (this.value.length > 16) {
        this.value = this.value.substring(0, 16);
      }
    });
  </script>
</body>
</html>