<?php

use App\Filament\Resources\ClientResource\Pages\CreateClient;
use App\Models\Client;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\LiveWire\livewire;

test('can create client', function () {
    actingAs(User::factory()->make());

    $first_name = fake()->firstName;
    $last_name = fake()->lastName;
    $email = fake()->email;

    livewire(CreateClient::class)
        ->assertFormExists()
        ->fillForm([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $clients = Client::all();

    expect($clients)
        ->toHaveCount(1)
        ->and($clients->first())
        ->toMatchArray([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email
        ]);
});
