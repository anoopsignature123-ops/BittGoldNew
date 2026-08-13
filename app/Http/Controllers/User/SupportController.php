<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Support;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SupportController extends Controller
{
    public function index()
    {
        $user = $this->authenticatedUser();
        $tickets = Support::where('user_id', $user->id)->latest()->paginate(10);
        return view('user.support.index', compact('tickets'));
    }

    public function create()
    {
        return view('user.support.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'priority' => 'required|string',
            'message' => 'required|string',
        ]);

        Support::create([
            'ticket_id' => 'BG-TKT-' . strtoupper(Str::random(6)),
            'user_id' => $this->authenticatedUser()->id,
            'subject' => $request->subject,
            'category' => $request->category,
            'priority' => $request->priority,
            'message' => $request->message,
            'status' => 'open',
        ]);

        return redirect()->route('user.supports.index')->with('success', 'Support ticket created successfully.');
    }

    public function show($ticket_id)
    {
        $ticket = Support::where('ticket_id', $ticket_id)->where('user_id', $this->authenticatedUser()->id)->firstOrFail();

        // Agar admin ne reply kiya tha aur user ne dekh liya, toh status ko 'open' ya read mark kar sakte hain
        if ($ticket->status === 'answered') {
            // Optional: Aap status change karna chahein toh yahan kar sakte hain
        }

        return view('user.support.show', compact('ticket'));
    }

    public function reply(Request $request, $ticket_id)
    {
        $request->validate(['message' => 'required|string']);

        $ticket = Support::where('ticket_id', $ticket_id)->where('user_id', $this->authenticatedUser()->id)->firstOrFail();

        $ticket->update([
            'reply' => $request->message, // Agar aap chahein toh ise multi-message chat table me bhi convert kar sakte hain, filhal schema ke mutabiq update kiya hai
            'status' => 'answered'
        ]);

        return back()->with('success', 'Reply sent successfully.');
    }
}