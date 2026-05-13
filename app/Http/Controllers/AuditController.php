<?php

namespace App\Http\Controllers;

use App\Models\ActionLog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf; 

class AuditController extends Controller
{
    /**
     * Visualizza la pagina dell'Audit con i filtri.
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = ActionLog::with('user')->latest();

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return Inertia::render('Audit/Index', [
            'logs' => $query->get(),
            'filters' => $request->only(['start_date', 'end_date'])
        ]);
    }

    /**
     * Genera e scarica il PDF filtrato.
     */
    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = ActionLog::with('user')->latest();

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $logs = $query->get();

        $pdf = Pdf::loadView('pdf.audit-log', [
            'logs' => $logs,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);

        return $pdf->download('audit_log_aziendale.pdf');
    }
}