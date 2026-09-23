<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ReturnRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FineController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $query = ReturnRecord::whereHas('borrowing', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->where('fine_amount', '>', 0)->with(['borrowing.item']);

        if ($request->filled('status')) {
            if ($request->status === 'unpaid') {
                $query->where('fine_payment_status', 'Belum Bayar');
            } elseif ($request->status === 'pending') {
                $query->where('fine_payment_status', 'Menunggu Verifikasi');
            } elseif ($request->status === 'paid') {
                $query->where('fine_payment_status', 'Lunas');
            }
        }

        $fines = $query->latest()->get();

        // Calculate summary statistics
        $allUserFines = ReturnRecord::whereHas('borrowing', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->where('fine_amount', '>', 0)->get();

        $totalFineAmount = $allUserFines->sum('fine_amount');
        $paidFineAmount = $allUserFines->where('fine_payment_status', 'Lunas')->sum('fine_amount');
        $unpaidFineAmount = $allUserFines->where('fine_payment_status', '!=', 'Lunas')->sum('fine_amount');
        $pendingCount = $allUserFines->where('fine_payment_status', 'Menunggu Verifikasi')->count();
        $unpaidCount = $allUserFines->where('fine_payment_status', 'Belum Bayar')->count();

        return view('customer.fines.index', compact(
            'fines',
            'totalFineAmount',
            'paidFineAmount',
            'unpaidFineAmount',
            'pendingCount',
            'unpaidCount'
        ));
    }
}
