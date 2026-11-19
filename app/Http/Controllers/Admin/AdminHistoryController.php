<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceHistory;
use Illuminate\Http\Request;

class AdminHistoryController extends Controller
{
    /**
     * Display service history list
     */
    public function index(Request $request)
    {
        $query = ServiceHistory::with(['user', 'vehicle']);

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Date range filter
        if ($request->filled('start_date')) {
            $query->whereDate('completed_at', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('completed_at', '<=', $request->input('end_date'));
        }

        // Service type filter
        if ($request->filled('service_type')) {
            $query->where('service_type', $request->input('service_type'));
        }

        $histories = $query->orderBy('completed_at', 'desc')->paginate(15);

        // Statistics
        $totalServices = ServiceHistory::count();
        $monthlyRevenue = ServiceHistory::thisMonth()->where('payment_status', 'paid')->sum('total_cost');
        $avgDuration = ServiceHistory::whereNotNull('entered_at')
            ->whereNotNull('completed_at')
            ->get()
            ->avg(fn (ServiceHistory $history) => $history->duration) ?? 0;
        $completionRate = 100; // 100% karena semua di history adalah completed

        return view('dashboard.admin.history.index', [
            'title' => 'Service History',
            'description' => 'Ringkasan servis selesai dan catatan histori kendaraan.',
            'active' => 'history',
            'totalServices' => $totalServices,
            'monthlyRevenue' => $monthlyRevenue,
            'avgDuration' => round($avgDuration, 1),
            'completionRate' => $completionRate,
            'histories' => $histories,
        ]);
    }

    /**
     * Show detailed history for a service
     */
    public function show($id)
    {
        $history = ServiceHistory::with(['user', 'vehicle'])->findOrFail($id);

        return view('dashboard.admin.history.show', [
            'title' => 'Detail Histori Servis',
            'active' => 'history',
            'history' => $history,
        ]);
    }

    /**
     * Export history report
     */
    public function export(Request $request)
    {
        $format = $request->query('format', 'pdf');

        $query = ServiceHistory::with(['user', 'vehicle']);

        if ($request->filled('start_date')) {
            $query->whereDate('completed_at', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('completed_at', '<=', $request->input('end_date'));
        }

        $histories = $query->orderBy('completed_at', 'desc')->get();

        if ($format === 'csv') {
            return $this->exportCsv($histories);
        }

        // Default to PDF
        return response()->json([
            'message' => 'Export PDF akan diimplementasikan',
            'total_records' => $histories->count(),
        ]);
    }

    /**
     * Export to CSV
     */
    private function exportCsv($histories)
    {
        $filename = 'service-history-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($histories) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'Pelanggan', 'Kendaraan', 'Jenis Servis', 'Total Biaya', 'Status Pembayaran']);

            foreach ($histories as $history) {
                fputcsv($file, [
                    $history->completed_at->format('Y-m-d'),
                    $history->user->name,
                    $history->vehicle->brand . ' ' . $history->vehicle->model,
                    $history->service_type,
                    'Rp ' . number_format($history->total_cost, 0, ',', '.'),
                    ucfirst($history->payment_status),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get history statistics
     */
    public function statistics()
    {
        $monthlyRevenue = ServiceHistory::thisMonth()->where('payment_status', 'paid')->sum('total_cost');
        $totalServices = ServiceHistory::count();
        $avgDuration = ServiceHistory::whereNotNull('entered_at')
            ->whereNotNull('completed_at')
            ->get()
            ->avg(fn (ServiceHistory $history) => $history->duration) ?? 0;

        return response()->json([
            'total_services' => $totalServices,
            'total_revenue' => $monthlyRevenue,
            'average_duration' => round($avgDuration, 2),
            'completion_rate' => 100,
            'currency' => 'IDR',
        ]);
    }
}
