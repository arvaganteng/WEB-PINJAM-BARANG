<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $totalBorrowings = Borrowing::where('user_id', $user->id)->count();
        $activeBorrowings = Borrowing::where('user_id', $user->id)->whereIn('status', ['Disetujui', 'Dipinjam'])->count();
        $completedBorrowings = Borrowing::where('user_id', $user->id)->where('status', 'Selesai')->count();

        return view('customer.profile.show', compact('user', 'totalBorrowings', 'activeBorrowings', 'completedBorrowings'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'old_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:6|confirmed',
        ]);

        if ($request->filled('old_password')) {
            if (!Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'Password lama tidak cocok.']);
            }
            $user->password = Hash::make($request->password);
        }

        $user->name = $validated['name'];
        $user->phone = $validated['phone'];
        $user->address = $validated['address'] ?? null;
        $user->save();

        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }
}
