<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Show the Contact Us page on the website.
     */
    public function index()
    {
        return view('website.contact');
    }

    /**
     * Handle the Contact Us form submission.
     * - Saves the enquiry in the database
     * - Sends a notification email to support using the 'contact-us' template
     */
    public function store(ContactRequest $request)
    {
        $validated = $request->validated();

        $contact = Contact::create($validated);

        try {
            send_template_email('contact-us', config('mail.from.address', 'support@bittgold.com'), [
                'name'      => $contact->name,
                'email'     => $contact->email,
                'phone'     => $contact->phone ?? 'N/A',
                'subject'   => $contact->subject ?? 'General Enquiry',
                'message'   => $contact->message,
                'logo'      => url('assets/images/logo/logo.png'),
                'site_name' => config('app.name', 'BittGold'),
            ]);
        } catch (\Throwable $exception) {
            Log::error('Contact us notification email could not be sent.', [
                'contact_id' => $contact->id,
                'exception'  => $exception->getMessage(),
            ]);
        }

        return back()->with('success', 'Your message has been sent successfully. We will get back to you soon.');
    }
}