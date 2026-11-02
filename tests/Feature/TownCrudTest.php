<?php

use App\Models\County;
use App\Models\Population;
use App\Models\Town;
use App\Models\User;

// TASK 9 - CRUD tests (Pest)

beforeEach(function () {
    County::create(['id' => 1, 'cname' => 'Csongrád']);
    County::create(['id' => 2, 'cname' => 'Békés']);
    Town::create(['id' => 10, 'tname' => 'Szeged', 'countyid' => 1, 'countyseat' => true, 'countylevel' => true]);
    Population::create(['townid' => 10, 'ryear' => 2019, 'women' => 86000, 'total' => 162000]);

    $this->admin = User::factory()->create(['role' => 1]);
    $this->user  = User::factory()->create(['role' => 2]);
});

test('guests are sent to the login page', function () {
    $this->get('/crud')->assertRedirect('/login');
});

test('a normal registered user cannot open the crud menu', function () {
    $this->actingAs($this->user)->get('/crud')->assertRedirect(route('home'));
});

test('the admin sees the list of towns', function () {
    $this->actingAs($this->admin)->get('/crud')->assertOk()->assertSee('Szeged');
});

test('the admin can create a town', function () {
    $this->actingAs($this->admin)
        ->post('/crud', ['tname' => 'Gyula', 'countyid' => 2, 'countyseat' => '1'])
        ->assertRedirect(route('crud.index'));

    $town = Town::where('tname', 'Gyula')->first();
    expect($town)->not->toBeNull();
    expect($town->id)->toBe(11);              // next free id
    expect($town->countyseat)->toBeTrue();
    expect($town->countylevel)->toBeFalse();  // unticked checkbox
});

test('invalid data is rejected on the server', function () {
    $this->actingAs($this->admin)
        ->from('/crud/create')
        ->post('/crud', ['tname' => 'Szeged', 'countyid' => 99])
        ->assertRedirect('/crud/create')
        ->assertSessionHasErrors(['tname', 'countyid']);
});

test('the admin can update a town', function () {
    $this->actingAs($this->admin)
        ->put('/crud/10', ['tname' => 'Szeged City', 'countyid' => 1, 'countyseat' => '1', 'countylevel' => '1'])
        ->assertRedirect(route('crud.index'));

    expect(Town::find(10)->tname)->toBe('Szeged City');
});

test('the admin can delete a town together with its population records', function () {
    $this->actingAs($this->admin)->delete('/crud/10')->assertRedirect(route('crud.index'));

    expect(Town::find(10))->toBeNull();
    expect(Population::where('townid', 10)->count())->toBe(0);
});
