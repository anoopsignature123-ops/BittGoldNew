<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Support;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index(Request $request)
    {
        $query = Support::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->latest()->paginate(10);
        return view('admin.support.index', compact('tickets'));
    }

    public function show($ticket_id)
    {
        $ticket = Support::with('user')->where('ticket_id', $ticket_id)->firstOrFail();
        return view('admin.support.show', compact('ticket'));
    }

    public function reply(Request $request, $ticket_id)
    {
        $request->validate(['reply' => 'required|string']);

        $ticket = Support::where('ticket_id', $ticket_id)->firstOrFail();
        
        $ticket->update([
            'reply' => $request->reply,
            'status' => 'answered'
        ]);

        return back()->with('success', 'Reply sent to user successfully.');
    }

    public function updateStatus(Request $request, $ticket_id)
    {
        $ticket = Support::where('ticket_id', $ticket_id)->firstOrFail();
        $ticket->update(['status' => $request->status]);

        return back()->with('success', 'Ticket status updated successfully.');
    }
}