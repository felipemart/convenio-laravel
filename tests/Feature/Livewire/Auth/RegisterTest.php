<?php

declare(strict_types = 1);

use App\Livewire\Auth\Register;
use App\Models\User;
use App\Notifications\BemVindoNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

test('render the livewire view', function (): void {
    Livewire::test(Register::class)
        ->assertStatus(200);
});

test('Should be able to register a new user in the system', function (): void {
    Livewire::test(Register::class)
        ->set('name', 'John Doe')
        ->set('email', 'johndoe@example.com')
        ->set('email_confirmation', 'johndoe@example.com')
        ->set('password', 'password')
        ->call('registrarUsuario')
        ->assertHasNoErrors();

    assertDatabaseHas('users', [
        'name'  => 'John Doe',
        'email' => 'johndoe@example.com',
    ]);

    assertDatabaseCount('users', 1);
});

test('Validation rules', function ($f): void {
    if ($f->rule == 'unique') {
        User::factory()->create([$f->field => $f->value]);
    }

    $livewire = Livewire::test(Register::class)
        ->set($f->field, $f->value);

    if (property_exists($f, 'aValue')) {
        $livewire->set($f->aField, $f->aValue);
    }

    $livewire->call('registrarUsuario')
        ->assertHasErrors([$f->field => $f->rule]);
})->with([
    'name::required'     => (object)['field' => 'name', 'value' => '', 'rule' => 'required'],
    'name::max:255'      => (object)['field' => 'name', 'value' => str_repeat('*', 256), 'rule' => 'max'],
    'email::required'    => (object)['field' => 'email', 'value' => '', 'rule' => 'required'],
    'email::email'       => (object)['field' => 'email', 'value' => 'not-an-email', 'rule' => 'email'],
    'email::max:255'     => (object)['field' => 'email', 'value' => str_repeat('*@doe.com', 256), 'rule' => 'max'],
    'email::confirmed'   => (object)['field' => 'email', 'value' => 'joe@doe.com', 'rule' => 'confirmed'],
    'email::unique'      => (object)['field' => 'email', 'value' => 'joe@doe.com', 'rule' => 'unique', 'aField' => 'email_confirmation', 'aValue' => 'joe@doe.com'],
    'password::required' => (object)['field' => 'password', 'value' => '', 'rule' => 'required'],
]);

test('should be able to confirm the email', function (): void {
    Notification::fake();

    Livewire::test(Register::class)
        ->set('name', 'John Doe')
        ->set('email', 'johndoe@example.com')
        ->set('email_confirmation', 'johndoe@example.com')
        ->set('password', 'password')
        ->call('registrarUsuario');

    $user = User::where('email', 'johndoe@example.com')->first();

    Notification::assertSentTo($user, BemVindoNotification::class);
});
