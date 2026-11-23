<?php

namespace App\Http\Controllers;

use App\Models\Contact;

class ContactAdminController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->orderByDesc('id')->paginate(10);
        $unreadCount = Contact::where('is_read', false)->count();

        return view('posts.contacts.index', compact('contacts', 'unreadCount'));
    }

    public function show(Contact $contact)
    {
        if (! $contact->is_read) {
            $contact->update(['is_read' => true]);
        }

        return view('posts.contacts.show', compact('contact'));
    }
}
