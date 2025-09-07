<?php require '../config/auth.php'; ?>
<?php if (isLoggedIn()) header("Location: dashboard.php"); ?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin - Desa Calingcing</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      height: 100vh;
      overflow: hidden;
      position: relative;
    }

    /* Animated background particles */
    .particles {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 1;
    }

    .particle {
      position: absolute;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      animation: float 6s ease-in-out infinite;
    }

    .particle:nth-child(1) { width: 80px; height: 80px; top: 20%; left: 10%; animation-delay: 0s; }
    .particle:nth-child(2) { width: 120px; height: 120px; top: 60%; left: 80%; animation-delay: 2s; }
    .particle:nth-child(3) { width: 60px; height: 60px; top: 80%; left: 20%; animation-delay: 4s; }
    .particle:nth-child(4) { width: 100px; height: 100px; top: 40%; left: 70%; animation-delay: 1s; }
    .particle:nth-child(5) { width: 90px; height: 90px; top: 10%; left: 60%; animation-delay: 3s; }

    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      50% { transform: translateY(-20px) rotate(180deg); }
    }

    .container {
      position: relative;
      z-index: 2;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      padding: 2.5rem;
      position: relative;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .login-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 25px 80px rgba(0, 0, 0, 0.15);
    }

    .login-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
      transition: left 0.5s ease;
    }

    .login-card:hover::before {
      left: 100%;
    }

    .logo-container {
      text-align: center;
      margin-bottom: 2rem;
    }

    .logo-icon {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, #667eea, #764ba2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1rem;
      box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }

    .logo-icon i {
      font-size: 2rem;
      color: white;
    }

    .login-title {
      color: #333;
      font-weight: 700;
      margin-bottom: 0.5rem;
      font-size: 1.8rem;
    }

    .login-subtitle {
      color: #666;
      margin-bottom: 2rem;
      font-size: 0.95rem;
    }

    .form-floating {
      position: relative;
      margin-bottom: 1.5rem;
    }

    .form-control {
      border: 2px solid #e1e5e9;
      border-radius: 12px;
      padding: 1rem 1rem 1rem 3rem;
      font-size: 1rem;
      transition: all 0.3s ease;
      background: rgba(255, 255, 255, 0.9);
    }

    .form-control:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
      transform: translateY(-2px);
    }

    .input-icon {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: #999;
      z-index: 5;
      transition: color 0.3s ease;
    }

    .form-control:focus + .input-icon {
      color: #667eea;
    }

    .form-label {
      font-weight: 600;
      color: #555;
      margin-bottom: 0.5rem;
    }

    .btn-login {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      padding: 1rem 2rem;
      border-radius: 12px;
      font-weight: 600;
      font-size: 1.1rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
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

    .alert-danger {
      background: rgba(220, 53, 69, 0.1);
      border: 1px solid rgba(220, 53, 69, 0.3);
      color: #dc3545;
      border-radius: 12px;
      padding: 1rem;
      margin-bottom: 1.5rem;
      animation: shake 0.5s ease-in-out;
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-5px); }
      75% { transform: translateX(5px); }
    }

    .forgot-password {
      text-align: center;
      margin-top: 1.5rem;
    }

    .forgot-password a {
      color: #667eea;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s ease;
    }

    .forgot-password a:hover {
      color: #764ba2;
      text-decoration: underline;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
      .login-card {
        margin: 1rem;
        padding: 2rem;
      }

      .logo-icon {
        width: 60px;
        height: 60px;
      }

      .logo-icon i {
        font-size: 1.5rem;
      }

      .login-title {
        font-size: 1.5rem;
      }
    }

    /* Loading animation */
    .btn-login.loading {
      pointer-events: none;
    }

    .btn-login.loading::after {
      content: '';
      position: absolute;
      width: 20px;
      height: 20px;
      top: 50%;
      left: 50%;
      margin-left: -10px;
      margin-top: -10px;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-radius: 50%;
      border-top-color: white;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }
  </style>
</head>
<body>
  <!-- Animated background particles -->
  <div class="particles">
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
  </div>

  <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="login-card" style="width: 100%; max-width: 450px;">
      <div class="logo-container">
        <div class="logo-icon">
          <i class="fas fa-building"></i>
        </div>
        <h2 class="login-title">Selamat Datang</h2>
        <p class="login-subtitle">Masuk ke Panel Admin Desa Calingcing</p>
      </div>
      
      <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
          <i class="fas fa-exclamation-triangle me-2"></i>
          NIK atau Password salah!
        </div>
      <?php endif; ?>
      
      <form action="process_login.php" method="POST" id="loginForm">
        <div class="form-floating">
          <label for="nik" class="form-label">
            <i class="fas fa-id-card me-2"></i>NIK
          </label>
          <input type="text" class="form-control" id="nik" name="nik" required>
          <i class="fas fa-id-card input-icon"></i>
        </div>
        
        <div class="form-floating">
          <label for="password" class="form-label">
            <i class="fas fa-lock me-2"></i>Password
          </label>
          <input type="password" class="form-control" id="password" name="password" required>
          <i class="fas fa-lock input-icon"></i>
        </div>
        
        <button type="submit" class="btn btn-login w-100" id="loginBtn">
          <span>Masuk</span>
        </button>
      </form>
      
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Add loading animation to button
    document.getElementById('loginForm').addEventListener('submit', function() {
      const btn = document.getElementById('loginBtn');
      btn.classList.add('loading');
      btn.innerHTML = '';
    });

    // Add input focus effects
    document.querySelectorAll('.form-control').forEach(input => {
      input.addEventListener('focus', function() {
        this.parentElement.classList.add('focused');
      });
      
      input.addEventListener('blur', function() {
        this.parentElement.classList.remove('focused');
      });
    });

    // Smooth page load animation
    window.addEventListener('load', function() {
      document.querySelector('.login-card').style.opacity = '0';
      document.querySelector('.login-card').style.transform = 'translateY(50px)';
      
      setTimeout(() => {
        document.querySelector('.login-card').style.transition = 'all 0.6s ease';
        document.querySelector('.login-card').style.opacity = '1';
        document.querySelector('.login-card').style.transform = 'translateY(0)';
      }, 100);
    });
  </script>
</body>
</html>