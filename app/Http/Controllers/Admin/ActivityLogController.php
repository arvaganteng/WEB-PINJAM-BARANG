<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('description', 'like', '%' . $request->search . '%')
                  ->orWhere('causer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('action', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('action', 'like', $request->category . '.%');
        }

        if ($request->filled('causer_role')) {
            $query->where('causer_role', $request->causer_role);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->latest()->paginate(10)->withQueryString();

        $categories = [
            'auth'       => 'Autentikasi',
            'borrowing'  => 'Peminjaman',
            'return'     => 'Pengembalian',
            'item'       => 'Barang',
            'category'   => 'Kategori',
            'customer'   => 'Customer',
        ];

        return view('admin.activity-logs.index', compact('logs', 'categories'));
    }

    public function clearOld(Request $request)
    {
        $days = (int) $request->input('days', 30);
        $count = ActivityLog::where('created_at', '<', now()->subDays($days))->count();
        ActivityLog::where('created_at', '<', now()->subDays($days))->delete();

        return redirect()->route('admin.activity-logs.index')
            ->with('success', "{$count} log aktivitas yang lebih dari {$days} hari berhasil dihapus.");
    }
}
