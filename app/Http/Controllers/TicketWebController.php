<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class TicketWebController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['user', 'assignedTo'])->orderBy('id', 'asc')->get();
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $users = User::all();
        return view('tickets.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|min:5|max:255',
            'description' => 'required|string|min:10',
            'status'      => 'in:open,in_progress,closed',
            'priority'    => 'in:low,medium,high',
            'user_id'     => 'required|exists:users,id',
        ]);

        Ticket::create($request->all());
        return redirect()->route('tickets.index')->with('success', 'Ticket created!');
    }

    public function show($id)
    {
        $ticket = Ticket::with('user')->findOrFail($id);
        return view('tickets.show', compact('ticket'));
    }

    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        $users = User::all();
        return view('tickets.edit', compact('ticket', 'users'));
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $request->validate([
            'title'       => 'required|string|min:5|max:255',
            'description' => 'required|string|min:10',
            'status'      => 'in:open,in_progress,closed',
            'priority'    => 'in:low,medium,high',
            'user_id'     => 'required|exists:users,id',
        ]);
        $ticket->update($request->all());
        return redirect()->route('tickets.index')->with('success', 'Ticket updated!');
    }

    public function destroy($id)
    {
        Ticket::findOrFail($id)->delete();
        return redirect()->route('tickets.index')->with('success', 'Ticket deleted!');
    }
}