<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Daftar Akun – PT Nusantara Digital Express</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
  <style>
    /* ===== REGISTER PAGE ===== */
    html, body { height: 100%; margin: 0; }
    body { font-family: 'Inter', sans-serif; background: #f1f5f9; }

    .reg-layout {
      display: flex;
      min-height: 100vh;
    }

    /* ---- LEFT PANEL ---- */
    .reg-left {
      width: 400px;
      flex-shrink: 0;
      background: linear-gradient(155deg, #0c1f5e 0%, #1a3a9e 55%, #2563eb 100%);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 40px 36px;
      position: sticky;
      top: 0;
      height: 100vh;
      overflow: hidden;
    }

    .reg-left::before {
      content: '';
      position: absolute;
      width: 300px; height: 300px;
      border-radius: 50%;
      background: rgba(255,255,255,0.05);
      top: -80px; right: -80px;
    }
    .reg-left::after {
      content: '';
      position: absolute;
      width: 200px; height: 200px;
      border-radius: 50%;
      background: rgba(255,255,255,0.04);
      bottom: 60px; left: -60px;
    }

    .reg-left-top { position: relative; z-index: 1; }
    .reg-left-bottom { position: relative; z-index: 1; }

    .back-btn-reg {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      color: rgba(255,255,255,0.65);
      font-size: 13px;
      font-weight: 500;
      text-decoration: none;
      transition: color .2s;
      margin-bottom: 36px;
    }
    .back-btn-reg:hover { color: #fff; }

    .reg-brand {
      display: flex;
      align-items: center;
      gap: 14px;
      margin-bottom: 36px;
    }
    .reg-brand-icon {
      width: 52px; height: 52px;
      background: rgba(255,255,255,0.15);
      border: 1px solid rgba(255,255,255,0.25);
      border-radius: 16px;
      display: flex; align-items: center; justify-content: center;
      backdrop-filter: blur(8px);
    }
    .reg-brand-name { font-size: 16px; font-weight: 800; color: #fff; line-height: 1.25; }
    .reg-brand-sub  { font-size: 12px; color: rgba(255,255,255,0.55); font-weight: 500; margin-top: 2px; }

    .reg-headline { color: #fff; margin-bottom: 12px; }
    .reg-headline h1 { font-size: 26px; font-weight: 800; line-height: 1.25; margin: 0 0 10px; }
    .reg-headline p  { font-size: 14px; color: rgba(255,255,255,0.65); line-height: 1.7; margin: 0; }

    .reg-benefits { margin-top: 36px; display: flex; flex-direction: column; gap: 14px; }
    .reg-benefit {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 12px 14px;
      background: rgba(255,255,255,0.07);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 12px;
      transition: background .2s;
    }
    .reg-benefit:hover { background: rgba(255,255,255,0.11); }
    .reg-benefit-icon {
      width: 38px; height: 38px;
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 18px;
      flex-shrink: 0;
    }
    .reg-benefit-icon.green  { background: rgba(34,197,94,.2);  }
    .reg-benefit-icon.blue   { background: rgba(59,130,246,.2); }
    .reg-benefit-icon.purple { background: rgba(168,85,247,.2); }
    .reg-benefit-icon.orange { background: rgba(251,146,60,.2); }
    .reg-benefit-text { font-size: 13px; color: rgba(255,255,255,0.85); font-weight: 500; line-height: 1.4; }
    .reg-benefit-text strong { display: block; color: #fff; font-weight: 700; margin-bottom: 1px; }

    .reg-left-footer {
      font-size: 11px;
      color: rgba(255,255,255,0.35);
      text-align: center;
      position: relative; z-index: 1;
    }

    /* ---- RIGHT PANEL ---- */
    .reg-right {
      flex: 1;
      overflow-y: auto;
      display: flex;
      align-items: flex-start;
      justify-content: center;
      padding: 48px 32px;
      background: #f1f5f9;
    }

    .reg-form-card {
      width: 100%;
      max-width: 560px;
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 4px 6px -1px rgba(0,0,0,.06), 0 10px 40px -10px rgba(0,0,0,.1);
      padding: 36px 36px 32px;
    }

    .reg-form-header { margin-bottom: 28px; }
    .reg-form-header h2 {
      font-size: 22px; font-weight: 800; color: #0f172a; margin: 0 0 6px;
    }
    .reg-form-header p { font-size: 13.5px; color: #64748b; margin: 0; }

    /* Section divider */
    .form-sec {
      display: flex;
      align-items: center;
      gap: 10px;
      margin: 22px 0 16px;
    }
    .form-sec-icon {
      width: 28px; height: 28px;
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .form-sec-icon.blue   { background: #eff6ff; color: #2563eb; }
    .form-sec-icon.purple { background: #faf5ff; color: #9333ea; }
    .form-sec-label {
      font-size: 11px; font-weight: 700;
      text-transform: uppercase; letter-spacing: .08em;
      color: #64748b;
    }
    .form-sec-line { flex: 1; height: 1px; background: #e2e8f0; }

    /* Password meter */
    .pw-meter { margin-top: 8px; }
    .pw-meter-bars { display: flex; gap: 4px; }
    .pw-meter-bar {
      flex: 1; height: 4px; border-radius: 99px;
      background: #e2e8f0; transition: background .3s;
    }
    .pw-meter-label {
      font-size: 11.5px; font-weight: 600;
      margin-top: 5px; color: #94a3b8;
    }

    /* Password checklist */
    .pw-checklist {
      display: none;
      margin-top: 10px;
      padding: 12px 14px;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
    }
    .pw-checklist-title {
      font-size: 10px; font-weight: 700;
      color: #94a3b8; text-transform: uppercase;
      letter-spacing: .08em; margin-bottom: 8px;
    }
    .pw-req-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 5px 12px; }
    .pw-req {
      font-size: 12px; font-weight: 500; color: #ef4444;
      display: flex; align-items: center; gap: 6px;
      transition: color .2s;
    }
    .pw-req.ok { color: #16a34a; }
    .pw-req-dot {
      width: 6px; height: 6px; border-radius: 50%;
      background: currentColor; flex-shrink: 0;
    }

    /* ======= PHONE COUNTRY PICKER ======= */
    /* ======= PHONE COUNTRY PICKER ======= */
    .phone-input-group {
      display: flex;
      align-items: center;
      height: 50px;
      gap: 0;
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      overflow: visible;
      position: relative;
      background: #fff;
      transition: border-color .2s, box-shadow .2s;
    }
    .phone-input-group:focus-within {
      border-color: #2563eb;
      box-shadow: 0 0 0 4px rgba(37,99,235,0.12);
    }

    /* Country selector trigger button */
    .country-trigger {
      display: flex;
      align-items: center;
      height: 100%;
      gap: 9px;
      padding: 0 14px 0 16px;
      background: #f8fafc;
      border: none;
      border-right: 2px solid #e2e8f0;
      border-radius: 10px 0 0 10px;
      cursor: pointer;
      min-width: 118px;
      font-family: 'Inter', sans-serif;
      font-size: 15px;
      font-weight: 700;
      color: #1e293b;
      transition: background .2s;
      outline: none;
      user-select: none;
      white-space: nowrap;
    }
    .country-trigger:hover { background: #f1f5f9; }

    .country-trigger .dial-code {
      font-size: 15px;
      font-weight: 800;
      color: #2563eb;
      letter-spacing: 0.2px;
    }
    .country-trigger .caret {
      margin-left: 2px;
      color: #64748b;
      transition: transform .2s;
      flex-shrink: 0;
    }
    .country-trigger.open .caret { transform: rotate(180deg); }

    /* Phone number input */
    .phone-number-input {
      flex: 1;
      height: 100% !important;
      border: none !important;
      border-radius: 0 10px 10px 0 !important;
      outline: none !important;
      box-shadow: none !important;
      padding: 0 18px !important;
      font-size: 15.5px !important;
      font-weight: 600;
      color: #0f172a;
      background: #fff;
      letter-spacing: 0.6px;
    }
    .phone-number-input::placeholder {
      color: #94a3b8;
      font-weight: 400;
      letter-spacing: 0.6px;
    }
    .phone-number-input:focus { box-shadow: none !important; border-color: transparent !important; }

    /* Dropdown */
    .country-dropdown {
      position: absolute;
      top: calc(100% + 8px);
      left: 0;
      width: 340px;
      background: #fff;
      border: 1.5px solid #e2e8f0;
      border-radius: 14px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.14), 0 4px 16px rgba(0,0,0,0.06);
      z-index: 9999;
      display: none;
      overflow: hidden;
      animation: dropIn .18s ease;
    }
    .country-dropdown.open { display: block; }

    @keyframes dropIn {
      from { opacity: 0; transform: translateY(-8px) scale(.98); }
      to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* Search inside dropdown */
    .country-search-wrap {
      padding: 12px 14px 10px;
      border-bottom: 1px solid #f1f5f9;
      position: sticky;
      top: 0;
      background: #fff;
      z-index: 1;
    }
    .country-search {
      width: 100%;
      box-sizing: border-box;
      border: 1.5px solid #e2e8f0;
      border-radius: 9px;
      padding: 9px 14px 9px 36px;
      font-family: 'Inter', sans-serif;
      font-size: 13.5px;
      color: #1e293b;
      outline: none;
      background: #f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E") no-repeat 12px center;
      transition: border-color .2s;
    }
    .country-search:focus { border-color: #2563eb; background-color: #fff; }

    /* Country list */
    .country-list {
      max-height: 250px;
      overflow-y: auto;
      padding: 6px 0;
    }
    .country-list::-webkit-scrollbar { width: 5px; }
    .country-list::-webkit-scrollbar-track { background: transparent; }
    .country-list::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }

    .country-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 16px;
      cursor: pointer;
      transition: background .12s;
      font-size: 14px;
    }
    .country-item:hover { background: #f0f7ff; }
    .country-item.selected { background: #eff6ff; }

    .country-item .ci-flag  { flex-shrink: 0; }
    .country-item .ci-flag img { width: 28px; height: 19px; border-radius: 4px; object-fit: cover; box-shadow: 0 1px 3px rgba(0,0,0,0.18); display: block; }
    .country-item .ci-name  { flex: 1; color: #1e293b; font-weight: 500; font-size: 14px; }
    .country-item .ci-dial  { font-size: 13px; font-weight: 700; color: #2563eb; flex-shrink: 0; }
    .flag-img { width: 28px; height: 19px; border-radius: 4px; object-fit: cover; box-shadow: 0 1px 3px rgba(0,0,0,0.20); display: block; flex-shrink: 0; }

    .country-no-result {
      text-align: center;
      padding: 20px;
      font-size: 13px;
      color: #94a3b8;
    }

    /* Footer link */
    .reg-footer-link {
      text-align: center;
      margin-top: 20px;
      font-size: 13.5px;
      color: #64748b;
    }
    .reg-footer-link a { color: #2563eb; font-weight: 700; }
    .reg-footer-link a:hover { text-decoration: underline; }
  </style>
</head>
<body>

<div class="reg-layout">

  <!-- ===== LEFT PANEL ===== -->
  <div class="reg-left">
    <div class="reg-left-top">
      <a href="{{ route('landing') }}" class="back-btn-reg">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
        Kembali ke Beranda
      </a>

      <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
        <div style="background:#fff;border-radius:12px;padding:8px 12px;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,0.12);flex-shrink:0;">
          <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height:36px;width:auto;object-fit:contain;display:block;">
        </div>
        <div>
          <div style="font-size:14px;font-weight:800;color:#fff;line-height:1.2;">PT Nusantara</div>
          <div style="font-size:11px;color:rgba(255,255,255,0.6);font-weight:500;">Digital Express</div>
        </div>
      </div>

      <div class="reg-headline">
        <h1>Bergabung &amp;<br/>Mulai Pinjam Sekarang</h1>
        <p>Akses ratusan inventaris perusahaan secara digital — cepat, transparan, dan mudah dipantau.</p>
      </div>

      <div class="reg-benefits">
        <div class="reg-benefit">
          <div class="reg-benefit-icon green">🚀</div>
          <div class="reg-benefit-text">
            <strong>Pengajuan Kilat</strong>
            Ajukan peminjaman dalam hitungan detik
          </div>
        </div>
        <div class="reg-benefit">
          <div class="reg-benefit-icon blue">📊</div>
          <div class="reg-benefit-text">
            <strong>Tracking Real-time</strong>
            Pantau status barang kapan saja
          </div>
        </div>
        <div class="reg-benefit">
          <div class="reg-benefit-icon purple">🔒</div>
          <div class="reg-benefit-text">
            <strong>Data Aman</strong>
            Privasi &amp; keamanan terjamin
          </div>
        </div>
        <div class="reg-benefit">
          <div class="reg-benefit-icon orange">📱</div>
          <div class="reg-benefit-text">
            <strong>Akses Fleksibel</strong>
            Dari perangkat apa saja, di mana saja
          </div>
        </div>
      </div>
    </div>

    <div class="reg-left-footer">
      © 2026 PT Nusantara Digital Express · Semua hak dilindungi
    </div>
  </div>

  <!-- ===== RIGHT PANEL ===== -->
  <div class="reg-right">
    <div class="reg-form-card">

      <div class="reg-form-header">
        <h2>Buat Akun Customer</h2>
        <p>Lengkapi data diri Anda untuk mulai menggunakan layanan</p>
      </div>

      @if($errors->any())
        <div class="info-alert red" style="margin-bottom:20px; border-radius:10px;">
          <strong>⚠️ Periksa kembali formulir Anda:</strong>
          <ul style="margin: 6px 0 0; padding-left:18px;">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('register.post') }}">
        @csrf

        <!-- SECTION: Data Diri -->
        <div class="form-sec">
          <div class="form-sec-icon blue">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          </div>
          <span class="form-sec-label">Data Diri</span>
          <div class="form-sec-line"></div>
        </div>

        <div class="form-group">
          <label class="form-label">Nama Lengkap <span class="required">*</span></label>
          <input type="text" name="name" class="form-input"
                 placeholder="Contoh: Budi Santoso"
                 value="{{ old('name') }}" required autofocus/>
        </div>

        <div class="form-group">
          <label class="form-label">Email <span class="required">*</span></label>
          <input type="email" name="email" class="form-input"
                 placeholder="email@example.com"
                 value="{{ old('email') }}" required/>
        </div>

        <!-- PHONE WITH COUNTRY PICKER -->
        <div class="form-group">
          <label class="form-label">No. Telepon / WhatsApp <span class="required">*</span></label>

          <!-- Hidden field that stores the full phone value -->
          <input type="hidden" name="phone" id="phone-full-value"/>
          <input type="hidden" name="phone_dial_code" id="phone-dial-hidden" value="+62"/>

          <div class="phone-input-group" id="phone-group">
            <!-- Country trigger -->
            <button type="button" class="country-trigger" id="country-trigger" onclick="toggleCountryDropdown()">
              <img id="selected-flag" src="https://flagcdn.com/w40/id.png" alt="Indonesia" class="flag-img"/>
              <span class="dial-code" id="selected-dial">+62</span>
              <svg class="caret" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
            </button>

            <!-- Country dropdown -->
            <div class="country-dropdown" id="country-dropdown">
              <div class="country-search-wrap">
                <input type="text" class="country-search" id="country-search"
                       placeholder="Cari negara..."
                       oninput="filterCountries(this.value)"
                       autocomplete="off"/>
              </div>
              <div class="country-list" id="country-list"></div>
            </div>

            <!-- Actual phone number -->
            <input type="tel" id="phone-number-input" class="form-input phone-number-input"
                   placeholder="8xxxxxxxxxx"
                   value="{{ old('phone') }}"
                   oninput="updateFullPhone()"
                   required/>
          </div>
          <div style="font-size:11.5px; color:#94a3b8; margin-top:5px;">
            💡 Pilih kode negara, lalu masukkan nomor tanpa angka 0 di depan
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">
            Alamat Lengkap
            <span style="color:#94a3b8; font-weight:400;">(Opsional)</span>
          </label>

          {{-- Dropdown Wilayah Bertingkat --}}
          <div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.15);border-radius:12px;padding:14px 16px;margin-bottom:10px;">
            <div style="font-size:12px;font-weight:700;color:#cbd5e1;margin-bottom:10px;">🗺️ Pilih Wilayah <span style="font-weight:400;color:#94a3b8;font-size:11px;">(Provinsi → Kota → Kecamatan)</span></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
              <div>
                <label style="font-size:11px;font-weight:600;color:#94a3b8;margin-bottom:4px;display:block;">Provinsi</label>
                <select id="sel-provinsi" class="form-input form-select" onchange="loadKota()" style="font-size:13px;width:100%;">
                  <option value="">-- Pilih Provinsi --</option>
                </select>
              </div>
              <div>
                <label style="font-size:11px;font-weight:600;color:#94a3b8;margin-bottom:4px;display:block;">Kota / Kabupaten</label>
                <select id="sel-kota" class="form-input form-select" onchange="loadKecamatan()" style="font-size:13px;width:100%;" disabled>
                  <option value="">-- Pilih Kota --</option>
                </select>
              </div>
            </div>
            <div>
              <label style="font-size:11px;font-weight:600;color:#94a3b8;margin-bottom:4px;display:block;">Kecamatan</label>
              <select id="sel-kecamatan" class="form-input form-select" onchange="syncWilayah()" style="font-size:13px;width:100%;" disabled>
                <option value="">-- Pilih Kecamatan --</option>
              </select>
            </div>
          </div>

          <label style="font-size:11px;font-weight:600;color:#94a3b8;margin-bottom:4px;display:block;">Detail Alamat (nama jalan, nomor rumah, RT/RW)</label>
          <textarea name="address" id="address-detail" class="form-input form-textarea" rows="2"
                    placeholder="Cth: Jl. Merdeka No. 12, RT 03/RW 05">{{ old('address') }}</textarea>
        </div>

        <!-- SECTION: Keamanan Akun -->
        <div class="form-sec">
          <div class="form-sec-icon purple">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          </div>
          <span class="form-sec-label">Keamanan Akun</span>
          <div class="form-sec-line"></div>
        </div>

        <div class="form-group">
          <label class="form-label">Password <span class="required">*</span></label>
          <div class="input-wrapper">
            <input type="password" name="password" id="reg-pass" class="form-input"
                   placeholder="Min. 8 karakter, huruf + angka + simbol"
                   oninput="checkPassword()" required/>
            <button type="button" class="input-icon-right" onclick="togglePass('reg-pass')">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>

          <!-- Strength bars -->
          <div class="pw-meter" id="pw-meter" style="display:none;">
            <div class="pw-meter-bars">
              <div class="pw-meter-bar" id="pb1"></div>
              <div class="pw-meter-bar" id="pb2"></div>
              <div class="pw-meter-bar" id="pb3"></div>
              <div class="pw-meter-bar" id="pb4"></div>
            </div>
            <div class="pw-meter-label" id="pw-label"></div>
          </div>

          <!-- Checklist -->
          <div class="pw-checklist" id="pw-checklist">
            <div class="pw-checklist-title">Persyaratan Password</div>
            <div class="pw-req-grid">
              <div id="req-len" class="pw-req"><div class="pw-req-dot"></div>Min. 8 karakter</div>
              <div id="req-let" class="pw-req"><div class="pw-req-dot"></div>Ada huruf (a-z/A-Z)</div>
              <div id="req-num" class="pw-req"><div class="pw-req-dot"></div>Ada angka (0-9)</div>
              <div id="req-sym" class="pw-req"><div class="pw-req-dot"></div>Ada simbol (!@#$%)</div>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Konfirmasi Password <span class="required">*</span></label>
          <div class="input-wrapper">
            <input type="password" name="password_confirmation" id="reg-pass2"
                   class="form-input" placeholder="Ulangi password Anda" required/>
            <button type="button" class="input-icon-right" onclick="togglePass('reg-pass2')">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-full btn-lg"
                style="margin-top:16px; height:48px; font-size:15px; font-weight:700; border-radius:12px;"
                onclick="updateFullPhone()">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          Buat Akun Sekarang
        </button>
      </form>

      <div class="reg-footer-link">
        Sudah punya akun? <a href="{{ route('login') }}">Login sekarang →</a>
      </div>
    </div>
  </div>

</div>

<script>
  // ============================================================
  // COUNTRY DATA — with ISO-2 code for real flag images (flagcdn.com)
  // ============================================================
  function flagUrl(iso2) {
    return 'https://flagcdn.com/w40/' + iso2.toLowerCase() + '.png';
  }

  const COUNTRIES = [
    { name: 'Indonesia',       iso: 'id', dial: '+62'  },
    { name: 'Malaysia',        iso: 'my', dial: '+60'  },
    { name: 'Singapura',       iso: 'sg', dial: '+65'  },
    { name: 'Thailand',        iso: 'th', dial: '+66'  },
    { name: 'Filipina',        iso: 'ph', dial: '+63'  },
    { name: 'Vietnam',         iso: 'vn', dial: '+84'  },
    { name: 'Brunei',          iso: 'bn', dial: '+673' },
    { name: 'Myanmar',         iso: 'mm', dial: '+95'  },
    { name: 'Kamboja',         iso: 'kh', dial: '+855' },
    { name: 'Laos',            iso: 'la', dial: '+856' },
    { name: 'Timor Leste',     iso: 'tl', dial: '+670' },
    { name: 'India',           iso: 'in', dial: '+91'  },
    { name: 'China',           iso: 'cn', dial: '+86'  },
    { name: 'Jepang',          iso: 'jp', dial: '+81'  },
    { name: 'Korea Selatan',   iso: 'kr', dial: '+82'  },
    { name: 'Korea Utara',     iso: 'kp', dial: '+850' },
    { name: 'Australia',       iso: 'au', dial: '+61'  },
    { name: 'Selandia Baru',   iso: 'nz', dial: '+64'  },
    { name: 'Amerika Serikat', iso: 'us', dial: '+1'   },
    { name: 'Kanada',          iso: 'ca', dial: '+1'   },
    { name: 'Inggris',         iso: 'gb', dial: '+44'  },
    { name: 'Jerman',          iso: 'de', dial: '+49'  },
    { name: 'Prancis',         iso: 'fr', dial: '+33'  },
    { name: 'Italia',          iso: 'it', dial: '+39'  },
    { name: 'Spanyol',         iso: 'es', dial: '+34'  },
    { name: 'Belanda',         iso: 'nl', dial: '+31'  },
    { name: 'Belgia',          iso: 'be', dial: '+32'  },
    { name: 'Swiss',           iso: 'ch', dial: '+41'  },
    { name: 'Austria',         iso: 'at', dial: '+43'  },
    { name: 'Swedia',          iso: 'se', dial: '+46'  },
    { name: 'Norwegia',        iso: 'no', dial: '+47'  },
    { name: 'Denmark',         iso: 'dk', dial: '+45'  },
    { name: 'Finlandia',       iso: 'fi', dial: '+358' },
    { name: 'Portugal',        iso: 'pt', dial: '+351' },
    { name: 'Yunani',          iso: 'gr', dial: '+30'  },
    { name: 'Polandia',        iso: 'pl', dial: '+48'  },
    { name: 'Rusia',           iso: 'ru', dial: '+7'   },
    { name: 'Ukraina',         iso: 'ua', dial: '+380' },
    { name: 'Turki',           iso: 'tr', dial: '+90'  },
    { name: 'Arab Saudi',      iso: 'sa', dial: '+966' },
    { name: 'Uni Emirat Arab', iso: 'ae', dial: '+971' },
    { name: 'Qatar',           iso: 'qa', dial: '+974' },
    { name: 'Kuwait',          iso: 'kw', dial: '+965' },
    { name: 'Bahrain',         iso: 'bh', dial: '+973' },
    { name: 'Oman',            iso: 'om', dial: '+968' },
    { name: 'Yordania',        iso: 'jo', dial: '+962' },
    { name: 'Lebanon',         iso: 'lb', dial: '+961' },
    { name: 'Israel',          iso: 'il', dial: '+972' },
    { name: 'Irak',            iso: 'iq', dial: '+964' },
    { name: 'Iran',            iso: 'ir', dial: '+98'  },
    { name: 'Pakistan',        iso: 'pk', dial: '+92'  },
    { name: 'Bangladesh',      iso: 'bd', dial: '+880' },
    { name: 'Sri Lanka',       iso: 'lk', dial: '+94'  },
    { name: 'Nepal',           iso: 'np', dial: '+977' },
    { name: 'Afganistan',      iso: 'af', dial: '+93'  },
    { name: 'Kazakhstan',      iso: 'kz', dial: '+7'   },
    { name: 'Uzbekistan',      iso: 'uz', dial: '+998' },
    { name: 'Mesir',           iso: 'eg', dial: '+20'  },
    { name: 'Nigeria',         iso: 'ng', dial: '+234' },
    { name: 'Afrika Selatan',  iso: 'za', dial: '+27'  },
    { name: 'Kenya',           iso: 'ke', dial: '+254' },
    { name: 'Ghana',           iso: 'gh', dial: '+233' },
    { name: 'Ethiopia',        iso: 'et', dial: '+251' },
    { name: 'Tanzania',        iso: 'tz', dial: '+255' },
    { name: 'Uganda',          iso: 'ug', dial: '+256' },
    { name: 'Maroko',          iso: 'ma', dial: '+212' },
    { name: 'Tunisia',         iso: 'tn', dial: '+216' },
    { name: 'Aljazair',        iso: 'dz', dial: '+213' },
    { name: 'Libya',           iso: 'ly', dial: '+218' },
    { name: 'Sudan',           iso: 'sd', dial: '+249' },
    { name: 'Meksiko',         iso: 'mx', dial: '+52'  },
    { name: 'Brasil',          iso: 'br', dial: '+55'  },
    { name: 'Argentina',       iso: 'ar', dial: '+54'  },
    { name: 'Kolombia',        iso: 'co', dial: '+57'  },
    { name: 'Chile',           iso: 'cl', dial: '+56'  },
    { name: 'Peru',            iso: 'pe', dial: '+51'  },
    { name: 'Venezuela',       iso: 've', dial: '+58'  },
    { name: 'Kuba',            iso: 'cu', dial: '+53'  },
    { name: 'Ekuador',         iso: 'ec', dial: '+593' },
    { name: 'Bolivia',         iso: 'bo', dial: '+591' },
    { name: 'Paraguay',        iso: 'py', dial: '+595' },
    { name: 'Uruguay',         iso: 'uy', dial: '+598' },
    { name: 'Hungaria',        iso: 'hu', dial: '+36'  },
    { name: 'Ceko',            iso: 'cz', dial: '+420' },
    { name: 'Slovakia',        iso: 'sk', dial: '+421' },
    { name: 'Rumania',         iso: 'ro', dial: '+40'  },
    { name: 'Bulgaria',        iso: 'bg', dial: '+359' },
    { name: 'Kroasia',         iso: 'hr', dial: '+385' },
    { name: 'Serbia',          iso: 'rs', dial: '+381' },
    { name: 'Irlandia',        iso: 'ie', dial: '+353' },
    { name: 'Islandia',        iso: 'is', dial: '+354' },
    { name: 'Luksemburg',      iso: 'lu', dial: '+352' },
    { name: 'Malta',           iso: 'mt', dial: '+356' },
    { name: 'Siprus',          iso: 'cy', dial: '+357' },
    { name: 'Estonia',         iso: 'ee', dial: '+372' },
    { name: 'Latvia',          iso: 'lv', dial: '+371' },
    { name: 'Lituania',        iso: 'lt', dial: '+370' },
    { name: 'Slovenia',        iso: 'si', dial: '+386' },
    { name: 'Makedonia',       iso: 'mk', dial: '+389' },
    { name: 'Albania',         iso: 'al', dial: '+355' },
    { name: 'Moldova',         iso: 'md', dial: '+373' },
    { name: 'Georgia',         iso: 'ge', dial: '+995' },
    { name: 'Armenia',         iso: 'am', dial: '+374' },
    { name: 'Azerbaijan',      iso: 'az', dial: '+994' },
    { name: 'Belarus',         iso: 'by', dial: '+375' },
    { name: 'Hong Kong',       iso: 'hk', dial: '+852' },
    { name: 'Taiwan',          iso: 'tw', dial: '+886' },
    { name: 'Mongolia',        iso: 'mn', dial: '+976' },
    { name: 'Papua Nugini',    iso: 'pg', dial: '+675' },
    { name: 'Fiji',            iso: 'fj', dial: '+679' },
  ];

  // Sort: Indonesia first, then alphabetical
  COUNTRIES.sort((a, b) => {
    if (a.name === 'Indonesia') return -1;
    if (b.name === 'Indonesia') return 1;
    return a.name.localeCompare(b.name, 'id');
  });

  let selectedCountry = COUNTRIES[0]; // Indonesia by default
  let dropdownOpen = false;

  // ── Render list ──
  function renderCountryList(list) {
    const container = document.getElementById('country-list');
    if (!list.length) {
      container.innerHTML = '<div class="country-no-result">😕 Negara tidak ditemukan</div>';
      return;
    }
    container.innerHTML = list.map((c) => `
      <div class="country-item ${c.name === selectedCountry.name ? 'selected' : ''}"
           onclick="selectCountry(${COUNTRIES.indexOf(c)})">
        <span class="ci-flag"><img src="${flagUrl(c.iso)}" alt="${c.name}" loading="lazy"/></span>
        <span class="ci-name">${c.name}</span>
        <span class="ci-dial">${c.dial}</span>
      </div>
    `).join('');
  }

  function filterCountries(query) {
    const q = query.trim().toLowerCase();
    const filtered = q
      ? COUNTRIES.filter(c => c.name.toLowerCase().includes(q) || c.dial.includes(q))
      : COUNTRIES;
    renderCountryList(filtered);
  }

  function selectCountry(index) {
    selectedCountry = COUNTRIES[index];
    document.getElementById('selected-flag').src = flagUrl(selectedCountry.iso);
    document.getElementById('selected-flag').alt = selectedCountry.name;
    document.getElementById('selected-dial').textContent = selectedCountry.dial;
    document.getElementById('phone-dial-hidden').value = selectedCountry.dial;
    closeCountryDropdown();
    updateFullPhone();
    document.getElementById('phone-number-input').focus();
  }

  function updateFullPhone() {
    const number = document.getElementById('phone-number-input').value.trim();
    // Remove leading 0 if user typed it
    const cleaned = number.replace(/^0+/, '');
    document.getElementById('phone-full-value').value = selectedCountry.dial + cleaned;
  }

  function toggleCountryDropdown() {
    dropdownOpen ? closeCountryDropdown() : openCountryDropdown();
  }

  function openCountryDropdown() {
    dropdownOpen = true;
    document.getElementById('country-dropdown').classList.add('open');
    document.getElementById('country-trigger').classList.add('open');
    document.getElementById('country-search').value = '';
    renderCountryList(COUNTRIES);
    setTimeout(() => document.getElementById('country-search').focus(), 50);
  }

  function closeCountryDropdown() {
    dropdownOpen = false;
    document.getElementById('country-dropdown').classList.remove('open');
    document.getElementById('country-trigger').classList.remove('open');
  }

  // Close dropdown on outside click
  document.addEventListener('click', function(e) {
    const group = document.getElementById('phone-group');
    if (dropdownOpen && !group.contains(e.target)) {
      closeCountryDropdown();
    }
  });

  // Init
  document.addEventListener('DOMContentLoaded', function() {
    renderCountryList(COUNTRIES);
    updateFullPhone();
  });

  // ── Password ──
  function togglePass(id) {
    const el = document.getElementById(id);
    if (el) el.type = el.type === 'password' ? 'text' : 'password';
  }

  function checkPassword() {
    const val       = document.getElementById('reg-pass').value;
    const meter     = document.getElementById('pw-meter');
    const checklist = document.getElementById('pw-checklist');
    const label     = document.getElementById('pw-label');

    if (!val.length) {
      meter.style.display = checklist.style.display = 'none';
      return;
    }
    meter.style.display = checklist.style.display = 'block';

    const hasLen = val.length >= 8;
    const hasLet = /[a-zA-Z]/.test(val);
    const hasNum = /[0-9]/.test(val);
    const hasSym = /[^a-zA-Z0-9]/.test(val);

    setReq('req-len', hasLen, 'Min. 8 karakter');
    setReq('req-let', hasLet, 'Ada huruf (a-z/A-Z)');
    setReq('req-num', hasNum, 'Ada angka (0-9)');
    setReq('req-sym', hasSym, 'Ada simbol (!@#$%)');

    const score  = [hasLen, hasLet, hasNum, hasSym].filter(Boolean).length;
    const colors = ['#ef4444','#f97316','#eab308','#22c55e'];
    const labels = ['Sangat Lemah','Lemah','Cukup Kuat','Kuat ✓'];

    ['pb1','pb2','pb3','pb4'].forEach((id, i) => {
      document.getElementById(id).style.background = i < score ? colors[score - 1] : '#e2e8f0';
    });
    label.textContent = labels[score - 1] || '';
    label.style.color = colors[score - 1] || '#94a3b8';
  }

  function setReq(id, ok, text) {
    const el = document.getElementById(id);
    if (!el) return;
    el.innerHTML = `<div class="pw-req-dot"></div> ${text}`;
    el.className = 'pw-req' + (ok ? ' ok' : '');
  }

  // ===== WILAYAH INDONESIA =====
  const WILAYAH_BASE = 'https://www.emsifa.com/api-wilayah-indonesia/api';
  let _wCache = {};
  async function wFetch(url) {
    if (_wCache[url]) return _wCache[url];
    const r = await fetch(url); const d = await r.json();
    _wCache[url] = d; return d;
  }
  function selLoad(sel, txt) { sel.innerHTML = `<option>${txt}</option>`; sel.disabled = true; }

  async function loadProvinsi() {
    const sel = document.getElementById('sel-provinsi');
    selLoad(sel, 'Memuat...');
    try {
      const data = await wFetch(`${WILAYAH_BASE}/provinces.json`);
      sel.innerHTML = '<option value="">-- Pilih Provinsi --</option>';
      data.forEach(p => sel.innerHTML += `<option value="${p.id}">${p.name}</option>`);
      sel.disabled = false;
    } catch { sel.innerHTML = '<option value="">Gagal</option>'; sel.disabled = false; }
  }

  async function loadKota() {
    const provId = document.getElementById('sel-provinsi').value;
    const selK = document.getElementById('sel-kota');
    const selKec = document.getElementById('sel-kecamatan');
    selKec.innerHTML = '<option value="">-- Pilih Kecamatan --</option>'; selKec.disabled = true;
    if (!provId) { selK.innerHTML = '<option value="">-- Pilih Kota --</option>'; selK.disabled = true; return; }
    selLoad(selK, 'Memuat...');
    try {
      const data = await wFetch(`${WILAYAH_BASE}/regencies/${provId}.json`);
      selK.innerHTML = '<option value="">-- Pilih Kota/Kabupaten --</option>';
      data.forEach(k => selK.innerHTML += `<option value="${k.id}">${k.name}</option>`);
      selK.disabled = false;
    } catch { selK.innerHTML = '<option value="">Gagal</option>'; selK.disabled = false; }
  }

  async function loadKecamatan() {
    const kotaId = document.getElementById('sel-kota').value;
    const selKec = document.getElementById('sel-kecamatan');
    if (!kotaId) { selKec.innerHTML = '<option value="">-- Pilih Kecamatan --</option>'; selKec.disabled = true; return; }
    selLoad(selKec, 'Memuat...');
    try {
      const data = await wFetch(`${WILAYAH_BASE}/districts/${kotaId}.json`);
      selKec.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
      data.forEach(k => selKec.innerHTML += `<option value="${k.id}">${k.name}</option>`);
      selKec.disabled = false;
    } catch { selKec.innerHTML = '<option value="">Gagal</option>'; selKec.disabled = false; }
  }

  function syncWilayah() {
    const provSel = document.getElementById('sel-provinsi');
    const kotaSel = document.getElementById('sel-kota');
    const kecSel  = document.getElementById('sel-kecamatan');
    const detail  = document.getElementById('address-detail');
    const kecName  = kecSel.options[kecSel.selectedIndex]?.text || '';
    const kotaName = kotaSel.options[kotaSel.selectedIndex]?.text || '';
    const provName = provSel.options[provSel.selectedIndex]?.text || '';
    if (!kecName || kecName.startsWith('--')) return;
    const wilayah = `${kecName}, ${kotaName}, ${provName}`;
    const prev = detail.dataset.lastWilayah || '';
    const cur  = detail.value.trim();
    if (!cur || cur === prev) { detail.value = wilayah; }
    else {
      let stripped = cur.replace(prev, '').replace(/,\s*$/, '').trim();
      detail.value = stripped ? `${stripped}, ${wilayah}` : wilayah;
    }
    detail.dataset.lastWilayah = wilayah;
  }

  document.addEventListener('DOMContentLoaded', loadProvinsi);
</script>
</body>
</html>
