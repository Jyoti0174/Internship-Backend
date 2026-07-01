<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignTicketRequest;
use App\Http\Requests\UpdateTicketStatusRequest;
use App\Http\Resources\TicketResource;
use App\Mail\CommentAddedMail;
use App\Mail\TicketAssignedMail;
use App\Mail\TicketCreatedMail;
use App\Mail\TicketStatusChangedMail;
use App\Models\Ticket;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TicketController extends Controller
{
    use ApiResponse;

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
        $ticket->load(['user', 'assignedTo', 'department']);

        // Send email to ticket creator
        if ($ticket->user && $ticket->user->email) {
            Mail::to($ticket->user->email)->send(new TicketCreatedMail($ticket));
        }

        return (new TicketResource($ticket))->response()->setStatusCode(201);
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

        $tickets = Ticket::orderBy('id')->get();
        $i = 1;

        foreach ($tickets as $t) {
            \DB::table('tickets')
                ->where('id', $t->id)
                ->update(['ticket_number' => $i++]);
        }

        return $this->successResponse(null, 'Ticket deleted successfully.');
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

        return $this->successResponse($stats, 'Dashboard statistics fetched successfully.');
    }

    // PUT /api/tickets/{id}/assign
    public function assign(AssignTicketRequest $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update([
            'assigned_to' => $request->assigned_to,
            'status'      => 'in_progress',
        ]);

        $ticket->load(['user', 'assignedTo', 'department']);

        // Send email to assigned user
        if ($ticket->assignedTo && $ticket->assignedTo->email) {
            Mail::to($ticket->assignedTo->email)->send(new TicketAssignedMail($ticket));
        }

        return $this->successResponse(
            $ticket,
            'Ticket assigned successfully.'
        );
    }

    // PUT /api/tickets/{id}/unassign
    public function unassign($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update([
            'assigned_to' => null,
            'status'      => 'open',
        ]);

        return $this->successResponse(
            $ticket->load(['user', 'assignedTo', 'department']),
            'Ticket unassigned successfully.'
        );
    }

    // GET /api/tickets/assigned-to-me
    public function assignedToMe(Request $request)
    {
        $tickets = Ticket::with(['user', 'assignedTo', 'department'])
            ->where('assigned_to', $request->user()->id)
            ->get();

        return $this->successResponse(
            $tickets,
            'Assigned tickets fetched successfully.'
        );
    }

    // PATCH /api/tickets/{id}/status
    public function updateStatus(UpdateTicketStatusRequest $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $oldStatus = $ticket->status;

        $ticket->update([
            'status' => $request->status,
        ]);

        $ticket->load(['user', 'assignedTo', 'department']);

        // Send email to ticket creator
        if ($ticket->user && $ticket->user->email) {
            Mail::to($ticket->user->email)->send(new TicketStatusChangedMail($ticket, $oldStatus, $request->status));
        }

        return $this->successResponse(
            $ticket,
            'Ticket status updated successfully.'
        );
    }
}