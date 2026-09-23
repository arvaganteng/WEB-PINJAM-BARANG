<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            if ($role === 'admin') {
                return redirect()->route('admin.login')->with('error', 'Silakan login sebagai administrator terlebih dahulu.');
            }
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        if ($user->status !== 'aktif') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Akun Anda sedang dinonaktifkan. Silakan hubungi admin.');
        }

        if ($user->role !== $role) {
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('customer.dashboard');
        }

        return $next($request);
    }
}
