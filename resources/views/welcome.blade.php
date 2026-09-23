<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>PT Nusantara Digital Express – Sistem Peminjaman Barang & Inventaris</title>

  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}"/>

  <style>
    /* ================= LANDING PAGE ULTRA SMOOTH SCROLL & ANIMATIONS ================= */
    html { 
      scroll-behavior: smooth; 
    }
    
    body {
      background: #f8fafc;
      min-height: 100vh;
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      overflow-x: hidden;
      color: #0f172a;
    }

    /* Ambient Glowing Orbs */
    .ambient-orb {
      position: absolute;
      border-radius: 50%;
      pointer-events: none;
      z-index: 0;
      filter: blur(80px);
    }
    .ambient-orb-1 {
      top: -120px;
      left: -100px;
      width: 550px;
      height: 550px;
      background: radial-gradient(circle, rgba(37, 99, 235, 0.18) 0%, rgba(255, 255, 255, 0) 70%);
      animation: orbFloat 14s ease-in-out infinite alternate;
    }
    .ambient-orb-2 {
      top: 35%;
      right: -150px;
      width: 600px;
      height: 600px;
      background: radial-gradient(circle, rgba(16, 185, 129, 0.14) 0%, rgba(255, 255, 255, 0) 70%);
      animation: orbFloat 18s ease-in-out infinite alternate-reverse;
    }
    .ambient-orb-3 {
      bottom: 10%;
      left: -100px;
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, rgba(124, 58, 237, 0.12) 0%, rgba(255, 255, 255, 0) 70%);
      animation: orbFloat 16s ease-in-out infinite alternate;
    }

    @keyframes orbFloat {
      0% { transform: translate(0, 0) scale(1); }
      50% { transform: translate(40px, 50px) scale(1.1); }
      100% { transform: translate(-30px, 20px) scale(0.92); }
    }

    /* Floating Micro-Badge */
    @keyframes badgeFloat {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-6px); }
    }

    /* Scroll Reveal Classes with Soft Easing */
    .reveal-on-scroll {
      opacity: 0;
      transform: translateY(35px);
      transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
      will-change: opacity, transform;
    }

    .reveal-on-scroll.from-left {
      transform: translateX(-40px);
    }

    .reveal-on-scroll.from-right {
      transform: translateX(40px);
    }

    .reveal-on-scroll.scale-up {
      transform: scale(0.92) translateY(20px);
    }

    .reveal-on-scroll.revealed {
      opacity: 1;
      transform: translate(0, 0) scale(1);
    }

    /* Staggered Delay Helpers */
    .delay-1 { transition-delay: 0.1s !important; }
    .delay-2 { transition-delay: 0.2s !important; }
    .delay-3 { transition-delay: 0.3s !important; }
    .delay-4 { transition-delay: 0.4s !important; }

    /* Sticky Frosted Glass Navbar */
    .glass-nav {
      background: rgba(255, 255, 255, 0.82);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
      border-bottom: 1px solid rgba(226, 232, 240, 0.8);
      position: sticky;
      top: 0;
      z-index: 1000;
      transition: all 0.35s ease;
    }
    .glass-nav.scrolled {
      background: rgba(255, 255, 255, 0.95);
      box-shadow: 0 10px 30px -10px rgba(15, 23, 42, 0.1);
      border-bottom-color: #cbd5e1;
    }

    /* Hero Section */
    .badge-pill-soft {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 7px 18px;
      background: rgba(37, 99, 235, 0.08);
      border: 1px solid rgba(37, 99, 235, 0.25);
      border-radius: 30px;
      font-size: 13px;
      font-weight: 600;
      color: #2563eb;
      margin-bottom: 24px;
      animation: badgeFloat 4s ease-in-out infinite;
      box-shadow: 0 4px 15px rgba(37, 99, 235, 0.08);
    }

    .hero-title {
      font-size: 48px;
      font-weight: 900;
      line-height: 1.18;
      color: #0f172a;
      letter-spacing: -0.025em;
      margin-bottom: 20px;
    }
    .hero-title span {
      background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #0f2d6b 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .hero-subtitle {
      font-size: 17px;
      color: #475569;
      line-height: 1.65;
      max-width: 660px;
      margin: 0 auto 36px;
    }

    .btn-hero-primary {
      background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
      color: #fff;
      padding: 14px 28px;
      border-radius: 14px;
      font-size: 15px;
      font-weight: 600;
      box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.4);
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
      display: inline-flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
    }
    .btn-hero-primary:hover {
      transform: translateY(-3px) scale(1.02);
      box-shadow: 0 16px 32px -5px rgba(37, 99, 235, 0.5);
      color: #fff;
    }

    .btn-hero-secondary {
      background: #ffffff;
      color: #334155;
      padding: 14px 26px;
      border-radius: 14px;
      font-size: 15px;
      font-weight: 600;
      border: 1.5px solid #cbd5e1;
      box-shadow: 0 4px 12px rgba(0,0,0,0.03);
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
      display: inline-flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
    }
    .btn-hero-secondary:hover {
      background: #f8fafc;
      border-color: #94a3b8;
      color: #0f172a;
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.06);
    }

    /* Glass Cards */
    .glass-card {
      background: rgba(255, 255, 255, 0.92);
      backdrop-filter: blur(14px);
      border: 1px solid #e2e8f0;
      border-radius: 20px;
      padding: 32px;
      box-shadow: 0 8px 30px -5px rgba(15, 23, 42, 0.04);
      transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .glass-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 20px 40px -10px rgba(37, 99, 235, 0.12), 0 0 1px rgba(37, 99, 235, 0.2);
      border-color: #cbd5e1;
    }

    .feature-icon-box {
      width: 54px;
      height: 54px;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 26px;
      margin-bottom: 18px;
      transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .glass-card:hover .feature-icon-box {
      transform: scale(1.12) rotate(4deg);
    }

    /* Stats Banner */
    .stats-bar {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      padding: 28px 36px;
      background: #ffffff;
      border-radius: 22px;
      border: 1px solid #e2e8f0;
      box-shadow: 0 12px 30px -5px rgba(0,0,0,0.04);
      margin-top: 56px;
    }
    .stat-item {
      text-align: center;
    }
    .stat-num {
      font-size: 34px;
      font-weight: 800;
      color: #0f2d6b;
      margin-bottom: 4px;
    }
    .stat-desc {
      font-size: 13px;
      color: #64748b;
      font-weight: 500;
    }

    /* Step Process */
    .step-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 18px;
      padding: 28px 24px;
      text-align: center;
      position: relative;
      transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .step-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 16px 32px -8px rgba(15, 23, 42, 0.08);
      border-color: #93c5fd;
    }
    .step-number {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #eff6ff;
      color: #2563eb;
      font-weight: 800;
      font-size: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 16px;
      border: 2px solid #bfdbfe;
    }

    /* Scroll to Top Button */
    .scroll-top-btn {
      position: fixed;
      bottom: 30px;
      right: 30px;
      width: 46px;
      height: 46px;
      border-radius: 50%;
      background: #2563eb;
      color: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);
      cursor: pointer;
      opacity: 0;
      pointer-events: none;
      transform: translateY(20px);
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
      z-index: 999;
      border: none;
    }
    .scroll-top-btn.visible {
      opacity: 1;
      pointer-events: auto;
      transform: translateY(0);
    }
    .scroll-top-btn:hover {
      background: #1d4ed8;
      transform: translateY(-3px) scale(1.08);
    }
  </style>
</head>
<body>

<div class="ambient-orb ambient-orb-1"></div>
<div class="ambient-orb ambient-orb-2"></div>
<div class="ambient-orb ambient-orb-3"></div>

<!-- NAVIGATION BAR -->
<nav class="glass-nav" id="mainNav">
  <div style="max-width: 1200px; margin: 0 auto; padding: 14px 24px; display: flex; align-items: center; justify-content: space-between;">
    <a href="{{ url('/') }}" style="display: flex; align-items: center; text-decoration: none;">
      <img src="{{ asset('images/logo.png') }}" alt="PT Nusantara Digital Express" style="height: 48px; width: auto; object-fit: contain;">
    </a>

    <div style="display: flex; align-items: center; gap: 14px;">
      <a href="{{ route('customer.catalog.index') }}" style="font-size: 14px; font-weight: 600; color: #475569; padding: 8px 14px; border-radius: 8px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.color='#2563eb'" onmouseout="this.style.color='#475569'">
        Daftar Barang
      </a>

      @auth
        @if(Auth::user()->role === 'admin')
          <a href="{{ route('admin.dashboard') }}" class="btn-hero-primary" style="padding: 9px 18px; font-size: 13.5px; border-radius: 10px;">
            Panel Admin
          </a>
        @else
          <a href="{{ route('customer.dashboard') }}" class="btn-hero-primary" style="padding: 9px 18px; font-size: 13.5px; border-radius: 10px;">
            Dashboard Saya
          </a>
        @endif
      @else
        <a href="{{ route('login') }}" style="font-size: 14px; font-weight: 600; color: #0f2d6b; padding: 8px 16px; text-decoration: none;">
          Masuk
        </a>
        <a href="{{ route('register') }}" class="btn-hero-primary" style="padding: 9px 18px; font-size: 13.5px; border-radius: 10px;">
          Daftar Peminjam
        </a>
      @endauth
    </div>
  </div>
</nav>

<!-- HERO SECTION -->
<section style="max-width: 1200px; margin: 64px auto 40px; padding: 0 24px; position: relative; z-index: 1;">
  <div style="text-align: center; max-width: 840px; margin: 0 auto;">
    <div class="badge-pill-soft">
      <span>Sistem Peminjaman Inventaris Perusahaan 2.0</span>
    </div>

    <h1 class="hero-title reveal-on-scroll">
      Peminjaman Aset &amp; Barang Kantor <br><span>Instan, Transparan &amp; Terintegrasi</span>
    </h1>

    <p class="hero-subtitle reveal-on-scroll delay-1">
      Platform resmi PT Nusantara Digital Express untuk pengelolaan, verifikasi jaminan identitas, dan peminjaman peralatan operasional kerja secara digital, cepat, dan aman.
    </p>

    <div class="reveal-on-scroll delay-2" style="display: flex; align-items: center; justify-content: center; gap: 14px; flex-wrap: wrap;">
      <a href="{{ route('customer.catalog.index') }}" class="btn-hero-primary">
        <span>Jelajahi Katalog Barang</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
      </a>
      @guest
        <a href="{{ route('login') }}" class="btn-hero-secondary">
          <span>Login Akun Peminjam</span>
        </a>
      @endguest
    </div>
  </div>

  <!-- STATS HIGHLIGHT BAR -->
  <div class="stats-bar reveal-on-scroll delay-3">
    <div class="stat-item">
      <div class="stat-num">100+</div>
      <div class="stat-desc">Barang &amp; Aset Siap Pakai</div>
    </div>
    <div class="stat-item">
      <div class="stat-num">99.8%</div>
      <div class="stat-desc">Pengembalian Tepat Waktu</div>
    </div>
    <div class="stat-item">
      <div class="stat-num">100%</div>
      <div class="stat-desc">Verifikasi KTP / Dokumen Aman</div>
    </div>
    <div class="stat-item">
      <div class="stat-num">24/7</div>
      <div class="stat-desc">Akses Pengajuan Kapan Saja</div>
    </div>
  </div>
</section>

<!-- HOW IT WORKS SECTION -->
<section style="max-width: 1200px; margin: 90px auto; padding: 0 24px; position: relative; z-index: 1;">
  <div style="text-align: center; margin-bottom: 48px;" class="reveal-on-scroll">
    <span style="font-size: 12.5px; font-weight: 700; color: #2563eb; text-transform: uppercase; letter-spacing: 0.08em;">Alur Mudah</span>
    <h2 style="font-size: 32px; font-weight: 800; color: #0f172a; margin-top: 6px; margin-bottom: 10px;">4 Langkah Mudah Meminjam Barang</h2>
    <p style="font-size: 15px; color: #64748b; max-width: 600px; margin: 0 auto;">Proses peminjaman inventaris dirancang efisien dan tanpa birokrasi berbelit.</p>
  </div>

  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px;">
    <!-- Step 1 -->
    <div class="step-card reveal-on-scroll delay-1">
      <div class="step-number">1</div>
      <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">Pilih Barang</h3>
      <p style="font-size: 13px; color: #64748b; line-height: 1.6;">Cari barang di katalog dan periksa stok unit serta spesifikasi fisiknya.</p>
    </div>

    <!-- Step 2 -->
    <div class="step-card reveal-on-scroll delay-2">
      <div class="step-number">2</div>
      <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">Isi Pengajuan &amp; KTP</h3>
      <p style="font-size: 13px; color: #64748b; line-height: 1.6;">Tentukan durasi, lokasi pemakaian, dan unggah foto KTP sebagai jaminan.</p>
    </div>

    <!-- Step 3 -->
    <div class="step-card reveal-on-scroll delay-3">
      <div class="step-number">3</div>
      <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">Persetujuan Admin</h3>
      <p style="font-size: 13px; color: #64748b; line-height: 1.6;">Admin memverifikasi permohonan dan menyiapkan barang di gudang.</p>
    </div>

    <!-- Step 4 -->
    <div class="step-card reveal-on-scroll delay-4">
      <div class="step-number">4</div>
      <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">Ambil &amp; Pakai</h3>
      <p style="font-size: 13px; color: #64748b; line-height: 1.6;">Ambil barang di lokasi gudang dan gunakan sesuai peruntukan operasional.</p>
    </div>
  </div>
</section>

<!-- FEATURES SECTION -->
<section style="max-width: 1200px; margin: 90px auto; padding: 0 24px; position: relative; z-index: 1;">
  <div style="text-align: center; margin-bottom: 48px;" class="reveal-on-scroll">
    <span style="font-size: 12.5px; font-weight: 700; color: #16a34a; text-transform: uppercase; letter-spacing: 0.08em;">Fitur Modern</span>
    <h2 style="font-size: 32px; font-weight: 800; color: #0f172a; margin-top: 6px; margin-bottom: 10px;">Keunggulan Sistem NDE</h2>
    <p style="font-size: 15px; color: #64748b; max-width: 600px; margin: 0 auto;">Dirancang khusus untuk kenyamanan peminjam dan efisiensi pengelolaan tim logistik.</p>
  </div>

  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 24px;">
    <!-- Feature 1 -->
    <div class="glass-card reveal-on-scroll delay-1">
      <div class="feature-icon-box" style="background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
      </div>
      <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 10px;">Katalog Barang Realtime</h3>
      <p style="font-size: 13.5px; color: #64748b; line-height: 1.6;">
        Pantau ketersediaan stok barang, spesifikasi teknis, serta lokasi penyimpanan fisik secara akurat sebelum mengajukan pinjaman.
      </p>
    </div>

    <!-- Feature 2 -->
    <div class="glass-card reveal-on-scroll delay-2">
      <div class="feature-icon-box" style="background: #f0fdf4; color: #16a34a; display: flex; align-items: center; justify-content: center;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><line x1="7" y1="8" x2="11" y2="8"/><line x1="7" y1="12" x2="17" y2="12"/><line x1="7" y1="16" x2="14" y2="16"/></svg>
      </div>
      <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 10px;">Verifikasi Jaminan Digital</h3>
      <p style="font-size: 13.5px; color: #64748b; line-height: 1.6;">
        Cukup unggah foto KTP/KK sekali sebagai dokumen jaminan identitas resmi. Tersimpan aman dan siap digunakan untuk peminjaman berikutnya.
      </p>
    </div>

    <!-- Feature 3 -->
    <div class="glass-card reveal-on-scroll delay-3">
      <div class="feature-icon-box" style="background: #fffbeb; color: #d97706; display: flex; align-items: center; justify-content: center;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
      </div>
      <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 10px;">Tracking GPS &amp; Durasi</h3>
      <p style="font-size: 13.5px; color: #64748b; line-height: 1.6;">
        Dukungan deteksi lokasi pemakaian GPS dan penghitungan otomatis tanggal pengembalian agar aset terpantau secara transparan.
      </p>
    </div>

    <!-- Feature 4 -->
    <div class="glass-card reveal-on-scroll delay-4">
      <div class="feature-icon-box" style="background: #f5f3ff; color: #7c3aed; display: flex; align-items: center; justify-content: center;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
      </div>
      <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 10px;">Kelola Denda &amp; Pembayaran</h3>
      <p style="font-size: 13.5px; color: #64748b; line-height: 1.6;">
        Sistem denda transparan dengan opsi pembayaran Transfer QRIS atau Tunai Cash saat pengembalian barang ke gudang.
      </p>
    </div>
  </div>
</section>

<!-- CALL TO ACTION BANNER -->
<section style="max-width: 1200px; margin: 90px auto; padding: 0 24px; position: relative; z-index: 1;" class="reveal-on-scroll scale-up">
  <div style="background: linear-gradient(135deg, #0f2d6b 0%, #1e40af 50%, #2563eb 100%); border-radius: 24px; padding: 56px 40px; text-align: center; color: white; box-shadow: 0 20px 45px -10px rgba(15, 45, 107, 0.4); position: relative; overflow: hidden;">
    <div style="position: absolute; top: -60px; right: -60px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    
    <h2 style="font-size: 34px; font-weight: 800; margin-bottom: 14px; position: relative;">Siap Mengajukan Peminjaman Barang?</h2>
    <p style="font-size: 16px; color: #cbd5e1; max-width: 600px; margin: 0 auto 32px; line-height: 1.6; position: relative;">
      Jelajahi ratusan inventaris peralatan kantor, teknologi, dan perkakas kerja PT Nusantara Digital Express sekarang.
    </p>
    <div style="display: flex; justify-content: center; gap: 14px; flex-wrap: wrap; position: relative;">
      <a href="{{ route('customer.catalog.index') }}" class="btn-hero-primary" style="background: #ffffff; color: #0f2d6b; box-shadow: 0 8px 20px rgba(0,0,0,0.15); font-weight: 700;">
        Lihat Semua Barang
      </a>
      @guest
        <a href="{{ route('register') }}" class="btn-hero-secondary" style="background: rgba(255,255,255,0.15); color: #ffffff; border-color: rgba(255,255,255,0.3);">
          Daftar Akun Gratis
        </a>
      @endguest
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer style="background: #ffffff; border-top: 1px solid #e2e8f0; padding: 40px 24px; margin-top: 80px; text-align: center; font-size: 13px; color: #64748b;">
  <div style="max-width: 1200px; margin: 0 auto; display: flex; flex-direction: column; align-items: center; gap: 10px;">
    <div style="font-weight: 800; color: #0f2d6b; font-size: 15px;">PT Nusantara Digital Express</div>
    <div>© {{ date('Y') }} NDE Peminjaman Barang &amp; Inventaris. Seluruh hak cipta dilindungi.</div>
  </div>
</footer>

<!-- Scroll to top floating button -->
<button class="scroll-top-btn" id="scrollTopBtn" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" title="Kembali ke Atas">
  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"/></svg>
</button>

<script>
  // Ultra-Smooth Scroll Observer
  document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
      threshold: 0.12,
      rootMargin: '0px 0px -40px 0px'
    };

    const revealObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('revealed');
        }
      });
    }, observerOptions);

    document.querySelectorAll('.reveal-on-scroll').forEach(el => {
      revealObserver.observe(el);
    });

    // Sticky Navbar shadow & Scroll to top toggle on scroll
    const nav = document.getElementById('mainNav');
    const scrollBtn = document.getElementById('scrollTopBtn');

    window.addEventListener('scroll', function() {
      if (window.scrollY > 30) {
        nav.classList.add('scrolled');
      } else {
        nav.classList.remove('scrolled');
      }

      if (window.scrollY > 300) {
        scrollBtn.classList.add('visible');
      } else {
        scrollBtn.classList.remove('visible');
      }
    }, { passive: true });
  });
</script>

</body>
</html>
