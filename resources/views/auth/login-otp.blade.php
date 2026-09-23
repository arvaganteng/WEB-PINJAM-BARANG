<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Verifikasi OTP – PT Nusantara Digital Express</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
  <style>
    .otp-inputs {
      display: flex;
      gap: 10px;
      justify-content: center;
      margin: 20px 0;
    }
    .otp-digit {
      width: 48px;
      height: 56px;
      text-align: center;
      font-size: 24px;
      font-weight: 700;
      color: var(--navy);
      border: 2px solid var(--gray-200);
      border-radius: 10px;
      background: #fff;
      transition: all 0.2s;
    }
    .otp-digit:focus {
      border-color: var(--blue);
      box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
      outline: none;
    }
    .otp-timer {
      font-size: 13px;
      color: var(--gray-500);
      text-align: center;
      margin-top: 16px;
    }
    .otp-timer span {
      font-weight: 700;
      color: var(--navy);
    }
    .resend-btn {
      background: none;
      border: none;
      color: var(--blue);
      font-weight: 600;
      font-size: 13px;
      cursor: pointer;
      padding: 0;
      text-decoration: underline;
    }
    .resend-btn:disabled {
      color: #94a3b8;
      cursor: not-allowed;
    }
  </style>
</head>
<body class="auth-body">

  <div class="auth-layout">
    <!-- LEFT PANEL -->
    <div class="auth-left">
      <div class="auth-left-content">
        <a href="{{ route('login') }}" class="back-btn">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
          Kembali ke Login
        </a>
        <div class="auth-brand" style="margin: 10px 0 28px; display: flex; justify-content: center; width: 100%;">
          <img src="{{ asset('images/logo.png') }}" alt="PT Nusantara Digital Express" style="height: 120px; width: auto; max-width: 330px; object-fit: contain; mix-blend-mode: multiply; filter: contrast(1.05); display: block;">
        </div>
        <div class="auth-welcome">
          <h1>Verifikasi 2 Langkah</h1>
          <p>Kami menjaga keamanan akun Anda dengan verifikasi kode OTP yang dikirimkan secara langsung ke email terdaftar.</p>
        </div>
        <div class="auth-illustration">
          <div class="ill-card">
            <div style="display:flex;align-items:center;gap:12px;">
              <div style="width:40px;height:40px;border-radius:10px;background:#eff6ff;color:#2563eb;display:flex;align-items:center;justify-content:center;font-size:20px;">🔒</div>
              <div>
                <div style="font-size:13px;font-weight:700;color:var(--gray-900);">Autentikasi Aman</div>
                <div style="font-size:12px;color:var(--gray-500);">Perlindungan dari akses tidak sah</div>
              </div>
            </div>
            <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--gray-100)">
              <div style="font-size:11.5px;color:var(--gray-500);line-height:1.5;">
                Kode 6 digit akan kadaluarsa otomatis dalam 5 menit. Jangan bagikan kode ini kepada pihak mana pun.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="auth-right">
      <div class="auth-form-box">
        <div class="auth-form-header" style="text-align:center">
          <div style="width:54px;height:54px;margin:0 auto 16px;background:#eff6ff;border-radius:16px;display:flex;align-items:center;justify-content:center;color:#2563eb;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          </div>
          <h2>Masukkan Kode Verifikasi</h2>
          <p style="margin-top:6px;font-size:13px;color:var(--gray-600);line-height:1.5;">
            Kode OTP 6-digit telah dikirim ke email:<br/>
            <strong style="color:var(--navy);font-size:14px;">{{ $maskedEmail }}</strong><br/>
            <span style="font-size:11.5px;color:var(--gray-500);">💡 Cek Kotak Masuk atau folder <strong>Spam/Junk</strong> Gmail Anda</span>
          </p>
        </div>

        @if(session('success'))
          <div class="info-alert green" style="margin-bottom: 16px; font-size: 13px;">
            ✓ {{ session('success') }}
          </div>
        @endif

        @if(session('error'))
          <div class="info-alert red" style="margin-bottom: 16px; font-size: 13px;">
            ✕ {{ session('error') }}
          </div>
        @endif

        @if($errors->any())
          <div class="info-alert red" style="margin-bottom: 16px; font-size: 13px;">
            ⚠️ {{ $errors->first() }}
          </div>
        @endif

        <form class="auth-form" method="POST" action="{{ route('login.otp.verify') }}" id="otpForm">
          @csrf
          <input type="hidden" name="otp" id="fullOtp"/>

          <div class="otp-inputs">
            <input type="text" class="otp-digit" maxlength="1" pattern="[0-9]" inputmode="numeric" autofocus required/>
            <input type="text" class="otp-digit" maxlength="1" pattern="[0-9]" inputmode="numeric" required/>
            <input type="text" class="otp-digit" maxlength="1" pattern="[0-9]" inputmode="numeric" required/>
            <input type="text" class="otp-digit" maxlength="1" pattern="[0-9]" inputmode="numeric" required/>
            <input type="text" class="otp-digit" maxlength="1" pattern="[0-9]" inputmode="numeric" required/>
            <input type="text" class="otp-digit" maxlength="1" pattern="[0-9]" inputmode="numeric" required/>
          </div>

          <button type="submit" class="btn btn-primary btn-full btn-lg" id="verifyBtn" style="margin-top:8px">
            Verifikasi & Masuk
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
          </button>
        </form>

        <div class="otp-timer" id="timerBox">
          Kirim ulang kode dalam <span id="countdown">05:00</span>
        </div>

        <div style="text-align:center;margin-top:12px;display:none" id="resendBox">
          <form method="POST" action="{{ route('login.otp.resend') }}" style="display:inline">
            @csrf
            <button type="submit" class="resend-btn" id="resendBtn">
              🔄 Kirim Ulang Kode OTP
            </button>
          </form>
        </div>

        <div class="auth-footer-link" style="margin-top:24px">
          Salah email? <a href="{{ route('login') }}">Login dengan akun lain</a>
        </div>
      </div>
    </div>
  </div>

  <script>
    const inputs = document.querySelectorAll('.otp-digit');
    const fullOtpInput = document.getElementById('fullOtp');
    const form = document.getElementById('otpForm');

    inputs.forEach((input, index) => {
      input.addEventListener('input', (e) => {
        // Only allow numbers
        e.target.value = e.target.value.replace(/[^0-9]/g, '');

        if (e.target.value.length === 1 && index < inputs.length - 1) {
          inputs[index + 1].focus();
        }
        updateFullOtp();
      });

      input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !e.target.value && index > 0) {
          inputs[index - 1].focus();
        }
      });

      // Handle paste
      input.addEventListener('paste', (e) => {
        e.preventDefault();
        const pasteData = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, 6);
        pasteData.split('').forEach((char, i) => {
          if (inputs[i]) inputs[i].value = char;
        });
        if (pasteData.length > 0) {
          const focusIndex = Math.min(pasteData.length, inputs.length - 1);
          inputs[focusIndex].focus();
        }
        updateFullOtp();
      });
    });

    function updateFullOtp() {
      let otp = '';
      inputs.forEach(input => { otp += input.value; });
      fullOtpInput.value = otp;
      
      // Auto submit if all 6 digits entered
      if (otp.length === 6) {
        form.submit();
      }
    }

    // Countdown Timer (5 Minutes)
    let timeLeft = 300; // 5 mins in seconds
    const countdownEl = document.getElementById('countdown');
    const timerBox = document.getElementById('timerBox');
    const resendBox = document.getElementById('resendBox');

    const timer = setInterval(() => {
      timeLeft--;
      const minutes = String(Math.floor(timeLeft / 60)).padStart(2, '0');
      const seconds = String(timeLeft % 60).padStart(2, '0');
      countdownEl.textContent = `${minutes}:${seconds}`;

      if (timeLeft <= 0) {
        clearInterval(timer);
        timerBox.style.display = 'none';
        resendBox.style.display = 'block';
      }
    }, 1000);
  </script>
</body>
</html>
