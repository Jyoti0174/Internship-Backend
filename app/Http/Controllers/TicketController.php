<?php

namespace App\Http\Controllers;

use App\Helpers\NotificationHelper;
use App\Http\Requests\AssignTicketRequest;
use App\Http\Requests\UpdateTicketStatusRequest;
use App\Http\Resources\TicketResource;
use App\Models\ActivityLog;
use App\Models\Ticket;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

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

        if ($ticket->user) {
            NotificationHelper::sendIfEnabled($ticket->user, 'ticket_created', [
                'title'      => $ticket->title,
                'priority'   => $ticket->priority,
                'status'     => $ticket->status,
                'created_at' => $ticket->created_at,
            ]);
        }

        return (new TicketResource($ticket))->response()->setStatusCode(201);
    }

    public function show(Request $request, $id)
    {
        $ticket = Ticket::with([
            'user',
            'assignedTo',
            'department',
            'comments.user'
        ])->findOrFail($id);

        // Employee sirf apna ticket dekh sakta hai
        if ($request->user()->role === 'employee') {
            if (
                $ticket->user_id !== $request->user()->id &&
                $ticket->assigned_to !== $request->user()->id
            ) {
                return $this->errorResponse(
                    'Access denied. You can only view your own tickets.',
                    403
                );
            }
        }

        return new TicketResource($ticket);
    }

    public function update(UpdateTicketRequest $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        // Employee apna ticket update kar sakta hai
        if ($request->user()->role === 'employee') {
            if ($ticket->user_id !== $request->user()->id) {
                return $this->errorResponse(
                    'Access denied. You can only update your own tickets.',
                    403
                );
            }
        }

        $ticket->update($request->validated());

        return new TicketResource(
            $ticket->load(['user', 'assignedTo', 'department'])
        );
    }

    public function destroy(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        // Employee apna ticket delete kar sakta hai
        if ($request->user()->role === 'employee') {
            if ($ticket->user_id !== $request->user()->id) {
                return $this->errorResponse(
                    'Access denied. You can only delete your own tickets.',
                    403
                );
            }
        }

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

        if ($ticket->assignedTo) {
            NotificationHelper::sendIfEnabled($ticket->assignedTo, 'ticket_assigned', [
                'title'       => $ticket->title,
                'priority'    => $ticket->priority,
                'assigned_by' => auth()->user()->name,
            ]);
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
        $user = $request->user();

        // Employee sirf apne assigned tickets dekhe
        if ($user->role === 'employee') {
            $tickets = Ticket::with(['user', 'assignedTo', 'department'])
                ->where(function ($query) use ($user) {
                    $query->where('assigned_to', $user->id)
                          ->orWhere('user_id', $user->id);
                })
                ->get();
        } else {
            $tickets = Ticket::with(['user', 'assignedTo', 'department'])
                ->where('assigned_to', $user->id)
                ->get();
        }

        return $this->successResponse(
            $tickets,
            'Assigned tickets fetched successfully.'
        );
    }

    // PATCH /api/tickets/{id}/status
    public function updateStatus(UpdateTicketStatusRequest $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        // Employee sirf apne ticket ka status update kar sakta hai
        if ($request->user()->role === 'employee') {
            if (
                $ticket->user_id !== $request->user()->id &&
                $ticket->assigned_to !== $request->user()->id
            ) {
                return $this->errorResponse(
                    'Access denied. You can only update status of your own tickets.',
                    403
                );
            }
        }

        $oldStatus = $ticket->status;

        $ticket->update([
            'status' => $request->status,
        ]);

        $ticket->load(['user', 'assignedTo', 'department']);

        if ($ticket->user) {
            NotificationHelper::sendIfEnabled($ticket->user, 'status_changed', [
                'title'      => $ticket->title,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
            ]);
        }

        return $this->successResponse(
            $ticket,
            'Ticket status updated successfully.'
        );
    }
}