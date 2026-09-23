<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Item;
use App\Models\Category;
use App\Models\Borrowing;
use App\Models\ReturnRecord;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalItems = Item::sum('stock');
        $availableItems = Item::where('status', 'Tersedia')->sum('stock');
        $borrowedItems = Borrowing::where('status', 'Dipinjam')->sum('quantity');
        $pendingBorrowings = Borrowing::where('status', 'Menunggu')->count();
        $pendingReturns = ReturnRecord::whereNull('verified_at')->count();
        $completedBorrowings = Borrowing::where('status', 'Selesai')->count();

        $recentBorrowings = Borrowing::with(['user', 'item'])
            ->latest()
            ->take(5)
            ->get();

        $pendingReturnsList = ReturnRecord::with(['borrowing.user', 'borrowing.item'])
            ->whereNull('verified_at')
            ->latest()
            ->take(5)
            ->get();

        $recentLogs = ActivityLog::with('user')
            ->latest()
            ->take(8)
            ->get();

        // Data Grafik 6 Bulan Terakhir
        $chartMonths = [];
        $chartBorrowData = [];
        $chartReturnData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subMonths($i);
            $monthKey = $date->translatedFormat('M Y');
            $chartMonths[] = $monthKey;
            
            $borrowCount = Borrowing::whereYear('borrow_date', $date->year)
                ->whereMonth('borrow_date', $date->month)
                ->count();
                
            $returnCount = Borrowing::where('status', 'Selesai')
                ->whereYear('return_date', $date->year)
                ->whereMonth('return_date', $date->month)
                ->count();
                
            $chartBorrowData[] = $borrowCount;
            $chartReturnData[] = $returnCount;
        }

        $totalBorrowingsCount = Borrowing::count();

        $statusCounts = [
            'Selesai' => Borrowing::where('status', 'Selesai')->count(),
            'Dipinjam' => Borrowing::where('status', 'Dipinjam')->count(),
            'Disetujui' => Borrowing::where('status', 'Disetujui')->count(),
            'Menunggu' => Borrowing::where('status', 'Menunggu')->count(),
            'Ditolak' => Borrowing::where('status', 'Ditolak')->count(),
        ];

        return view('admin.dashboard', compact(
            'totalItems',
            'availableItems',
            'borrowedItems',
            'pendingBorrowings',
            'pendingReturns',
            'completedBorrowings',
            'recentBorrowings',
            'pendingReturnsList',
            'chartMonths',
            'chartBorrowData',
            'chartReturnData',
            'totalBorrowingsCount',
            'statusCounts',
            'recentLogs'
        ));
    }
}
