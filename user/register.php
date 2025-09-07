<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Akun Warga - Desa Calingcing</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #667eea;
      --secondary-color: #764ba2;
      --accent-color: #f093fb;
      --text-dark: #2d3748;
      --text-light: #718096;
      --card-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
      --hover-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.2);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      min-height: 100dvh; /* For mobile browsers */
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      position: relative;
      overflow-x: hidden;
      padding: 0;
      margin: 0;
    }

    /* Animated background particles */
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%" r="50%"><stop offset="0%" style="stop-color:rgba(255,255,255,0.1)"/><stop offset="100%" style="stop-color:rgba(255,255,255,0)"/></radialGradient></defs><circle cx="200" cy="200" r="3" fill="url(%23a)"/><circle cx="800" cy="300" r="2" fill="url(%23a)"/><circle cx="400" cy="600" r="2.5" fill="url(%23a)"/><circle cx="700" cy="800" r="2" fill="url(%23a)"/><circle cx="100" cy="500" r="1.5" fill="url(%23a)"/></svg>');
      animation: float 20s infinite linear;
      pointer-events: none;
      z-index: 1;
    }

    @keyframes float {
      0% { transform: translateY(0px) rotate(0deg); }
      50% { transform: translateY(-20px) rotate(180deg); }
      100% { transform: translateY(0px) rotate(360deg); }
    }

    .container {
      position: relative;
      z-index: 2;
    }

    .register-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border-radius: 24px;
      box-shadow: var(--card-shadow);
      border: 1px solid rgba(255, 255, 255, 0.3);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }

    .register-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--primary-color), var(--accent-color), var(--secondary-color));
      animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
      0% { transform: translateX(-100%); }
      100% { transform: translateX(100%); }
    }

    .register-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--hover-shadow);
    }

    .form-header {
      text-align: center;
      margin-bottom: 2rem;
      position: relative;
    }

    .form-icon {
      font-size: 4rem;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 1rem;
      display: inline-block;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.05); }
    }

    .form-header h2 {
      color: var(--text-dark);
      font-weight: 700;
      margin-bottom: 0.5rem;
      font-size: 2rem;
    }

    .form-header p {
      color: var(--text-light);
      font-size: 1rem;
      margin: 0;
    }

    .form-floating {
      position: relative;
      margin-bottom: 1.5rem;
    }

    .form-floating input {
      border: 2px solid #e2e8f0;
      border-radius: 16px;
      padding: 1rem 1.25rem;
      font-size: 1rem;
      transition: all 0.3s ease;
      background: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(10px);
    }

    .form-floating input:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
      outline: none;
      background: rgba(255, 255, 255, 0.95);
    }

    .form-floating label {
      color: var(--text-light);
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .form-floating input:focus ~ label,
    .form-floating input:not(:placeholder-shown) ~ label {
      color: var(--primary-color);
      transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
    }

    /* Fix for webkit browsers */
    .form-floating input::-webkit-input-placeholder {
      color: transparent;
    }

    .form-floating input:-ms-input-placeholder {
      color: transparent;
    }

    .form-floating input::-ms-input-placeholder {
      color: transparent;
    }

    .form-floating input::placeholder {
      color: transparent;
    }

    .input-group {
      position: relative;
    }

    .input-group .form-floating input {
      padding-left: 3.5rem;
    }

    .input-icon {
      position: absolute;
      left: 1.25rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-light);
      font-size: 1.1rem;
      z-index: 10;
      transition: color 0.3s ease;
    }

    .input-group:has(input:focus) .input-icon {
      color: var(--primary-color);
    }

    .btn-register {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      border-radius: 16px;
      padding: 1rem 2rem;
      font-size: 1.1rem;
      font-weight: 600;
      color: white;
      width: 100%;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .btn-register::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: left 0.5s;
    }

    .btn-register:hover::before {
      left: 100%;
    }

    .btn-register:hover {
      transform: translateY(-2px);
      box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
    }

    .btn-register:active {
      transform: translateY(0);
    }

    .alert {
      border-radius: 16px;
      border: none;
      padding: 1rem 1.5rem;
      margin-bottom: 1.5rem;
      font-weight: 500;
      animation: slideIn 0.5s ease;
    }

    @keyframes slideIn {
      from { transform: translateY(-20px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    .alert-danger {
      background: linear-gradient(135deg, #fee2e2, #fecaca);
      color: #dc2626;
      border-left: 4px solid #dc2626;
    }

    .alert-success {
      background: linear-gradient(135deg, #d1fae5, #a7f3d0);
      color: #059669;
      border-left: 4px solid #059669;
    }

    .login-link {
      text-align: center;
      margin-top: 2rem;
      padding-top: 2rem;
      border-top: 1px solid rgba(0, 0, 0, 0.1);
    }

    .login-link a {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      position: relative;
    }

    .login-link a::after {
      content: '';
      position: absolute;
      bottom: -2px;
      left: 0;
      width: 0;
      height: 2px;
      background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
      transition: width 0.3s ease;
    }

    .login-link a:hover::after {
      width: 100%;
    }

    .login-link a:hover {
      color: var(--secondary-color);
    }

    /* Responsive design */
    @media (max-width: 992px) {
      .container {
        padding: 1rem;
      }
      
      .register-card {
        margin: 0.5rem;
        padding: 1.5rem !important;
      }
    }

    @media (max-width: 768px) {
      .register-card {
        margin: 0.5rem;
        border-radius: 20px;
        padding: 1.5rem !important;
      }
      
      .form-header h2 {
        font-size: 1.75rem;
      }
      
      .form-icon {
        font-size: 3rem;
      }
      
      .form-floating input {
        padding: 0.875rem 1rem;
        font-size: 0.95rem;
      }
      
      .input-group .form-floating input {
        padding-left: 3rem;
      }
      
      .input-icon {
        left: 1rem;
        font-size: 1rem;
      }
      
      .btn-register {
        padding: 0.875rem 1.5rem;
        font-size: 1rem;
      }
    }

    @media (max-width: 576px) {
      .container {
        padding: 0.5rem;
      }
      
      .register-card {
        margin: 0.25rem;
        padding: 1.25rem !important;
        border-radius: 16px;
      }
      
      .form-header h2 {
        font-size: 1.5rem;
      }
      
      .form-header p {
        font-size: 0.9rem;
      }
      
      .form-icon {
        font-size: 2.5rem;
      }
      
      .form-floating input {
        padding: 0.75rem 0.875rem;
        font-size: 0.9rem;
      }
      
      .input-group .form-floating input {
        padding-left: 2.75rem;
      }
      
      .input-icon {
        left: 0.875rem;
        font-size: 0.9rem;
      }
      
      .btn-register {
        padding: 0.75rem 1.25rem;
        font-size: 0.95rem;
      }
      
      .alert {
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
      }
    }

    /* Loading animation */
    .loading {
      display: none;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    .spinner {
      width: 40px;
      height: 40px;
      border: 4px solid #f3f3f3;
      border-top: 4px solid var(--primary-color);
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Password strength indicator */
    .password-strength {
      height: 4px;
      background: #e2e8f0;
      border-radius: 2px;
      margin-top: 0.5rem;
      overflow: hidden;
    }

    .password-strength-bar {
      height: 100%;
      width: 0%;
      transition: all 0.3s ease;
      border-radius: 2px;
    }

    .strength-weak { background: #ef4444; width: 25%; }
    .strength-fair { background: #f59e0b; width: 50%; }
    .strength-good { background: #10b981; width: 75%; }
    .strength-strong { background: #059669; width: 100%; }
  </style>
</head>
<body>
  <div class="container-fluid py-3 py-md-5">
    <div class="row justify-content-center mx-0">
      <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
        <div class="register-card p-3 p-sm-4 p-md-5">
          <div class="form-header">
            <i class="fas fa-user-plus form-icon"></i>
            <h2>Pendaftaran Warga</h2>
            <p>Bergabunglah dengan sistem informasi Desa Calingcing</p>
          </div>

          <!-- Notifikasi Error/Success -->
          <div id="error-message" style="display: none;">
            <div class="alert alert-danger">
              <i class="fas fa-exclamation-circle me-2"></i>
              <span id="error-text"></span>
            </div>
          </div>

          <form id="registerForm" action="process_register.php" method="POST">
            <div class="input-group">
              <div class="form-floating">
                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Lengkap" required>
                <label for="nama">Nama Lengkap</label>
              </div>
              <i class="fas fa-user input-icon"></i>
            </div>

            <div class="input-group">
              <div class="form-floating">
                <input type="text" class="form-control" id="nik" name="nik" 
                       placeholder="NIK (16 Digit)" pattern="[0-9]{16}" 
                       title="NIK harus 16 digit angka" required>
                <label for="nik">NIK (16 Digit)</label>
              </div>
              <i class="fas fa-id-card input-icon"></i>
            </div>

            <div class="input-group">
              <div class="form-floating">
                <input type="password" class="form-control" id="password" name="password" 
                       placeholder="Password" minlength="6" required>
                <label for="password">Password</label>
              </div>
              <i class="fas fa-lock input-icon"></i>
              <div class="password-strength">
                <div class="password-strength-bar" id="strengthBar"></div>
              </div>
            </div>

            <div class="input-group">
              <div class="form-floating">
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                       placeholder="Konfirmasi Password" required>
                <label for="confirm_password">Konfirmasi Password</label>
              </div>
              <i class="fas fa-lock input-icon"></i>
            </div>

            <button type="submit" class="btn btn-register">
              <span class="btn-text">Daftar Sekarang</span>
              <div class="loading">
                <div class="spinner"></div>
              </div>
            </button>
          </form>

          <div class="login-link">
            <p class="mb-0">Sudah punya akun? <a href="login.php">Login disini</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Password strength checker
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('strengthBar');
    const confirmPassword = document.getElementById('confirm_password');
    const form = document.getElementById('registerForm');
    const errorMessage = document.getElementById('error-message');
    const errorText = document.getElementById('error-text');

    passwordInput.addEventListener('input', function() {
      const password = this.value;
      const strength = checkPasswordStrength(password);
      
      strengthBar.className = 'password-strength-bar';
      
      if (strength === 1) {
        strengthBar.classList.add('strength-weak');
      } else if (strength === 2) {
        strengthBar.classList.add('strength-fair');
      } else if (strength === 3) {
        strengthBar.classList.add('strength-good');
      } else if (strength === 4) {
        strengthBar.classList.add('strength-strong');
      }
    });

    function checkPasswordStrength(password) {
      let strength = 0;
      
      if (password.length >= 6) strength++;
      if (password.match(/[a-z]/)) strength++;
      if (password.match(/[A-Z]/)) strength++;
      if (password.match(/[0-9]/)) strength++;
      
      return strength;
    }

    // Form validation with better mobile support
    confirmPassword.addEventListener('input', function() {
      if (this.value !== passwordInput.value) {
        this.setCustomValidity('Password tidak cocok');
        this.classList.add('is-invalid');
      } else {
        this.setCustomValidity('');
        this.classList.remove('is-invalid');
      }
    });

    // Enhanced NIK validation
    const nikInput = document.getElementById('nik');
    nikInput.addEventListener('input', function() {
      // Remove non-numeric characters
      this.value = this.value.replace(/[^0-9]/g, '');
      
      // Limit to 16 digits
      if (this.value.length > 16) {
        this.value = this.value.slice(0, 16);
      }
      
      // Visual feedback
      if (this.value.length === 16) {
        this.classList.add('is-valid');
        this.classList.remove('is-invalid');
      } else if (this.value.length > 0) {
        this.classList.add('is-invalid');
        this.classList.remove('is-valid');
      } else {
        this.classList.remove('is-valid', 'is-invalid');
      }
    });

    // Name validation (only letters and spaces)
    const namaInput = document.getElementById('nama');
    namaInput.addEventListener('input', function() {
      this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
    });

    // Enhanced form submission with better error handling
    form.addEventListener('submit', function(e) {
      // Validate form before submission
      if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
        form.classList.add('was-validated');
        return;
      }
      
      const submitBtn = document.querySelector('.btn-register');
      const btnText = submitBtn.querySelector('.btn-text');
      const loading = submitBtn.querySelector('.loading');
      
      submitBtn.disabled = true;
      btnText.style.opacity = '0';
      loading.style.display = 'block';
      
      // Re-enable button after 5 seconds if form doesn't submit
      setTimeout(() => {
        submitBtn.disabled = false;
        btnText.style.opacity = '1';
        loading.style.display = 'none';
      }, 5000);
    });

    // Check for URL parameters to show errors
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');
    
    if (error) {
      let errorMessage = '';
      if (error === 'nik_exists') {
        errorMessage = 'NIK sudah terdaftar dalam sistem!';
      } else if (error === 'password_mismatch') {
        errorMessage = 'Password dan konfirmasi password tidak cocok!';
      } else {
        errorMessage = 'Pendaftaran gagal. Silakan coba lagi!';
      }
      
      document.getElementById('error-message').style.display = 'block';
      document.getElementById('error-text').textContent = errorMessage;
    }

    // Smooth animations for form interactions
    document.querySelectorAll('.form-floating input').forEach(input => {
      input.addEventListener('focus', function() {
        this.parentElement.parentElement.style.transform = 'translateY(-2px)';
      });
      
      input.addEventListener('blur', function() {
        this.parentElement.parentElement.style.transform = 'translateY(0)';
      });
    });
  </script>
</body>
</html>