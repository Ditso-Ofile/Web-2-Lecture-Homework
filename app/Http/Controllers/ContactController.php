<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * TASK 6 - Contact menu.
 * GET  /contact  shows the form
 * POST /contact  validates the data on the server and saves it into the `messages` table
 */
class ContactController extends Controller
{
    public function create(): View
    {
        return view('contact.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // Server-side validation: on failure Laravel goes back to the form with the error messages
        $validated = $request->validate([
            'name'    => 'required|string|min:3|max:100',
            'email'   => 'required|email|max:150',
            'subject' => 'nullable|string|max:150',
            'message' => 'required|string|min:10|max:2000',
        ], [
            'message.min' => 'Please write at least 10 characters.',
        ]);

        try {
            // Mass assignment - only the fields in Message::$fillable are saved
            Message::create($validated);
        } catch (\Exception $e) {
            return back()->withInput()->with('failed', 'Your message could not be saved. Please try again.');
        }

        return redirect()->route('contact.create')
            ->with('success', 'Thank you, ' . $validated['name'] . '! Your message has been sent.');
    }
}
