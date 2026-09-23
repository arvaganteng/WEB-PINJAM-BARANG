<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\LoginOtpMail;
use App\Services\ActivityLogService;
use App\Services\MailService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // Customer Login View
    public function showCustomerLogin()
    {
        if (Auth::check()) {
            return Auth::user()->role === 'admin' 
                ? redirect()->route('admin.dashboard') 
                : redirect()->route('customer.dashboard');
        }
        return view('auth.login-customer');
    }

    // Customer Login Action with Rate Limiter (3 attempts, 5 min lockout) & 2FA OTP
    public function loginCustomer(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Validasi Google reCAPTCHA
        $recaptchaSecret = config('services.recaptcha.secret_key');
        if ($recaptchaSecret) {
            $recaptchaResponse = $request->input('g-recaptcha-response');
            if (!$recaptchaResponse) {
                return back()->withErrors(['email' => 'Silakan centang verifikasi "Saya bukan robot" (Captcha) terlebih dahulu.'])->withInput();
            }

            try {
                $verify = Http::withoutVerifying()->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret'   => $recaptchaSecret,
                    'response' => $recaptchaResponse,
                    'remoteip' => $request->ip(),
                ]);

                if (!$verify->json('success')) {
                    return back()->withErrors(['email' => 'Verifikasi Captcha tidak valid. Silakan coba lagi.'])->withInput();
                }
            } catch (\Exception $e) {
                Log::warning('Captcha verification error: ' . $e->getMessage());
            }
        }

        $email = Str::lower($request->input('email'));
        $throttleKey = Str::transliterate($email . '|' . $request->ip());

        // 1. Cek apakah akun ditahan karena 3x gagal login
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login gagal (3x). Akses login ditahan selama 5 menit. Silakan coba lagi dalam {$minutes} menit ({$seconds} detik).",
            ])->onlyInput('email');
        }

        // 2. Cari user customer berdasarkan email
        $user = User::where('email', $email)->where('role', 'customer')->first();

        // 3. Cek apakah user ditemukan dan password cocok
        if (!$user || !Hash::check($request->password, $user->password)) {
            RateLimiter::hit($throttleKey, 300); // Tahan selama 300 detik (5 menit)
            $remaining = RateLimiter::remaining($throttleKey, 3);
            
            if ($remaining <= 0) {
                $seconds = RateLimiter::availableIn($throttleKey);
                $minutes = ceil($seconds / 60);
                return back()->withErrors([
                    'email' => "Anda telah 3x gagal login. Akses login ditahan selama 5 menit. Silakan coba kembali dalam {$minutes} menit.",
                ])->onlyInput('email');
            }

            return back()->withErrors([
                'email' => "Email atau password salah. Sisa kesempatan mencoba: {$remaining} kali lagi sebelum ditahan 5 menit.",
            ])->onlyInput('email');
        }

        // 4. Cek status akun aktif / nonaktif
        if ($user->status !== 'aktif') {
            return back()->withErrors(['email' => 'Akun Anda dinonaktifkan oleh administrator.']);
        }

        // 5. Kredensial benar -> Bersihkan catatan gagal di Rate Limiter
        RateLimiter::clear($throttleKey);

        // 6. Generate kode OTP 6-digit & simpan batas waktu 5 menit
        $otp = (string) random_int(100000, 999999);
        $expiresAt = now()->addMinutes(5);

        $user->update([
            'login_otp' => $otp,
            'login_otp_expires_at' => $expiresAt,
        ]);

        Log::info("OTP generated for {$user->email}: {$otp} (expires: {$expiresAt})");

        // Simpan id user di session sementara untuk verifikasi OTP
        $request->session()->put('login_otp_user_id', $user->id);

        // 7. Kirimkan email kode OTP via MailService (Gmail SMTP)
        $emailSubject = 'Kode Verifikasi Login: ' . $otp . ' – PT Nusantara Digital Express';
        $emailHtml = view('emails.login-otp', ['user' => $user, 'otp' => $otp])->render();

        try {
            MailService::sendHtml($user->email, $emailSubject, $emailHtml, $user->name);
        } catch (\Throwable $e) {
            Log::warning('MailService gagal: ' . $e->getMessage());
        }

        return redirect()->route('login.otp.view');
    }

    // Tampilkan Form Input OTP
    public function showOtpForm(Request $request)
    {
        $userId = $request->session()->get('login_otp_user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Sesi verifikasi telah berakhir. Silakan login kembali.');
        }

        $user = User::find($userId);
        if (!$user) {
            $request->session()->forget('login_otp_user_id');
            return redirect()->route('login')->with('error', 'Pengguna tidak ditemukan.');
        }

        // Mask email: misal budi@example.com -> bu***@example.com
        $parts = explode('@', $user->email);
        $name = $parts[0];
        $domain = $parts[1] ?? '';
        $maskedName = substr($name, 0, 2) . str_repeat('*', max(3, strlen($name) - 2));
        $maskedEmail = $maskedName . '@' . $domain;

        $secondsRemaining = 300;
        if ($user->login_otp_expires_at) {
            $diff = now()->diffInSeconds($user->login_otp_expires_at, false);
            $secondsRemaining = (int) max(0, ceil($diff));
        }

        return view('auth.login-otp', compact('user', 'maskedEmail', 'secondsRemaining'));
    }

    // Verifikasi Kode OTP yang dimasukkan
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string',
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
        ]);

        $userId = $request->session()->get('login_otp_user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Sesi verifikasi telah berakhir. Silakan login kembali.');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login')->with('error', 'Pengguna tidak ditemukan.');
        }

        // Bersihkan OTP dari spasi atau karakter non-angka
        $enteredOtp = trim(preg_replace('/[^0-9]/', '', (string) $request->input('otp')));

        if (strlen($enteredOtp) !== 6) {
            return back()->withErrors(['otp' => 'Kode OTP harus 6 digit angka.']);
        }

        // Cek kecocokan OTP
        if (!$user->login_otp || (string) $user->login_otp !== (string) $enteredOtp) {
            Log::warning("OTP mismatch for {$user->email}: input='{$enteredOtp}' vs db='{$user->login_otp}'");
            return back()->withErrors(['otp' => 'Kode OTP yang Anda masukkan salah. Periksa kembali email Anda (pastikan gunakan kode dari email paling baru).']);
        }

        // Cek apakah sudah kadaluarsa (> 5 menit)
        if (!$user->login_otp_expires_at || Carbon::parse($user->login_otp_expires_at)->isPast()) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa (lebih dari 5 menit). Silakan klik "Kirim Ulang Kode".']);
        }

        // OTP valid -> Bersihkan kolom OTP
        $user->update([
            'login_otp' => null,
            'login_otp_expires_at' => null,
        ]);

        $request->session()->forget('login_otp_user_id');
        $request->session()->regenerate();

        // Login ke sistem
        Auth::login($user);

        Log::info("User {$user->email} logged in successfully via OTP.");

        ActivityLogService::log(
            'auth.login',
            "Customer {$user->name} berhasil login ke sistem.",
            $user,
            ['email' => $user->email]
        );

        return redirect()->intended(route('customer.dashboard'))->with('success', 'Verifikasi berhasil! Selamat datang kembali, ' . $user->name . '!');
    }

    // Kirim Ulang OTP
    public function resendOtp(Request $request)
    {
        $userId = $request->session()->get('login_otp_user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Sesi verifikasi telah berakhir. Silakan login kembali.');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login')->with('error', 'Pengguna tidak ditemukan.');
        }

        // Generate kode baru
        $otp = (string) random_int(100000, 999999);
        $expiresAt = now()->addMinutes(5);

        $user->update([
            'login_otp' => $otp,
            'login_otp_expires_at' => $expiresAt,
        ]);

        Log::info("New OTP re-sent for {$user->email}: {$otp}");

        $emailSubject = 'Kode Verifikasi Login Baru: ' . $otp . ' – PT Nusantara Digital Express';
        $emailHtml = view('emails.login-otp', ['user' => $user, 'otp' => $otp])->render();

        try {
            MailService::sendHtml($user->email, $emailSubject, $emailHtml, $user->name);
        } catch (\Throwable $e) {
            Log::warning('MailService gagal di resendOtp: ' . $e->getMessage());
        }

        return back()->with('success', 'Kode OTP baru dikirim ke email Gmail Anda.');
    }

    // Admin Login View
    public function showAdminLogin()
    {
        if (Auth::check()) {
            return Auth::user()->role === 'admin' 
                ? redirect()->route('admin.dashboard') 
                : redirect()->route('customer.dashboard');
        }
        return view('auth.login-admin');
    }

    // Admin Login Action (dengan Rate Limiter 3x gagal / 5 menit juga)
    public function loginAdmin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Validasi Google reCAPTCHA Admin
        $recaptchaSecret = config('services.recaptcha.secret_key');
        if ($recaptchaSecret) {
            $recaptchaResponse = $request->input('g-recaptcha-response');
            if (!$recaptchaResponse) {
                return back()->withErrors(['email' => 'Silakan centang verifikasi "Saya bukan robot" (Captcha) terlebih dahulu.'])->withInput();
            }

            try {
                $verify = Http::withoutVerifying()->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret'   => $recaptchaSecret,
                    'response' => $recaptchaResponse,
                    'remoteip' => $request->ip(),
                ]);

                if (!$verify->json('success')) {
                    return back()->withErrors(['email' => 'Verifikasi Captcha admin tidak valid.'])->withInput();
                }
            } catch (\Exception $e) {
                Log::warning('Captcha verification admin error: ' . $e->getMessage());
            }
        }

        $email = Str::lower($credentials['email']);
        $throttleKey = Str::transliterate('admin|' . $email . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login gagal (3x). Akses login admin ditahan selama 5 menit. Silakan coba dalam {$minutes} menit.",
            ])->onlyInput('email');
        }

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'role' => 'admin'])) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            $adminUser = Auth::user();
            ActivityLogService::log(
                'auth.login',
                "Admin {$adminUser->name} berhasil login ke panel admin.",
                $adminUser,
                ['email' => $adminUser->email]
            );

            return redirect()->intended(route('admin.dashboard'))->with('success', 'Selamat datang di Dashboard Administrator!');
        }

        RateLimiter::hit($throttleKey, 300);
        $remaining = RateLimiter::remaining($throttleKey, 3);

        return back()->withErrors([
            'email' => "Kredensial admin tidak valid. Sisa kesempatan: {$remaining}x.",
        ])->onlyInput('email');
    }

    // Register View
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('customer.dashboard');
        }
        return view('auth.register');
    }

    // Register Action
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'phone'    => 'required|string|max:30',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->symbols(),
            ],
            'address'  => 'nullable|string',
        ], [
            'phone.required'     => 'Nomor telepon wajib diisi.',
            'password.min'       => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password'           => 'Password harus mengandung huruf, angka, dan simbol (misal: ! @ # $ %).',
        ]);

        // phone field contains the full number e.g. "+628123456789"
        $phone = $validated['phone'];

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $phone,
            'password' => Hash::make($validated['password']),
            'address'  => $validated['address'] ?? null,
            'role'     => 'customer',
            'status'   => 'aktif',
        ]);

        Auth::login($user);

        ActivityLogService::log(
            'auth.register',
            "Customer baru {$user->name} mendaftarkan akun.",
            $user,
            ['email' => $user->email]
        );

        return redirect()->route('customer.dashboard')->with('success', 'Pendaftaran berhasil! Selamat datang di Sistem Peminjaman Barang.');
    }

    // Logout Action
    public function logout(Request $request)
    {
        $user = Auth::user();
        $role = $user?->role;

        ActivityLogService::log(
            'auth.logout',
            ($user?->name ?? 'Pengguna') . ' logout dari sistem.',
            $user
        );

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($role === 'admin') {
            return redirect()->route('admin.login')->with('success', 'Anda telah berhasil logout.');
        }

        return redirect()->route('landing')->with('success', 'Anda telah berhasil logout.');
    }
}
