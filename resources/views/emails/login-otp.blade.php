<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Kode Verifikasi Login – PT Nusantara Digital Express</title>
</head>
<body style="margin:0;padding:0;background-color:#F1F5F9;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;-webkit-font-smoothing:antialiased;">

  <!-- Background Wrapper -->
  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#F1F5F9;padding:36px 12px;">
    <tr>
      <td align="center">

        <!-- Main Card -->
        <table width="560" cellpadding="0" cellspacing="0" border="0" style="max-width:560px;width:100%;background:#ffffff;border-radius:20px;overflow:hidden;box-shadow:0 10px 30px rgba(15,23,42,0.08);border:1px solid #E2E8F0;">
          
          <!-- Top Accent Bar -->
          <tr>
            <td style="height:6px;background:linear-gradient(90deg,#0B1F6B 0%,#1D4ED8 50%,#3B82F6 100%);"></td>
          </tr>

          <!-- Header with Logo -->
          <tr>
            <td align="center" style="padding:36px 32px 24px;background:#ffffff;border-bottom:1px solid #F1F5F9;">
              <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td align="center">
                    <img src="cid:company_logo"
                         alt="PT Nusantara Digital Express"
                         width="220"
                         style="max-width:220px;width:100%;height:auto;display:block;margin:0 auto;"
                    />
                  </td>
                </tr>
              </table>
              <div style="margin-top:14px;display:inline-block;padding:4px 14px;background:#EFF6FF;border:1px solid #DBEAFE;border-radius:999px;">
                <span style="font-size:11px;font-weight:700;color:#1D4ED8;letter-spacing:1px;text-transform:uppercase;">
                  Sistem Peminjaman Barang Inventaris
                </span>
              </div>
            </td>
          </tr>

          <!-- Body Content -->
          <tr>
            <td style="padding:32px 36px 28px;">

              <!-- Greeting -->
              <h2 style="margin:0 0 8px;font-size:20px;font-weight:800;color:#0F172A;letter-spacing:-0.3px;">
                Halo, {{ $user->name }}!
              </h2>
              <p style="margin:0 0 24px;font-size:14.5px;color:#475569;line-height:1.6;">
                Kami menerima permintaan login ke akun Anda. Gunakan kode verifikasi (OTP) 6-digit di bawah ini untuk menyelesaikan proses masuk:
              </p>

              <!-- OTP Box Container -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td style="background:#F8FAFC;border:2px solid #E2E8F0;border-radius:16px;padding:26px 20px;text-align:center;">
                    
                    <p style="margin:0 0 14px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:2px;color:#64748B;">
                      Kode Verifikasi OTP Anda
                    </p>

                    <!-- 6 Digit Individual Boxes -->
                    <table cellpadding="0" cellspacing="0" border="0" style="margin:0 auto 16px;">
                      <tr>
                        @for($i = 0; $i < 6; $i++)
                        <td style="padding:0 5px;">
                          <div style="width:44px;height:54px;line-height:54px;background:#ffffff;border:2px solid #2563EB;border-radius:10px;text-align:center;font-family:'Courier New',Courier,monospace;font-size:28px;font-weight:900;color:#0B1F6B;box-shadow:0 3px 8px rgba(37,99,235,0.12);display:inline-block;vertical-align:middle;">
                            {{ substr($otp, $i, 1) }}
                          </div>
                        </td>
                        @endfor
                      </tr>
                    </table>

                    <!-- Expiration Notice -->
                    <div style="display:inline-block;padding:5px 14px;background:#FFFBEB;border:1px solid #FDE68A;border-radius:999px;">
                      <span style="font-size:12.5px;color:#B45309;font-weight:700;">
                        ⏱ Berlaku selama <strong>5 menit</strong>
                      </span>
                    </div>

                  </td>
                </tr>
              </table>

              <!-- Security Notice -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:22px;">
                <tr>
                  <td style="background:#FEF2F2;border-left:4px solid #EF4444;border-radius:0 10px 10px 0;padding:12px 16px;">
                    <p style="margin:0;font-size:12.5px;color:#991B1B;line-height:1.55;">
                      <strong>⚠️ Peringatan Keamanan:</strong><br/>
                      Jangan pernah membagikan kode OTP ini kepada siapa pun, termasuk staf PT Nusantara Digital Express. Jika bukan Anda yang mencoba login, segera amankan akun Anda.
                    </p>
                  </td>
                </tr>
              </table>

              <!-- Step Guide -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:24px;">
                <tr>
                  <td style="background:#F8FAFC;border-radius:12px;padding:16px 20px;">
                    <p style="margin:0 0 10px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#64748B;">
                      Petunjuk Masuk
                    </p>
                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                      <tr>
                        <td style="padding:4px 0;font-size:13px;color:#334155;line-height:1.5;">
                          <strong style="color:#2563EB;">1.</strong> Salin atau masukkan 6 digit angka di atas pada halaman verifikasi login.
                        </td>
                      </tr>
                      <tr>
                        <td style="padding:4px 0;font-size:13px;color:#334155;line-height:1.5;">
                          <strong style="color:#2563EB;">2.</strong> Klik tombol <strong>"Verifikasi &amp; Masuk"</strong> untuk masuk ke dashboard.
                        </td>
                      </tr>
                      <tr>
                        <td style="padding:4px 0;font-size:13px;color:#334155;line-height:1.5;">
                          <strong style="color:#2563EB;">3.</strong> Jika kode kadaluarsa, Anda dapat meminta kode baru melalui tautan kirim ulang.
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>

            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background:#F8FAFC;padding:24px 36px;border-top:1px solid #E2E8F0;text-align:center;">
              <p style="margin:0 0 6px;font-size:12px;font-weight:700;color:#0F172A;">
                PT Nusantara Digital Express
              </p>
              <p style="margin:0 0 10px;font-size:11.5px;color:#64748B;">
                Cepat &bull; Aman &bull; Terpercaya &bull; Sistem Peminjaman Barang Inventaris
              </p>
              <p style="margin:0;font-size:11px;color:#94A3B8;">
                Email ini dikirim secara otomatis oleh sistem keamanan kami. Mohon jangan membalas email ini.
              </p>
            </td>
          </tr>

        </table>

      </td>
    </tr>
  </table>

</body>
</html>
