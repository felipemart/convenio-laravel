<?php

use App\Livewire\Auth\Register;
use App\Models\User;
use App\Notifications\WecomeNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas};

it('renders successfully', function () {
    Livewire::test(Register::class)
        ->assertStatus(200);
});

it('should be able to register a new user in the system', function () {
    Livewire::test(Register::class)
        ->set('name', 'John Doe')
        ->set('email', 'johndoe@example.com')
        ->set('email_confirmation', 'johndoe@example.com')
        ->set('password', 'password')
        ->call('submit')
        ->assertHasNoErrors();

    assertDatabaseHas('users', [
        'name'  => 'John Doe',
        'email' => 'johndoe@example.com',
    ]);

    assertDatabaseCount('users', 1);
});

test('validation rules', function ($f) {
    if ($f->rule == 'unique') {
        User::factory()->create([$f->field => $f->value]);
    }

    $livewire = Livewire::test(Register::class)
        ->set($f->field, $f->value);

    if (property_exists($f, 'aValue')) {
        $livewire->set($f->aField, $f->aValue);
    }

    $livewire->call('submit')
        ->assertHasErrors([$f->field => $f->rule]);
})->with([
    'name::required'     => (object)['field' => 'name', 'value' => '', 'rule' => 'required'],
    'name::max:255'      => (object)['field' => 'name', 'value' => str_repeat('*', 256), 'rule' => 'max'],
    'email::required'    => (object)['field' => 'email', 'value' => '', 'rule' => 'required'],
    'email::email'       => (object)['field' => 'email', 'value' => 'not-an-email', 'rule' => 'email'],
    'email::max:255'     => (object)['field' => 'email', 'value' => str_repeat('*' . '@doe.com', 256), 'rule' => 'max'],
    'email::confirmed'   => (object)['field' => 'email', 'value' => 'joe@doe.com', 'rule' => 'confirmed'],
    'email::unique'      => (object)['field' => 'email', 'value' => 'joe@doe.com', 'rule' => 'unique', 'aField' => 'email_confirmation', 'aValue' => 'joe@doe.com'],
    'password::required' => (object)['field' => 'password', 'value' => '', 'rule' => 'required'],
]);

test('should be able to confirm email', function () {
    Notification::fake();

    Livewire::test(Register::class)
        ->set('name', 'John Doe')
        ->set('email', 'johndoe@example.com')
        ->set('email_confirmation', 'johndoe@example.com')
        ->set('password', 'password')
        ->call('submit');

    $user = User::where('email', 'johndoe@example.com')->first();

    Notification::assertSentTo($user, WecomeNotification::class);

});
