<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->buildFilteredQuery($request);

        $total = $query->count();

        $tickets = $query->orderBy('created_at', 'desc')->get()->map(function ($ticket) {
            return [
                'id'          => $ticket->id,
                'title'       => $ticket->title,
                'status'      => $ticket->status,
                'priority'    => $ticket->priority,
                'department'  => $ticket->department->name ?? 'N/A',
                'user'        => $ticket->user->name ?? 'N/A',
                'assigned_to' => $ticket->assignedTo->name ?? 'Unassigned',
                'created_at'  => $ticket->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Report data fetched successfully.',
            'total'   => $total,
            'data'    => $tickets,
        ], 200);
    }

    public function export(Request $request)
    {
        $query = $this->buildFilteredQuery($request);
        $tickets = $query->orderBy('created_at', 'desc')->get();

        $filename = 'tickets_report_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($tickets) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['ID', 'Title', 'Status', 'Priority', 'Department', 'User', 'Assigned To', 'Created At']);

            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket->id,
                    $ticket->title,
                    $ticket->status,
                    $ticket->priority,
                    $ticket->department->name ?? 'N/A',
                    $ticket->user->name ?? 'N/A',
                    $ticket->assignedTo->name ?? 'Unassigned',
                    $ticket->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // GET /api/reports/export-pdf
    public function exportPdf(Request $request)
    {
        $query = $this->buildFilteredQuery($request);
        $total = $query->count();
        $tickets = $query->orderBy('created_at', 'desc')->get();

        // Applied filters summary
        $filters = array_filter([
            'Status'      => $request->status,
            'Priority'    => $request->priority,
            'Department'  => $request->department_id,
            'Date From'   => $request->date_from,
            'Date To'     => $request->date_to,
        ]);

        $data = [
            'title'        => 'Employee Helpdesk — Ticket Report',
            'generated_at' => now()->format('d M Y, h:i A'),
            'filters'      => $filters,
            'total'        => $total,
            'tickets'      => $tickets,
        ];

        $pdf = Pdf::loadView('reports.tickets-pdf', $data)
            ->setPaper('a4', 'landscape')
            ->setOption('defaultFont', 'sans-serif')
            ->setOption('isRemoteEnabled', true);

        $filename = 'tickets_report_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }

    private function buildFilteredQuery(Request $request)
    {
        $query = Ticket::with(['user', 'assignedTo', 'department']);

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return $query;
    }
}