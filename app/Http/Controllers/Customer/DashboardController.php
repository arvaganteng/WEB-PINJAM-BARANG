<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $activeBorrowings = Borrowing::where('user_id', $userId)
            ->whereIn('status', ['Disetujui', 'Dipinjam'])
            ->count();

        $pendingBorrowings = Borrowing::where('user_id', $userId)
            ->where('status', 'Menunggu')
            ->count();

        $completedBorrowings = Borrowing::where('user_id', $userId)
            ->where('status', 'Selesai')
            ->count();

        $pendingVerificationBorrowings = Borrowing::where('user_id', $userId)
            ->where('status', 'Menunggu Verifikasi')
            ->count();

        $recentItems = Item::with('category')->where('status', 'Tersedia')->latest()->take(3)->get();
        $recentBorrowings = Borrowing::where('user_id', $userId)->with(['item', 'returnRecord'])->latest()->take(3)->get();

        // Due date warnings (active borrowings due in <= 2 days or overdue)
        $dueBorrowings = Borrowing::where('user_id', $userId)
            ->where('status', 'Dipinjam')
            ->where('return_date', '<=', now()->addDays(2)->toDateString())
            ->with('item')
            ->get();

        return view('customer.dashboard', compact(
            'activeBorrowings',
            'pendingBorrowings',
            'completedBorrowings',
            'pendingVerificationBorrowings',
            'recentItems',
            'recentBorrowings',
            'dueBorrowings'
        ));
    }
}
