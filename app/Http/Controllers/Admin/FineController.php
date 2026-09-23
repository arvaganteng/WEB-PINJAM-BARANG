<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnRecord;
use Illuminate\Http\Request;

class FineController extends Controller
{
    public function index(Request $request)
    {
        $query = ReturnRecord::where('fine_amount', '>', 0)
            ->with(['borrowing.user', 'borrowing.item', 'verifiedBy']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('return_code', 'like', "%{$search}%")
                  ->orWhereHas('borrowing', function ($bq) use ($search) {
                      $bq->where('borrow_code', 'like', "%{$search}%")
                         ->orWhereHas('user', function ($uq) use ($search) {
                             $uq->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                         })
                         ->orWhereHas('item', function ($iq) use ($search) {
                             $iq->where('name', 'like', "%{$search}%");
                         });
                  });
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'unpaid') {
                $query->where('fine_payment_status', 'Belum Bayar');
            } elseif ($request->status === 'pending') {
                $query->where('fine_payment_status', 'Menunggu Verifikasi');
            } elseif ($request->status === 'paid') {
                $query->where('fine_payment_status', 'Lunas');
            }
        }

        $fines = $query->latest()->paginate(12);

        // Calculate admin statistics
        $allFines = ReturnRecord::where('fine_amount', '>', 0)->get();

        $totalRevenue = $allFines->where('fine_payment_status', 'Lunas')->sum('fine_amount');
        $totalPending = $allFines->where('fine_payment_status', 'Menunggu Verifikasi')->sum('fine_amount');
        $totalUnpaid = $allFines->where('fine_payment_status', 'Belum Bayar')->sum('fine_amount');

        $paidCount = $allFines->where('fine_payment_status', 'Lunas')->count();
        $pendingCount = $allFines->where('fine_payment_status', 'Menunggu Verifikasi')->count();
        $unpaidCount = $allFines->where('fine_payment_status', 'Belum Bayar')->count();

        return view('admin.fines.index', compact(
            'fines',
            'totalRevenue',
            'totalPending',
            'totalUnpaid',
            'paidCount',
            'pendingCount',
            'unpaidCount'
        ));
    }
}
