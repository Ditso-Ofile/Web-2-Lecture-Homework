<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * TASK 6 + TASK 7 - Eloquent model for the `messages` table.
 * Columns: id, name, email, subject, message, created_at, updated_at
 *
 * Contact form (task 6) usage:  Message::create($validated);
 */
class Message extends Model
{
    protected $table = 'messages';

    // Mass assignment whitelist
    protected $fillable = ['name', 'email', 'subject', 'message'];
}
