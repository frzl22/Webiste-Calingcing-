<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Desa Digital Calingcing | Kab. Sukabumi</title>
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
      --glass: rgba(255, 255, 255, 0.08);
      --glass-border: rgba(255, 255, 255, 0.15);
      --shadow: rgba(0, 0, 0, 0.1);
      --glow: rgba(102, 126, 234, 0.4);
      --green: #4ade80;
      --emerald: #10b981;
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
    
    /* Village Background with Overlay */
    .village-background {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: 
        linear-gradient(135deg, rgba(102, 126, 234, 0.8) 0%, rgba(118, 75, 162, 0.8) 100%),
        url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80') center/cover no-repeat;
      z-index: -2;
    }
    
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: 
        radial-gradient(circle at 20% 50%, rgba(102, 126, 234, 0.2) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(118, 75, 162, 0.2) 0%, transparent 50%),
        radial-gradient(circle at 40% 80%, rgba(240, 147, 251, 0.1) 0%, transparent 50%);
      pointer-events: none;
      z-index: -1;
    }
    
    /* === NAVBAR === */
    .navbar {
      background: rgba(0, 0, 0, 0.1) !important;
      backdrop-filter: blur(25px);
      -webkit-backdrop-filter: blur(25px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.15);
      padding: 1rem 0;
      transition: all 0.3s ease;
    }
    
    .navbar.scrolled {
      background: rgba(0, 0, 0, 0.2) !important;
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
    }
    
    .navbar-brand {
      font-weight: 700;
      font-size: 1.5rem;
      color: var(--light) !important;
      text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }
    
    .nav-link {
      color: var(--light) !important;
      font-weight: 500;
      margin-left: 1.5rem;
      transition: all 0.3s ease;
      position: relative;
      text-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
    }
    
    .nav-link:hover {
      color: var(--accent) !important;
      transform: translateY(-2px);
    }
    
    .nav-link::after {
      content: '';
      position: absolute;
      bottom: -5px;
      left: 0;
      width: 0;
      height: 2px;
      background: linear-gradient(to right, var(--accent), var(--primary));
      transition: width 0.3s ease;
    }
    
    .nav-link:hover::after {
      width: 100%;
    }
    
    /* === HERO SECTION === */
    .hero {
      height: 100vh;
      display: flex;
      align-items: center;
      position: relative;
      overflow: hidden;
    }
    
    .hero::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: 
        radial-gradient(circle, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
      background-size: 60px 60px;
      animation: float 25s ease-in-out infinite;
      pointer-events: none;
    }
    
    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      50% { transform: translateY(-30px) rotate(180deg); }
    }
    
    .hero-content {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(30px);
      -webkit-backdrop-filter: blur(30px);
      border-radius: 30px;
      padding: 4rem;
      box-shadow: 
        0 25px 50px rgba(0, 0, 0, 0.2),
        inset 0 1px 0 rgba(255, 255, 255, 0.3);
      border: 1px solid rgba(255, 255, 255, 0.2);
      position: relative;
      overflow: hidden;
    }
    
    .hero-content::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, transparent 100%);
      pointer-events: none;
    }
    
    .hero-title {
      font-size: 4rem;
      font-weight: 800;
      background: linear-gradient(135deg, var(--light) 0%, var(--accent) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 1.5rem;
      text-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      position: relative;
      z-index: 1;
    }
    
    .hero-subtitle {
      font-size: 1.3rem;
      color: var(--light);
      margin-bottom: 3rem;
      opacity: 0.95;
      line-height: 1.6;
      position: relative;
      z-index: 1;
      text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }
    
    .btn-hero {
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      border: none;
      padding: 1rem 2.5rem;
      font-size: 1.1rem;
      font-weight: 600;
      border-radius: 50px;
      transition: all 0.3s ease;
      box-shadow: 0 10px 30px var(--glow);
      position: relative;
      overflow: hidden;
      z-index: 1;
    }
    
    .btn-hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: left 0.6s ease;
    }
    
    .btn-hero:hover::before {
      left: 100%;
    }
    
    .btn-hero:hover {
      transform: translateY(-3px);
      box-shadow: 0 15px 40px var(--glow);
    }
    
    .btn-outline-hero {
      background: transparent;
      border: 2px solid var(--light);
      color: var(--light);
      padding: 1rem 2.5rem;
      font-size: 1.1rem;
      font-weight: 600;
      border-radius: 50px;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .btn-outline-hero:hover {
      background: var(--light);
      color: var(--primary);
      transform: translateY(-3px);
      box-shadow: 0 15px 40px rgba(255, 255, 255, 0.3);
    }
    
    /* === LEADERSHIP SECTION === */
    .leadership {
      padding: 8rem 0;
      position: relative;
      background: rgba(0, 0, 0, 0.1);
      backdrop-filter: blur(10px);
    }
    
    .leader-card {
      background: rgba(255, 255, 255, 0.12);
      backdrop-filter: blur(25px);
      -webkit-backdrop-filter: blur(25px);
      border-radius: 25px;
      padding: 3rem;
      transition: all 0.4s ease;
      border: 1px solid rgba(255, 255, 255, 0.2);
      position: relative;
      overflow: hidden;
      margin-bottom: 2rem;
      text-align: center;
    }
    
    .leader-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 30px 60px rgba(0, 0, 0, 0.25);
      border-color: var(--green);
    }
    
    .leader-photo {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      overflow: hidden;
      margin: 0 auto 1.5rem;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      border: 3px solid #fff;
    }
    
    .leader-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    
    .leader-info h3 {
      color: var(--light);
      font-weight: 700;
      margin-bottom: 0.5rem;
      font-size: 1.5rem;
    }
    
    .leader-position {
      color: var(--accent);
      font-weight: 600;
      margin-bottom: 0.5rem;
    }
    
    .leader-period {
      color: rgba(255, 255, 255, 0.8);
      font-size: 0.9rem;
      margin-bottom: 1.5rem;
    }
    
    .leader-bio {
      color: rgba(255, 255, 255, 0.9);
      line-height: 1.6;
      margin-bottom: 1.5rem;
    }
    
    .leader-contact {
      margin-top: 1.5rem;
    }
    
    .contact-item {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 0.5rem;
      color: rgba(255, 255, 255, 0.9);
    }
    
    .contact-item i {
      margin-right: 0.5rem;
      color: var(--accent);
    }
    
    /* === VISION MISSION SECTION === */
    .vision-mission {
      padding: 8rem 0;
      position: relative;
      background: rgba(0, 0, 0, 0.1);
      backdrop-filter: blur(10px);
    }
    
    .vision-mission::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, transparent 100%);
      pointer-events: none;
    }
    
    .vision-card, .mission-card {
      background: rgba(255, 255, 255, 0.12);
      backdrop-filter: blur(25px);
      -webkit-backdrop-filter: blur(25px);
      border-radius: 25px;
      padding: 3.5rem;
      height: 100%;
      transition: all 0.4s ease;
      border: 1px solid rgba(255, 255, 255, 0.2);
      position: relative;
      overflow: hidden;
      margin-bottom: 2rem;
    }
    
    .vision-card::before, .mission-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, transparent 100%);
      pointer-events: none;
      transition: opacity 0.3s ease;
    }
    
    .vision-card:hover, .mission-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 30px 60px rgba(0, 0, 0, 0.25);
      border-color: var(--green);
    }
    
    .vision-card:hover::before, .mission-card:hover::before {
      opacity: 0.8;
    }
    
    .vision-icon, .mission-icon {
      font-size: 4rem;
      margin-bottom: 2rem;
      background: linear-gradient(135deg, var(--green) 0%, var(--emerald) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      position: relative;
      z-index: 1;
      text-align: center;
    }
    
    .vision-card h3, .mission-card h3 {
      color: var(--light);
      font-weight: 700;
      margin-bottom: 2rem;
      font-size: 2rem;
      position: relative;
      z-index: 1;
      text-align: center;
      text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }
    
    .vision-card p, .mission-card p {
      color: rgba(255, 255, 255, 0.9);
      line-height: 1.8;
      position: relative;
      z-index: 1;
      font-size: 1.1rem;
      text-align: center;
    }
    
    .mission-list {
      list-style: none;
      padding: 0;
    }
    
    .mission-list li {
      color: rgba(255, 255, 255, 0.9);
      margin-bottom: 1.5rem;
      padding-left: 2rem;
      position: relative;
      line-height: 1.6;
      font-size: 1.05rem;
    }
    
    .mission-list li::before {
      content: '✦';
      position: absolute;
      left: 0;
      color: var(--green);
      font-size: 1.2rem;
      font-weight: bold;
    }
    
    /* === FEATURES === */
    .features {
      padding: 8rem 0;
      position: relative;
    }
    
    .section-title {
      font-size: 3rem;
      font-weight: 700;
      color: var(--light);
      margin-bottom: 1rem;
      text-align: center;
      text-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }
    
    .section-subtitle {
      font-size: 1.2rem;
      color: rgba(255, 255, 255, 0.85);
      text-align: center;
      margin-bottom: 4rem;
      text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
    
    .feature-card {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(25px);
      -webkit-backdrop-filter: blur(25px);
      border-radius: 25px;
      padding: 3rem;
      height: 100%;
      transition: all 0.4s ease;
      border: 1px solid rgba(255, 255, 255, 0.15);
      position: relative;
      overflow: hidden;
      margin-bottom: 2rem;
    }
    
    .feature-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, transparent 100%);
      pointer-events: none;
      transition: opacity 0.3s ease;
    }
    
    .feature-card:hover {
      transform: translateY(-15px);
      box-shadow: 0 30px 60px rgba(0, 0, 0, 0.25);
      border-color: var(--accent);
    }
    
    .feature-card:hover::before {
      opacity: 0.8;
    }
    
    .feature-icon {
      font-size: 3rem;
      margin-bottom: 2rem;
      background: linear-gradient(135deg, var(--accent) 0%, var(--primary) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      position: relative;
      z-index: 1;
    }
    
    .feature-card h3 {
      color: var(--light);
      font-weight: 600;
      margin-bottom: 1rem;
      font-size: 1.5rem;
      position: relative;
      z-index: 1;
      text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
    
    .feature-card p {
      color: rgba(255, 255, 255, 0.85);
      line-height: 1.6;
      position: relative;
      z-index: 1;
    }
    
    /* === FOOTER === */
    footer {
      background: rgba(0, 0, 0, 0.3);
      backdrop-filter: blur(25px);
      -webkit-backdrop-filter: blur(25px);
      color: var(--light);
      padding: 3rem 0;
      border-top: 1px solid rgba(255, 255, 255, 0.15);
      position: relative;
    }
    
    footer::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, transparent 100%);
      pointer-events: none;
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
    
    /* === RESPONSIVE === */
    @media (max-width: 768px) {
      .hero-title {
        font-size: 2.5rem;
      }
      
      .hero-content {
        padding: 2.5rem;
      }
      
      .section-title {
        font-size: 2rem;
      }
      
      .feature-card, .vision-card, .mission-card, .leader-card {
        padding: 2rem;
        margin-bottom: 1.5rem;
      }
      
      .nav-link {
        margin-left: 0;
        margin-top: 0.5rem;
      }
      
      .vision-icon, .mission-icon {
        font-size: 3rem;
      }
      
      .vision-card h3, .mission-card h3 {
        font-size: 1.5rem;
      }
      
      .leader-photo {
        width: 120px;
        height: 120px;
      }
    }
    
    /* === SCROLL ANIMATIONS === */
    .scroll-fade {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.8s ease;
    }
    
    .scroll-fade.visible {
      opacity: 1;
      transform: translateY(0);
    }
  </style>
</head>
<body>
  <!-- Village Background -->
  <div class="village-background"></div>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#">
        <i class="fas fa-village me-2"></i>Desa Calingcing
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="#"><i class="fas fa-home me-1"></i> Beranda</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#leadership"><i class="fas fa-users me-1"></i> Kepemimpinan</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#vision-mission"><i class="fas fa-bullseye me-1"></i> Visi Misi</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="antrian_monitor.php"><i class="fas fa-clock me-1"></i> No Antrian</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./user/login.php"><i class="fas fa-user me-1"></i> Login Warga</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./admin/login.php"><i class="fas fa-lock me-1"></i> Admin</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-10 text-center">
          <div class="hero-content fade-in-up">
            <h1 class="hero-title">Desa Digital Calingcing</h1>
            <p class="hero-subtitle">Revolusi layanan administrasi desa dengan teknologi terdepan untuk kemudahan dan kenyamanan warga</p>
            <div class="d-flex gap-3 justify-content-center flex-wrap"> 
              <a href="#features" class="btn btn-outline-hero">
                <i class="fas fa-info-circle me-2"></i>Pelajari Fitur
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Leadership Profile Section -->
  <section id="leadership" class="leadership">
    <div class="container">
      <div class="row mb-5">
        <div class="col-12">
          <h2 class="section-title scroll-fade">Struktur Kepemimpinan</h2>
          <p class="section-subtitle scroll-fade">Mengenal para pemimpin Desa Calingcing yang berkomitmen melayani masyarakat</p>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-6 mb-4">
          <div class="leader-card scroll-fade">
            <div class="leader-photo">
              <img src="foto/kades.jpg" alt="Bapak Suherlan - Kepala Desa Calingcing" class="leader-image">
            </div>
            <div class="leader-info">
              <h3>Bapak Suherlan</h3>
              <div class="leader-position">Kepala Desa Calingcing</div>
              <div class="leader-period">Periode: 2019 - 2025</div>
              <p class="leader-bio">
                Memimpin Desa Calingcing dengan visi transformasi digital dan pemberdayaan masyarakat. 
                Berkomitmen untuk memberikan pelayanan terbaik dan memajukan kesejahteraan seluruh warga desa 
                melalui inovasi dan program-program pembangunan berkelanjutan.
              </p>
              <div class="leader-contact">
                <div class="contact-item">
                  <i class="fas fa-phone"></i>
                  <span>+62 812-xxxx-xxxx</span>
                </div>
                <div class="contact-item">
                  <i class="fas fa-envelope"></i>
                  <span>kades.calingcing@sukabumikab.go.id</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-5 col-md-6 mb-4">
          <div class="leader-card scroll-fade">
            <div class="leader-photo">
              <img src="foto/sekdes.jpg" alt="Bapak Ramdan Mustofa - Sekretaris Desa Calingcing" class="leader-image">
            </div>
            <div class="leader-info">
              <h3>Bapak Ramdan Mustofa, S.IP</h3>
              <div class="leader-position">Sekretaris Desa Calingcing</div>
              <div class="leader-period">Sejak: 2018</div>
              <p class="leader-bio">
                Bertanggung jawab atas administrasi pemerintahan desa Calingcing dan koordinasi seluruh kegiatan pelayanan publik. 
                Berpengalaman dalam mengelola sistem administrasi modern dan berkomitmen untuk meningkatkan 
                kualitas layanan berbasis teknologi digital.
              </p>
              <div class="leader-contact">
                <div class="contact-item">
                  <i class="fas fa-phone"></i>
                  <span>+62 813-xxxx-xxxx</span>
                </div>
                <div class="contact-item">
                  <i class="fas fa-envelope"></i>
                  <span>sekdes.calingcing@sukabumikab.go.id</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Vision Mission Section -->
  <section id="vision-mission" class="vision-mission">
    <div class="container">
      <div class="row mb-5">
        <div class="col-12">
          <h2 class="section-title scroll-fade">Visi & Misi Desa Calingcing</h2>
          <p class="section-subtitle scroll-fade">Menuju Desa Calingcing yang maju, mandiri, dan sejahtera</p>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6 mb-4">
          <div class="vision-card scroll-fade">
            <div class="vision-icon">
              <i class="fas fa-eye"></i>
            </div>
            <h3>Visi</h3>
            <p>Mewujudkan Desa Calingcing sebagai desa yang maju, mandiri, sejahtera, dan berbudaya dengan memanfaatkan potensi alam serta sumber daya manusia yang berkualitas menuju masyarakat yang berdaya saing di era digital.</p>
          </div>
        </div>
        <div class="col-lg-6 mb-4">
          <div class="mission-card scroll-fade">
            <div class="mission-icon">
              <i class="fas fa-bullseye"></i>
            </div>
            <h3>Misi</h3>
            <ul class="mission-list">
              <li>Meningkatkan kualitas pelayanan publik melalui sistem digital yang transparan dan akuntabel</li>
              <li>Mengembangkan potensi ekonomi desa dengan pemberdayaan UMKM dan pariwisata lokal</li>
              <li>Melestarikan nilai-nilai budaya dan kearifan lokal sebagai identitas desa</li>
              <li>Meningkatkan kualitas pendidikan dan kesehatan masyarakat desa</li>
              <li>Membangun infrastruktur desa yang mendukung kesejahteraan masyarakat</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section id="features" class="features">
    <div class="container">
      <div class="row mb-5">
        <div class="col-12">
          <h2 class="section-title scroll-fade">Layanan Unggulan Desa</h2>
          <p class="section-subtitle scroll-fade">Manfaatkan kemudahan layanan digital terdepan untuk kebutuhan administrasi Anda</p>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-4 col-md-6">
          <div class="feature-card scroll-fade">
            <div class="feature-icon">
              <i class="fas fa-envelope-open-text"></i>
            </div>
            <h3>Pengajuan Surat Online</h3>
            <p>Ajukan berbagai jenis surat keterangan, domisili, dan dokumen lainnya secara online dengan proses yang cepat dan mudah tanpa perlu antri.</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="feature-card scroll-fade">
            <div class="feature-icon">
              <i class="fas fa-bullhorn"></i>
            </div>
            <h3>Pengaduan Digital</h3>
            <p>Laporkan berbagai masalah di lingkungan sekitar dengan sistem pengaduan yang transparan, cepat, dan dapat dipantau secara real-time.</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="feature-card scroll-fade">
            <div class="feature-icon">
              <i class="fas fa-ticket-alt"></i>
            </div>
            <h3>Antrian Pintar</h3>
            <p>Booking nomor antrian untuk berbagai layanan desa dari kenyamanan rumah Anda dengan sistem antrian yang efisien dan modern.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="container text-center">
      <p>&copy;  2025 Desa Digital Calingcing - Kabupaten Sukabumi. Semua hak dilindungi undang-undang.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
      const navbar = document.querySelector('.navbar');
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });

    // Scroll animations
    const observerOptions = {
      threshold: 0.15,
      rootMargin: '0px 0px -80px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
        }
      });
    }, observerOptions);

    document.querySelectorAll('.scroll-fade').forEach(el => {
      observer.observe(el);
    });

    // Parallax effect for hero section
    window.addEventListener('scroll', function() {
      const scrolled = window.pageYOffset;
      const parallax = document.querySelector('.village-background');
      const speed = scrolled * 0.5;
      parallax.style.transform = `translateY(${speed}px)`;
    });
  </script>
</body>
</html>