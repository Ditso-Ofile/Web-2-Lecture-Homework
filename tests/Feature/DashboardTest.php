<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

// /dashboard now forwards logged-in users to our themed Mainpage
test('authenticated users are sent from the dashboard to the mainpage', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get('/dashboard')->assertRedirect(route('home'));
});
