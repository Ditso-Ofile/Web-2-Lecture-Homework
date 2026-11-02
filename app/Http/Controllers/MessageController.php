<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\View\View;

/**
 * TASK 7 - Messages menu.
 * Lists the messages sent from the Contact form, newest first, with the sending time.
 * The route is inside the 'auth' middleware group, so only logged-in users can open it.
 */
class MessageController extends Controller
{
    public function index(): View
    {
        $messages = Message::orderByDesc('created_at')
            ->orderByDesc('id')        // same second -> the later insert first
            ->paginate(10);

        return view('messages.index', compact('messages'));
    }
}
