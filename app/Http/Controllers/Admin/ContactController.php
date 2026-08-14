<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Display all contact inquiries from website
     */
    public function index(Request $request)
    {
        $query = Contact::query();

        // Filter by read status
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'unread') {
                $query->where('is_read', false);
            } elseif ($status === 'read') {
                $query->where('is_read', true);
            }
        }

        $contacts = $query->latest()->paginate(15);
        
        return view('admin.contacts.index', compact('contacts'));
    }

    /**
     * Show a single contact inquiry
     */
    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        
        // Mark as read
        if (!$contact->is_read) {
            $contact->update(['is_read' => true]);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Reply to a contact inquiry via email
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply_message' => 'required|string|max:5000',
        ]);

        $contact = Contact::findOrFail($id);

        try {
            send_template_email('contact-reply', $contact->email, [
                'name'      => $contact->name,
                'subject'   => $contact->subject ?? 'General Enquiry',
                'message'   => $request->reply_message,
                'logo'      => url('assets/images/logo/logo.png'),
                'site_name' => config('app.name', 'BittGold'),
            ]);

            // Update the contact record with the reply
            $contact->update([
                'is_read' => true,
            ]);

            return back()->with('success', 'Reply sent successfully to ' . $contact->email);
        } catch (\Throwable $exception) {
            Log::error('Contact reply email could not be sent.', [
                'contact_id' => $contact->id,
                'exception'  => $exception->getMessage(),
            ]);

            return back()->with('error', 'Failed to send email. Please try again.');
        }
    }

    /**
     * Delete a contact inquiry
     */
    public function delete($id)
    {
        $contact = Contact::findOrFail($id);
        $email = $contact->email;
        $contact->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Contact inquiry from ' . $email . ' has been deleted.');
    }
}
