<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sistem Peminjaman Barang – PT Nusantara Digital Express</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
</head>
<body>

  <!-- ===================== NAVBAR ===================== -->
  <header class="navbar">
    <div class="nav-container">
      <a href="{{ route('landing') }}" class="nav-brand" style="text-decoration:none; display:flex; align-items:center;">
        <img src="{{ asset('images/logo.png') }}" alt="PT Nusantara Digital Express" style="height: 48px; width: auto; object-fit: contain;">
      </a>

      <nav class="nav-links">
        <a href="#beranda" class="nav-link active">Beranda</a>
        <a href="#katalog" class="nav-link">Katalog Barang</a>
        <a href="#kontak" class="nav-link">Kontak</a>
      </nav>

      <div class="nav-actions">
        @auth
          @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-sm">Dashboard Admin</a>
          @else
            <a href="{{ route('customer.dashboard') }}" class="btn btn-primary btn-sm">Dashboard Saya</a>
          @endif
        @else
          <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Masuk</a>
          <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
        @endauth
      </div>
    </div>
  </header>

  <!-- ===================== HERO SECTION ===================== -->
  <section class="hero-section" id="beranda">
    <!-- Dynamic Aesthetic Crossfade Background Slider -->
    <div class="hero-slider">
      <div class="hero-slide active" style="background-image: url('{{ asset('images/hero/hero-1.jpg') }}');"></div>
      <div class="hero-slide" style="background-image: url('{{ asset('images/hero/hero-2.jpg') }}');"></div>
      <div class="hero-slide" style="background-image: url('{{ asset('images/hero/hero-3.jpg') }}');"></div>
    </div>

    <!-- Tech Aesthetic Deep Gradient & Blur Overlay -->
    <div class="hero-overlay"></div>

    <!-- Hero Content (Floating in front of background) -->
    <div class="hero-container">
      <div class="hero-content-box">
        <div class="hero-badge">
          <span class="badge-dot"></span>
          Platform Inventaris Digital Resmi PT NDE
        </div>
        <h1 class="hero-title">
          Kelola &amp; Pinjam <span class="text-gradient">Barang Perusahaan</span> Lebih Cepat &amp; Praktis
        </h1>
        <p class="hero-desc">
          Sistem digital terintegrasi untuk mempermudah permohonan, persetujuan, dan pengembalian aset inventaris PT Nusantara Digital Express secara real-time dan terpercaya.
        </p>
        <div class="hero-actions">
          <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
            Mulai Pinjam Sekarang
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
          </a>
          <a href="#katalog" class="btn btn-glass btn-lg">Lihat Katalog</a>
        </div>
        <div class="hero-stats">
          <div class="stat-item"><span class="stat-num">{{ $stats['total_items'] }}+</span><span class="stat-lbl">Barang Tersedia</span></div>
          <div class="stat-divider"></div>
          <div class="stat-item"><span class="stat-num">{{ $stats['total_customers'] }}+</span><span class="stat-lbl">Pengguna Terdaftar</span></div>
          <div class="stat-divider"></div>
          <div class="stat-item"><span class="stat-num">{{ $stats['satisfaction_rate'] }}</span><span class="stat-lbl">Kepuasan Pengguna</span></div>
        </div>
      </div>

      <!-- Aesthetic Slider Dots Indicator -->
      <div class="hero-slider-dots">
        <button type="button" class="dot active" onclick="goToSlide(0)" title="Slide 1: Digital Workspace"></button>
        <button type="button" class="dot" onclick="goToSlide(1)" title="Slide 2: Smart Inventory Hub"></button>
        <button type="button" class="dot" onclick="goToSlide(2)" title="Slide 3: Creative Tech Gear"></button>
      </div>
    </div>
  </section>

  <!-- ===================== KATALOG SECTION ===================== -->
  <section class="section katalog-section" id="katalog">
    <div class="section-container">
      <div class="section-header" data-reveal="fade-up">
        <div class="section-tag">KATALOG PILIHAN</div>
        <h2 class="section-title">Barang Siap Dipinjam</h2>
        <p class="section-desc">Pilihan inventaris berkualitas siap digunakan untuk mendukung operasional perusahaan.</p>
      </div>

      <div class="items-grid stagger-children">
        @forelse($items as $item)
          <div class="item-card" data-reveal="fade-up">
            <div class="item-card-img" style="height: 180px; overflow: hidden; border-radius: 10px 10px 0 0;">
              @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" style="width: 100%; height: 100%; object-fit: cover;">
              @else
                <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #0f2d6b 0%, #1a3f8f 100%); display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; gap: 8px;">
                  <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                  <span style="font-size: 11px; font-weight: 600; letter-spacing: 0.5px; opacity: 0.8; text-transform: uppercase;">Foto Barang</span>
                </div>
              @endif
            </div>
            <div class="item-card-body">
              <div class="item-card-category">{{ $item->category->name }}</div>
              <div class="item-card-name">{{ $item->name }}</div>
              <div class="item-card-meta">
                <div class="item-card-meta-row"><span>Kondisi</span><span>{{ $item->condition }}</span></div>
                <div class="item-card-meta-row"><span>Stok</span><span>{{ $item->stock }} unit</span></div>
                <div class="item-card-meta-row"><span>Status</span><span class="badge badge-success">{{ $item->status }}</span></div>
              </div>
              <div class="item-price">
                @if($item->price_per_day > 0)
                  Rp {{ number_format($item->price_per_day, 0, ',', '.') }}<span class="item-price-sub">/hari</span>
                @else
                  Gratis
                @endif
              </div>
              <a href="{{ route('login') }}" class="btn btn-primary btn-full btn-sm">Pinjam Barang</a>
            </div>
          </div>
        @empty
          <div style="grid-column: 1/-1; text-align: center; color: var(--gray-500); padding: 32px;">
            Belum ada barang yang tersedia di katalog saat ini.
          </div>
        @endforelse
      </div>

      <!-- Modern Catalog CTA Banner -->
      <div class="catalog-cta-banner" data-reveal="fade-up" data-delay="200">
        <div class="catalog-cta-content">
          <div class="catalog-cta-icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
          </div>
          <div>
            <h3 class="catalog-cta-title">Butuh Perangkat atau Peralatan Kantor Lainnya?</h3>
            <p class="catalog-cta-desc">Masuk ke Portal Customer untuk mengakses seluruh inventaris lengkap dengan pencarian kategori dan formulir permohonan pinjam cepat.</p>
          </div>
        </div>
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg catalog-cta-btn">
          <span>Lihat Semua Barang</span>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
      </div>
    </div>
  </section>

  <!-- ===================== FOOTER ===================== -->
  <footer class="footer" id="kontak">
    <div class="footer-container">
      <!-- Col 1: Brand & Bio -->
      <div class="footer-col footer-col-brand" data-reveal="fade-up">
        <div class="nav-brand" style="display:inline-block; background:#fff; padding:6px 12px; border-radius:8px; margin-bottom:10px;">
          <img src="{{ asset('images/logo.png') }}" alt="PT Nusantara Digital Express" style="height: 44px; width: auto; object-fit: contain; display:block;">
        </div>
        <p class="footer-desc">
          Platform digital manajemen inventaris dan peminjaman aset perusahaan secara real-time, akurat, dan transparan untuk meningkatkan produktivitas seluruh tim.
        </p>
        <div class="footer-badges">
          <span class="footer-badge-item">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            Sistem Terverifikasi
          </span>
          <span class="footer-badge-item">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 14 14"/></svg>
            Layanan 24/7
          </span>
        </div>
      </div>

      <!-- Col 2: Akses Cepat -->
      <div class="footer-col" data-reveal="fade-up" data-delay="100">
        <h4 class="footer-col-title">Akses Cepat</h4>
        <ul class="footer-col-links">
          <li><a href="#beranda">Beranda</a></li>
          <li><a href="#katalog">Katalog Barang</a></li>
          <li><a href="{{ route('login') }}">Login Customer</a></li>
          <li><a href="{{ route('register') }}">Daftar Akun Baru</a></li>
          <li><a href="{{ route('admin.login') }}">Portal Administrator</a></li>
        </ul>
      </div>

      <!-- Col 3: Layanan & Informasi -->
      <div class="footer-col" data-reveal="fade-up" data-delay="200">
        <h4 class="footer-col-title">Layanan &amp; Info</h4>
        <ul class="footer-col-links">
          <li><a href="#alur">Alur Peminjaman</a></li>
          <li><a href="#fitur">Fitur Unggulan</a></li>
          <li><a href="#">Syarat &amp; Ketentuan</a></li>
          <li><a href="#">Panduan Jaminan &amp; KTP</a></li>
          <li><a href="#">Bantuan &amp; FAQ</a></li>
        </ul>
      </div>

      <!-- Col 4: Kontak & Kantor -->
      <div class="footer-col" data-reveal="fade-up" data-delay="300">
        <h4 class="footer-col-title">Kantor Operasional</h4>
        <div class="footer-contact-item">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          <span>Gedung NDE Tower Lt. 5, Jl. Sudirman No. 88, Jakarta</span>
        </div>
        <div class="footer-contact-item">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          <span>inventaris@nusantara-express.co.id</span>
        </div>
        <div class="footer-contact-item">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          <span>+62 (021) 555-8900</span>
        </div>
      </div>
    </div>

    <!-- Footer Bottom Copyright Bar -->
    <div class="footer-bottom">
      <div class="footer-bottom-container">
        <div>&copy; {{ date('Y') }} PT Nusantara Digital Express. Hak Cipta Dilindungi Undang-Undang.</div>
        <div class="footer-bottom-links">
          <a href="#">Privasi</a>
          <span>&bull;</span>
          <a href="#">Syarat Penggunaan</a>
          <span>&bull;</span>
          <a href="#">Keamanan Sistem</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- ===================== SCRIPTS ===================== -->
  <script>
    /* ── Hero Background Slider ── */
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-slider-dots .dot');
    let slideTimer = null;

    function showSlide(index) {
      if (!slides.length) return;
      slides.forEach((slide, idx) => { slide.classList.toggle('active', idx === index); });
      dots.forEach((dot, idx) => { dot.classList.toggle('active', idx === index); });
      currentSlide = index;
    }
    function nextSlide() { showSlide((currentSlide + 1) % slides.length); }
    function goToSlide(index) { clearInterval(slideTimer); showSlide(index); startSlider(); }
    function startSlider() { if (slideTimer) clearInterval(slideTimer); slideTimer = setInterval(nextSlide, 5000); }

    /* ── Scroll Reveal (Intersection Observer) ── */
    function initScrollReveal() {
      const revealEls = document.querySelectorAll('[data-reveal]');
      if (!revealEls.length) return;

      // Safety Fallback: Ensure all elements reveal within 1 second even if observer is delayed
      setTimeout(() => {
        revealEls.forEach(el => el.classList.add('is-revealed'));
      }, 1000);

      if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              entry.target.classList.add('is-revealed');
              observer.unobserve(entry.target);
            }
          });
        }, {
          threshold: 0.02,
          rootMargin: '50px 0px 50px 0px'
        });

        revealEls.forEach(el => {
          const rect = el.getBoundingClientRect();
          if (rect.top < window.innerHeight && rect.bottom > 0) {
            el.classList.add('is-revealed');
          } else {
            observer.observe(el);
          }
        });
      } else {
        revealEls.forEach(el => el.classList.add('is-revealed'));
      }
    }

    /* ── Active NavLink on scroll ── */
    function initActiveNav() {
      const sections = document.querySelectorAll('section[id], footer[id]');
      const navLinks = document.querySelectorAll('.nav-link');
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const id = entry.target.getAttribute('id');
            navLinks.forEach(link => {
              link.classList.toggle('active', link.getAttribute('href') === '#' + id);
            });
          }
        });
      }, { threshold: 0.35 });
      sections.forEach(s => observer.observe(s));
    }

    document.addEventListener('DOMContentLoaded', () => {
      startSlider();
      initScrollReveal();
      initActiveNav();
    });
  </script>
</body>
</html>
