<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ActivityLogController extends Controller
{
    /**
     * GET /api/tickets/{id}/activity-logs
     */
    public function index(int $id): JsonResponse
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'success'    => false,
                'statusCode' => 404,
                'message'    => 'Ticket not found',
                'data'       => null,
                'timestamp'  => now()->toIso8601String(),
            ], 404);
        }

        $logs = $ticket->activityLogs()
            ->with('user:id,name,email')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($log) {
                return [
                    'id'          => $log->id,
                    'action'      => $log->action,
                    'description' => $log->description,
                    'field_name'  => $log->field_name,
                    'old_value'   => $log->old_value,
                    'new_value'   => $log->new_value,
                    'performed_by' => $log->user ? [
                        'id'    => $log->user->id,
                        'name'  => $log->user->name,
                        'email' => $log->user->email,
                    ] : null,
                    'created_at'  => $log->created_at->toIso8601String(),
                ];
            });

        return response()->json([
            'success'    => true,
            'statusCode' => 200,
            'message'    => 'Activity logs fetched successfully',
            'data'       => $logs,
            'timestamp'  => now()->toIso8601String(),
        ], 200);
    }
}