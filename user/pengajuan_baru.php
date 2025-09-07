<?php 
require '../config/auth.php';
if (!isLoggedIn()) header("Location: login.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $jenis_surat = $_POST['jenis_surat'];
  $keterangan = $_POST['keterangan'];
  $user_id = $_SESSION['user_id'];
  
  $stmt = $conn->prepare("INSERT INTO pengajuan_surat (user_id, jenis_surat, keterangan) VALUES (?, ?, ?)");
  $stmt->bind_param("iss", $user_id, $jenis_surat, $keterangan);
  
  if ($stmt->execute()) {
    header("Location: dashboard.php?success=1");
  } else {
    header("Location: pengajuan_baru.php?error=1");
  }
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Surat Baru</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --shadow-soft: 0 10px 40px rgba(0,0,0,0.1);
            --shadow-hover: 0 20px 60px rgba(0,0,0,0.15);
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-container {
            padding: 2rem 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .form-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            box-shadow: var(--shadow-soft);
            overflow: hidden;
            transition: all 0.4s ease;
            max-width: 600px;
            margin: 0 auto;
        }

        .form-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        .card-header-custom {
            background: var(--primary-gradient);
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .card-header-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.6s;
        }

        .card-header-custom:hover::before {
            left: 100%;
        }

        .header-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .header-icon {
            font-size: 2rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .card-body-custom {
            padding: 2.5rem;
        }

        .form-floating {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-select, .form-control {
            border: 2px solid #e1e5e9;
            border-radius: 16px;
            padding: 1rem 1.2rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }

        .form-select:focus, .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
            background: white;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .label-icon {
            color: #667eea;
        }

        .btn-submit {
            background: var(--primary-gradient);
            border: none;
            border-radius: 16px;
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.6s;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        .btn-submit:hover::before {
            left: 100%;
        }

        .btn-submit:active {
            transform: translateY(-1px);
        }

        .document-icons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }

        .doc-option {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px solid transparent;
            border-radius: 16px;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .doc-option:hover {
            transform: translateY(-5px);
            border-color: #667eea;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
        }

        .doc-option.selected {
            background: var(--primary-gradient);
            color: white;
            border-color: #667eea;
        }

        .doc-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .floating-element {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        .floating-element:nth-child(1) {
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }

        .floating-element:nth-child(3) {
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .progress-steps {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 0.5rem;
            font-weight: bold;
            color: #6c757d;
            transition: all 0.3s ease;
        }

        .step.active {
            background: var(--primary-gradient);
            color: white;
            transform: scale(1.2);
        }

        .step-connector {
            width: 50px;
            height: 2px;
            background: #e9ecef;
            margin: 0 0.5rem;
            align-self: center;
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }
            
            .card-body-custom {
                padding: 1.5rem;
            }
            
            .header-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="floating-elements">
        <i class="bi bi-file-earmark floating-element" style="font-size: 3rem;"></i>
        <i class="bi bi-award floating-element" style="font-size: 2.5rem;"></i>
        <i class="bi bi-shield-check floating-element" style="font-size: 3.5rem;"></i>
    </div>

    <div class="main-container">
        <div class="container">
            <div class="form-card">
                <div class="card-header-custom">
                    <h5 class="header-title">
                        <i class="bi bi-file-earmark-plus header-icon"></i>
                        Pengajuan Surat Baru
                    </h5>
                </div>
                
                <div class="card-body-custom">
                    <div class="progress-steps">
                        <div class="step active">1</div>
                        <div class="step-connector"></div>
                        <div class="step">2</div>
                        <div class="step-connector"></div>
                        <div class="step">3</div>
                    </div>

                    <form method="POST" id="pengajuanForm">
                        <div class="form-floating">
                            <label class="form-label">
                                <i class="bi bi-list-ul label-icon"></i>
                                Jenis Surat
                            </label>
                            <select name="jenis_surat" class="form-select" required id="jenisSurat">
                                <option value="">Pilih Jenis Surat</option>
                                <option value="ktp">KTP (Kartu Tanda Penduduk)</option>
                                <option value="kk">Kartu Keluarga</option>
                                <option value="domisili">Surat Domisili</option>
                                <option value="usaha">Surat Keterangan Usaha</option>
                                <option value="tidak_mampu">Surat Keterangan Tidak Mampu</option>
                                <option value="kelahiran">Surat Keterangan Kelahiran</option>
                            </select>
                        </div>

                        <div class="form-floating">
                            <label class="form-label">
                                <i class="bi bi-chat-text label-icon"></i>
                                Keterangan Tambahan
                            </label>
                            <textarea name="keterangan" class="form-control" rows="4" 
                                    placeholder="Tuliskan keterangan atau keperluan khusus Anda..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-submit">
                            <i class="bi bi-send me-2"></i>
                            Ajukan Permohonan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Enhanced form interactions
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('pengajuanForm');
            const jenisSurat = document.getElementById('jenisSurat');
            const steps = document.querySelectorAll('.step');
            
            // Progress step animation
            let currentStep = 1;
            
            jenisSurat.addEventListener('change', function() {
                if (this.value) {
                    updateStep(2);
                } else {
                    updateStep(1);
                }
            });
            
            document.querySelector('textarea[name="keterangan"]').addEventListener('input', function() {
                if (this.value.trim() && jenisSurat.value) {
                    updateStep(3);
                } else if (jenisSurat.value) {
                    updateStep(2);
                } else {
                    updateStep(1);
                }
            });
            
            function updateStep(step) {
                steps.forEach((s, index) => {
                    if (index + 1 <= step) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
            }
            
            // Form submission with loading effect
            form.addEventListener('submit', function(e) {
                const submitBtn = document.querySelector('.btn-submit');
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Memproses...';
                submitBtn.disabled = true;
            });
            
            // Add floating label effect
            const inputs = document.querySelectorAll('.form-control, .form-select');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.parentElement.classList.remove('focused');
                    }
                });
            });
        });
    </script>
</body>
</html>