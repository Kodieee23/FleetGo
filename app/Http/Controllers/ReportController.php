<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Trip::with(['driver', 'vehicle', 'purpose', 'department', 'stops'])->latest();

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $trips = $query->paginate(20);
        
        // Use the same view but different layout or just pass to admin/manager
        $role = auth()->user()->role;
        return view($role . '.reports', compact('trips'));
    }

    private function buildExportQuery(Request $request)
    {
        $query = Trip::with(['driver', 'vehicle', 'purpose', 'department', 'stops'])->latest();

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return $query;
    }

    public function exportExcel(Request $request)
    {
        $query = $this->buildExportQuery($request);
        $fileName = 'driverlog_report_' . date('Y-m-d_H-i') . '.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\TripsExport($query), $fileName);
    }

    public function exportPdf(Request $request)
    {
        $query = $this->buildExportQuery($request);
        $trips = $query->get();
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf', compact('trips'));
        $fileName = 'driverlog_report_' . date('Y-m-d_H-i') . '.pdf';
        
        return $pdf->download($fileName);
    }
}
