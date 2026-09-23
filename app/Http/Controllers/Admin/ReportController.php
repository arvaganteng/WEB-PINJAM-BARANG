<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\ReturnRecord;
use App\Models\Item;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Tampilkan halaman laporan & filter
     */
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-t'));
        $status = $request->get('status', 'Semua');
        $type = $request->get('type', 'peminjaman');
        $colorMode = $request->get('color_mode', 'color');

        $data = $this->getReportData($startDate, $endDate, $status);
        $borrowings = $data['borrowings'];
        $stats = $data['stats'];

        return view('admin.reports.index', compact('borrowings', 'stats', 'startDate', 'endDate', 'status', 'type', 'colorMode'));
    }

    /**
     * Export laporan dalam format PDF atau Excel
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'pdf');
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-t'));
        $status = $request->get('status', 'Semua');
        $type = $request->get('type', 'peminjaman');
        $colorMode = $request->get('color_mode', 'color');

        $data = $this->getReportData($startDate, $endDate, $status);
        $borrowings = $data['borrowings'];
        $stats = $data['stats'];

        $filenameBase = 'Laporan-Peminjaman-' . $startDate . '-sd-' . $endDate;

        // 1. EXCEL EXPORT (.xls)
        if ($format === 'excel') {
            $view = view('admin.reports.excel', compact('borrowings', 'stats', 'startDate', 'endDate', 'status', 'type'))->render();
            
            return response($view, 200, [
                'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="' . $filenameBase . '.xls"',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        }

        // 2. PDF EXPORT (.pdf via Dompdf)
        $logoBase64 = '';
        if (extension_loaded('gd')) {
            $logoPath = public_path('images/logo.png');
            if (file_exists($logoPath)) {
                $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
            }
        }

        $html = view('admin.reports.pdf', compact('borrowings', 'stats', 'startDate', 'endDate', 'status', 'type', 'logoBase64', 'colorMode'))->render();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Jika user minta download langsung atau view inline
        $disposition = $request->get('download') == '1' ? 'attachment' : 'inline';

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => $disposition . '; filename="' . $filenameBase . '.pdf"',
        ]);
    }

    /**
     * Query data peminjaman & hitung statistik berdasarkan filter
     */
    private function getReportData($startDate, $endDate, $status)
    {
        $query = Borrowing::with(['user', 'item', 'returnRecord.verifiedBy'])
            ->whereDate('borrow_date', '>=', $startDate)
            ->whereDate('borrow_date', '<=', $endDate);

        if ($status && $status !== 'Semua') {
            $query->where('status', $status);
        }

        $borrowings = $query->latest()->get();

        $rentalRevenue = $borrowings->where('status', 'Selesai')->sum('total_price');
        
        $fineRevenue = $borrowings->sum(function ($b) {
            return ($b->returnRecord && $b->returnRecord->fine_payment_status === 'Lunas') 
                ? $b->returnRecord->fine_amount 
                : 0;
        });

        $pendingFineAmount = $borrowings->sum(function ($b) {
            return ($b->returnRecord && $b->returnRecord->fine_amount > 0 && $b->returnRecord->fine_payment_status !== 'Lunas') 
                ? $b->returnRecord->fine_amount 
                : 0;
        });

        $stats = [
            'total' => $borrowings->count(),
            'approved' => $borrowings->whereIn('status', ['Disetujui', 'Dipinjam', 'Selesai'])->count(),
            'rejected' => $borrowings->where('status', 'Ditolak')->count(),
            'pending' => $borrowings->where('status', 'Menunggu')->count(),
            'rental_revenue' => $rentalRevenue,
            'fine_revenue' => $fineRevenue,
            'pending_fine' => $pendingFineAmount,
            'revenue' => $rentalRevenue + $fineRevenue,
        ];

        return compact('borrowings', 'stats');
    }
}
