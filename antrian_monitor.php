<?php
require 'config/database.php';

$antrian_aktif = $conn->query("SELECT * FROM antrian 
                              WHERE tanggal = CURDATE() 
                              AND status = 'dipanggil'
                              ORDER BY created_at DESC LIMIT 1")->fetch_assoc();

$antrian_menunggu = $conn->query("SELECT COUNT(*) as total FROM antrian 
                                 WHERE tanggal = CURDATE() 
                                 AND status = 'menunggu'")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
  <title>Monitor Antrian - Desa Calingcing</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f8f9fa; }
    .monitor-container { 
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    .antrian-display {
      font-size: 5rem;
      font-weight: bold;
      color: #4361ee;
    }
    .info-box {
      border-left: 4px solid #4361ee;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="monitor-container p-5 text-center">
          <h2 class="mb-4">SISTEM ANTRIAN ONLINE</h2>
          <h4 class="mb-4">Desa Calingcing - Kab. Sukabumi</h4>
          
          <div class="antrian-display my-5">
            <?= $antrian_aktif ? $antrian_aktif['nomor_antrian'] : '---' ?>
          </div>
          
          <div class="row text-start">
            <div class="col-md-6 mb-3">
              <div class="p-3 info-box">
                <h5>Layanan</h5>
                <p class="fs-4"><?= $antrian_aktif ? $antrian_aktif['jenis_layanan'] : '---' ?></p>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <div class="p-3 info-box">
                <h5>Antrian Menunggu</h5>
                <p class="fs-4"><?= $antrian_menunggu ?></p>
              </div>
            </div>
          </div>
          
          <div class="mt-4 text-muted">
            <small>Update Terakhir: <?= date('H:i:s') ?></small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Auto refresh setiap 10 detik
    setTimeout(function(){
      location.reload();
    }, 10000);
  </script>
</body>
</html>