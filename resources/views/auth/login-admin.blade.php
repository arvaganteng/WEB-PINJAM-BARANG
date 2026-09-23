<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login Administrator – PT Nusantara Digital Express</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body class="auth-body">

  <div class="auth-layout">
    <!-- LEFT PANEL -->
    <div class="auth-left">
      <div class="auth-left-content">
        <a href="{{ route('landing') }}" class="back-btn">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
          Kembali ke Beranda
        </a>
        <div class="auth-brand" style="margin: 10px 0 28px; display: flex; justify-content: center; width: 100%;">
          <img src="{{ asset('images/logo.png') }}" alt="PT Nusantara Digital Express" style="height: 120px; width: auto; max-width: 330px; object-fit: contain; mix-blend-mode: multiply; filter: contrast(1.05); display: block;">
        </div>
        <div class="auth-welcome">
          <h1>Portal Administrator</h1>
          <p>Masuk dengan kredensial administrator untuk mengelola inventaris, verifikasi peminjaman, dan memantau status aset perusahaan.</p>
        </div>
        <div class="auth-illustration">
          <div class="ill-card">
            <div class="ill-row"><div class="ill-dot green"></div><div class="ill-line long"></div></div>
            <div class="ill-row"><div class="ill-dot blue"></div><div class="ill-line medium"></div></div>
            <div class="ill-row"><div class="ill-dot yellow"></div><div class="ill-line short"></div></div>
            <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--gray-100)">
              <div style="font-size:11px;color:var(--gray-500);margin-bottom:8px">Keamanan Tingkat Perusahaan</div>
              <div style="display:flex;gap:8px;flex-wrap:wrap">
                <span class="badge badge-navy">SSL 256-bit</span>
                <span class="badge badge-success">Role-Based Access</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="auth-right">
      <div class="auth-form-box">
        <div class="auth-form-header">
          <div class="admin-badge-pill">Akses Khusus Admin</div>
          <h2>Login Administrator</h2>
          <p>Masukkan email dan password admin Anda</p>
        </div>

        @if($errors->any())
          <div class="info-alert red" style="margin-bottom: 16px;">
            {{ $errors->first() }}
          </div>
        @endif

        <form class="auth-form" method="POST" action="{{ route('admin.login.post') }}">
          @csrf
          <div class="form-group">
            <label class="form-label">Email Administrator</label>
            <div class="input-wrapper">
              <span class="input-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></span>
              <input type="email" name="email" class="form-input" placeholder="admin@nde.co.id" value="{{ old('email', 'admin@nde.co.id') }}" required autofocus/>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Password</label>
            <div class="input-wrapper">
              <span class="input-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
              <input type="password" name="password" class="form-input" placeholder="••••••••" value="password" id="admin-pass" required/>
              <button type="button" class="input-icon-right" onclick="togglePass('admin-pass')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
          </div>
          <!-- Google reCAPTCHA -->
          @if(config('services.recaptcha.site_key'))
            <div style="margin: 12px 0 16px; display: flex; justify-content: center;">
              <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
            </div>
          @endif

          <button type="submit" class="btn btn-primary btn-full btn-lg">
            Masuk sebagai Admin
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
          </button>
        </form>

        <div class="auth-divider">atau</div>
        <a href="{{ route('login') }}" class="btn btn-outline btn-full">Login sebagai Customer</a>
      </div>
    </div>
  </div>

  <script>
    function togglePass(id) {
      const el = document.getElementById(id);
      if (el) el.type = el.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>
</html>
