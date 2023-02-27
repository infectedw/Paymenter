<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Tickets;
use Illuminate\Http\Request;
use App\Models\TicketMessages;
use App\Http\Controllers\Controller;

class TicketsController extends Controller
{
    public function index()
    {
        $tickets = Tickets::where('status', '!=', 'closed')->get();
        $closed = Tickets::where('status', 'closed')->get();

        return view('admin.tickets.index', compact('tickets', 'closed'));
    }

    public function create()
    {
        $users = User::all();

        return view('admin.tickets.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'user' => 'required',
            'priority' => 'required',
        ]);

        $ticket = new Tickets([
            'title' => $request->get('title'),
            'status' => 'open',
            'priority' => $request->priority,
            'client' => $request->get('user'),
        ]);
        $ticket->save();

        TicketMessages::create([
            'ticket_id' => $ticket->id,
            'message' => $request->get('description'),
            'user_id' => auth()->user()->id,
        ]);

        return redirect()->route('admin.tickets.show', $ticket)->with('success', 'Ticket has been created');
    }

    public function show(Tickets $ticket)
    {
        return view('admin.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Tickets $ticket)
    {
        $request->validate([
            'message' => 'required',
        ]);

        $ticket->status = 'replied';
        $ticket->save();
        $ticket->messages()->create([
            'user_id' => auth()->user()->id,
            'message' => $request->get('message'),
        ]);

        return redirect()->route('admin.tickets.show', $ticket)->with('success', 'Message has been sent');
    }

    public function status(Request $request, Tickets $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,closed',
        ]);

        $ticket->status = $request->get('status');
        $ticket->save();

        return redirect()->route('admin.tickets.show', $ticket)->with('success', 'Ticket status has been updated');
    }
}
