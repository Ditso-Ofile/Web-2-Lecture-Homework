<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Models\User;

// Headers the React (Inertia) frontend sends when it follows a redirect after login
function inertiaHeaders(): array
{
    return [
        'X-Inertia' => 'true',
        'X-Inertia-Version' => (string) app(HandleInertiaRequests::class)->version(request()),
    ];
}

test('a blade page requested by inertia is opened with a full page load', function () {
    $this->actingAs(User::factory()->create());

    $this->withHeaders(inertiaHeaders())
        ->get('/messages')
        ->assertStatus(409)
        ->assertHeader('X-Inertia-Location', url('/messages'));
});

test('a normal browser request still gets the blade page', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/messages')->assertOk()->assertSee('Messages');
});

test('inertia pages such as login are not affected', function () {
    $this->withHeaders(inertiaHeaders())
        ->get('/login')
        ->assertOk()
        ->assertHeader('X-Inertia', 'true');
});
