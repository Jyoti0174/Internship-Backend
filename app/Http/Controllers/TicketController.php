<?php

namespace App\Http\Controllers;

use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['user', 'assignedTo', 'department']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        $sortField = $request->get('sort', 'created_at');

        $allowedSorts = ['title', 'priority', 'created_at'];

        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'created_at';
        }

        $tickets = $query->orderBy($sortField, 'asc')->paginate(10);

        return TicketResource::collection($tickets);
    }

    public function store(StoreTicketRequest $request)
    {
        $ticket = Ticket::create($request->validated());

        return (new TicketResource(
            $ticket->load(['user', 'assignedTo', 'department'])
        ))->response()->setStatusCode(201);
    }

    public function show($id)
    {
        $ticket = Ticket::with([
            'user',
            'assignedTo',
            'department',
            'comments.user'
        ])->findOrFail($id);

        return new TicketResource($ticket);
    }

    public function update(UpdateTicketRequest $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $ticket->update($request->validated());

        return new TicketResource(
            $ticket->load(['user', 'assignedTo', 'department'])
        );
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        // Resequence ticket numbers
        $tickets = Ticket::orderBy('id')->get();
        $i = 1;

        foreach ($tickets as $t) {
            \DB::table('tickets')
                ->where('id', $t->id)
                ->update(['ticket_number' => $i++]);
        }

        return response()->json([
            'message' => 'Ticket deleted successfully'
        ]);
    }

    public function stats()
    {
        $stats = [
            'total_tickets' => [
                'label' => 'Total Tickets',
                'count' => Ticket::count(),
            ],
            'open_tickets' => [
                'label' => 'Open Tickets',
                'count' => Ticket::where('status', 'open')->count(),
            ],
            'in_progress_tickets' => [
                'label' => 'In Progress Tickets',
                'count' => Ticket::where('status', 'in_progress')->count(),
            ],
            'closed_tickets' => [
                'label' => 'Closed Tickets',
                'count' => Ticket::where('status', 'closed')->count(),
            ],
            'high_priority_tickets' => [
                'label' => 'High Priority Tickets',
                'count' => Ticket::where('priority', 'high')->count(),
            ],
        ];

        return response()->json([
            'success' => true,
            'message' => 'Dashboard statistics fetched successfully.',
            'data' => $stats,
        ], 200);
    }
    // PUT /api/tickets/{id}/assign - Assign ticket to a user
public function assign(Request $request, $id)
{
    $request->validate([
        'assigned_to' => 'required|exists:users,id',
    ]);

    $ticket = Ticket::findOrFail($id);
    $ticket->update([
        'assigned_to' => $request->assigned_to,
        'status'      => 'in_progress',
    ]);

    return response()->json([
        'message' => 'Ticket assigned successfully',
        'ticket'  => $ticket->load(['user', 'assignedTo', 'department']),
    ]);
}

// PUT /api/tickets/{id}/unassign - Unassign ticket
public function unassign($id)
{
    $ticket = Ticket::findOrFail($id);
    $ticket->update([
        'assigned_to' => null,
        'status'      => 'open',
    ]);

    return response()->json([
        'message' => 'Ticket unassigned successfully',
        'ticket'  => $ticket->load(['user', 'assignedTo', 'department']),
    ]);
}

// GET /api/tickets/assigned-to-me - Get tickets assigned to logged in user
public function assignedToMe(Request $request)
{
    $tickets = Ticket::with(['user', 'assignedTo', 'department'])
        ->where('assigned_to', $request->user()->id)
        ->get();

    return response()->json([
        'message' => 'Assigned tickets fetched successfully',
        'total'   => $tickets->count(),
        'tickets' => $tickets,
    ]);
}
// PATCH /api/tickets/{id}/status - Update ticket status only
public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:open,in_progress,closed',
    ]);

    $ticket = Ticket::findOrFail($id);
    $ticket->update([
        'status' => $request->status,
    ]);

    return response()->json([
        'message' => 'Ticket status updated successfully',
        'ticket'  => $ticket->load(['user', 'assignedTo', 'department']),
    ]);
}
}