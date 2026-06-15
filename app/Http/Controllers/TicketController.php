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
        return (new TicketResource($ticket->load(['user', 'assignedTo', 'department'])))->response()->setStatusCode(201);
    }

    public function show($id)
    {
        $ticket = Ticket::with(['user', 'assignedTo', 'department'])->findOrFail($id);
        return new TicketResource($ticket);
    }

    public function update(UpdateTicketRequest $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update($request->validated());
        return new TicketResource($ticket->load(['user', 'assignedTo', 'department']));
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
        return response()->json(['message' => 'Ticket deleted successfully']);
    }
}
