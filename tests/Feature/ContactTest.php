<?php

use App\Models\Message;

// TASK 6 - Contact form tests (Pest)

test('guests can open the contact form', function () {
    $this->get('/contact')->assertOk()->assertSee('Send message');
});

test('a valid message is saved to the database', function () {
    $this->post('/contact', [
        'name' => 'Kovács Anna',
        'email' => 'anna@example.com',
        'subject' => 'Question',
        'message' => 'Where does the population data come from?',
    ])->assertRedirect('/contact')->assertSessionHas('success');

    expect(Message::count())->toBe(1);
    expect(Message::first()->email)->toBe('anna@example.com');
});

test('the server rejects an empty form', function () {
    $this->from('/contact')
        ->post('/contact', [])
        ->assertRedirect('/contact')
        ->assertSessionHasErrors(['name', 'email', 'message']);

    expect(Message::count())->toBe(0);
});

test('the server rejects an invalid e-mail and a too short message', function () {
    $this->from('/contact')
        ->post('/contact', ['name' => 'Anna', 'email' => 'not-an-email', 'message' => 'Hi'])
        ->assertSessionHasErrors(['email', 'message'])
        ->assertSessionDoesntHaveErrors('name');
});
