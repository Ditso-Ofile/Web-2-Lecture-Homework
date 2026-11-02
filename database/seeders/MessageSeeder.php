<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Seeder;

/**
 * TASK 7 - A few sample messages so the Messages page is not empty after
 * `php artisan migrate:fresh --seed`. Real messages come from the Contact form.
 */
class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            ['name' => 'Kovács Anna',  'email' => 'anna.kovacs@example.com', 'subject' => 'Data source',
             'message' => 'Hello! Where does the population data come from? Thanks.', 'days' => 9],
            ['name' => 'Nagy Péter',   'email' => 'peter.nagy@example.com',  'subject' => 'Missing village',
             'message' => 'I could not find my village in the database. Do you plan to add villages too?', 'days' => 6],
            ['name' => 'John Smith',   'email' => 'john.smith@example.com',  'subject' => 'Great site',
             'message' => "Really nice charts.\nCould you add a comparison of two towns?", 'days' => 3],
            ['name' => 'Tóth Eszter',  'email' => 'eszter.toth@example.com', 'subject' => null,
             'message' => 'The Szeged numbers look correct, thank you for the work!', 'days' => 1],
        ];

        foreach ($samples as $s) {
            $time = now()->subDays($s['days']);

            $message = new Message([
                'name'    => $s['name'],
                'email'   => $s['email'],
                'subject' => $s['subject'],
                'message' => $s['message'],
            ]);
            $message->created_at = $time;
            $message->updated_at = $time;
            $message->save();
        }
    }
}
