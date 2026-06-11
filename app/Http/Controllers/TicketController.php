<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with('user')->latest()->get();
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'status'      => 'required|in:open,in_progress,closed',
            'priority'    => 'required|in:low,medium,high',
            'user_id'     => 'required|exists:users,id',
        ]);

        Ticket::create($validated);
        return redirect()->route('tickets.index');
    }

    public function show($id)
    {
        $ticket = Ticket::with('user')->findOrFail($id);
        return view('tickets.show', compact('ticket'));
    }

    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('tickets.edit', compact('ticket'));
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'status'      => 'required|in:open,in_progress,closed',
            'priority'    => 'required|in:low,medium,high',
            'user_id'     => 'required|exists:users,id',
        ]);

        $ticket->update($validated);
        return redirect()->route('tickets.index');
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
        return redirect()->route('tickets.index');
    }
}